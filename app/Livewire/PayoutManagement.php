<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\PayoutRequest;
use App\Models\User;
use App\Models\Earning;
use App\Services\NotificationService;

class PayoutManagement extends Component
{
    use WithPagination;

    public $search = '';
    public $isModalOpen = false;
    public $isProcessModalOpen = false;
    
    // Request Form fields
    public $request_id;
    public $user_id = '';
    public $amount;
    public $notes = '';
    
    // Process Form fields
    public $admin_notes = '';
    public $transaction_date;
    public $payment_method = '';
    public $reference_number = '';
    public $action_type = ''; // 'approve', 'reject', or 'hold'

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $requests = PayoutRequest::with(['user', 'transaction'])
            ->whereHas('user', function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%');
            })
            ->orderByRaw("CASE status WHEN 'pending' THEN 1 WHEN 'approved' THEN 2 WHEN 'paid' THEN 3 WHEN 'rejected' THEN 4 ELSE 5 END")
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        $users = User::role(['EMPLOYEE', 'FREELANCER'])->orderBy('name')->get();
        
        // Calculate available balance for the selected user if any
        $available_balance = 0;
        if ($this->user_id) {
            $cleared_earnings = Earning::where('user_id', $this->user_id)->where('status', 'cleared')->sum('amount');
            $pending_payouts = PayoutRequest::where('user_id', $this->user_id)->whereIn('status', ['pending', 'approved'])->sum('amount');
            $available_balance = $cleared_earnings - $pending_payouts;
        }

        return view('livewire.payout-management', [
            'requests' => $requests,
            'users' => $users,
            'available_balance' => $available_balance,
        ])->layout('layouts.app', ['header' => 'Payout Requests']);
    }

    public function create()
    {
        $this->resetInputFields();
        $this->isModalOpen = true;
    }

    public function storeRequest()
    {
        $this->validate([
            'user_id' => 'required|exists:users,id',
            'amount' => 'required|numeric|min:0.01',
            'notes' => 'nullable|string|max:255',
        ]);
        
        // Verify balance
        $cleared_earnings = Earning::where('user_id', $this->user_id)->where('status', 'cleared')->sum('amount');
        $pending_payouts = PayoutRequest::where('user_id', $this->user_id)->whereIn('status', ['pending', 'approved'])->sum('amount');
        $available_balance = $cleared_earnings - $pending_payouts;

        if ($this->amount > $available_balance) {
            $this->addError('amount', 'Requested amount exceeds available balance.');
            return;
        }

        PayoutRequest::create([
            'user_id' => $this->user_id,
            'amount' => $this->amount,
            'status' => 'pending',
            'notes' => $this->notes,
        ]);

        // Notify admins of new payout request
        $employee = User::find($this->user_id);
        $requestCount = PayoutRequest::where('user_id', $this->user_id)->count();
        NotificationService::newPayoutRequest(
            $employee->name ?? 'Employee',
            $this->amount,
            $requestCount
        );

        session()->flash('message', 'Payout Request Submitted Successfully.');
        $this->closeModal();
    }
    
    public function processRequest($id, $type)
    {
        $this->resetProcessFields();
        $this->request_id = $id;
        $this->action_type = $type;
        $this->transaction_date = date('Y-m-d');
        $this->isProcessModalOpen = true;
    }

    public function storeProcess()
    {
        $req = PayoutRequest::findOrFail($this->request_id);
        
        if ($this->action_type === 'reject') {
            $this->validate([
                'admin_notes' => 'required|string|max:255',
            ]);
            
            $req->update([
                'status' => 'rejected',
                'admin_notes' => $this->admin_notes,
            ]);
            
            // Notify employee
            NotificationService::payoutRejected($req->user_id, $req->amount, $this->admin_notes);
            
            \App\Models\ActivityLog::create([
                'user_id' => auth()->id(),
                'action' => 'Payout rejected',
                'old_value' => 'Status: pending',
                'new_value' => 'Status: rejected | Reason: ' . $this->admin_notes,
                'ip_address' => request()->ip(),
                'device' => request()->header('User-Agent'),
            ]);
            
            session()->flash('message', 'Payout Request Rejected.');
        } elseif ($this->action_type === 'hold') {
            $this->validate([
                'admin_notes' => 'required|string|max:255',
            ]);
            
            $req->update([
                'status' => 'hold',
                'admin_notes' => $this->admin_notes,
            ]);
            
            // No notification on hold — admin decision, keep silent.
            
            \App\Models\ActivityLog::create([
                'user_id' => auth()->id(),
                'action' => 'Payout put on hold',
                'old_value' => 'Status: pending',
                'new_value' => 'Status: hold | Reason: ' . $this->admin_notes,
                'ip_address' => request()->ip(),
                'device' => request()->header('User-Agent'),
            ]);
            
            session()->flash('message', 'Payout Request Placed on Hold.');
        } elseif ($this->action_type === 'approve') {
            $this->validate([
                'transaction_date' => 'required|date',
                'payment_method' => 'required|string|max:255',
                'reference_number' => 'nullable|string|max:255',
                'admin_notes' => 'nullable|string|max:255',
            ]);
            
            // Create Transaction
            $req->transaction()->create([
                'amount' => $req->amount,
                'transaction_date' => $this->transaction_date,
                'payment_method' => $this->payment_method,
                'reference_number' => $this->reference_number,
            ]);
            
            $req->update([
                'status' => 'paid',
                'admin_notes' => $this->admin_notes,
            ]);
            
            // Update Earnings Status to 'paid' for this user up to the amount
            $amount_to_cover = $req->amount;
            $earnings = Earning::where('user_id', $req->user_id)->where('status', 'cleared')->orderBy('date')->get();
            
            foreach($earnings as $earning) {
                if ($amount_to_cover <= 0) break;
                $earning->update(['status' => 'paid']);
                $amount_to_cover -= $earning->amount;
            }
            
            \App\Models\ActivityLog::create([
                'user_id' => auth()->id(),
                'action' => 'Admin approved payout',
                'old_value' => 'Status: pending',
                'new_value' => 'Status: paid | Amount: ' . $req->amount,
                'ip_address' => request()->ip(),
                'device' => request()->header('User-Agent'),
            ]);
            
            // Notify employee — payout paid
            NotificationService::payoutPaid(
                $req->user_id,
                $req->amount,
                $this->reference_number ?: 'N/A'
            );
            
            session()->flash('message', 'Payout Request Approved and Transaction Recorded.');
        }

        $this->closeModal();
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
        $this->isProcessModalOpen = false;
        $this->resetInputFields();
        $this->resetProcessFields();
    }

    private function resetInputFields()
    {
        $this->request_id = null;
        $this->user_id = '';
        $this->amount = '';
        $this->notes = '';
    }
    
    private function resetProcessFields()
    {
        $this->admin_notes = '';
        $this->transaction_date = '';
        $this->payment_method = '';
        $this->reference_number = '';
        $this->action_type = '';
    }
}
