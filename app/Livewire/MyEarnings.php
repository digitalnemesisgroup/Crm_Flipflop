<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Earning;
use App\Models\PayoutRequest;
use Illuminate\Support\Facades\Auth;
use App\Services\NotificationService;

class MyEarnings extends Component
{
    public $request_amount;
    public $notes;
    public $isModalOpen = false;

    public function mount()
    {
        if (Auth::user()->earnings()->sum('amount') <= 0) {
            return redirect()->route('dashboard');
        }
    }

    public function render()
    {
        $user = Auth::user();
        
        // 1. Total Earning (Approved total earning, which are cleared or paid)
        $totalEarning = Earning::where('user_id', $user->id)
            ->whereIn('status', ['cleared', 'paid'])
            ->sum('amount');
            
        // 2. Paid (Actual paid amount)
        $paid = PayoutRequest::where('user_id', $user->id)
            ->where('status', 'paid')
            ->sum('amount');
            
        // 3. Due (Approved earning minus Paid)
        $due = max(0, $totalEarning - $paid);
        
        // 4. Requested (Hold amount)
        $requested = PayoutRequest::where('user_id', $user->id)
            ->whereIn('status', ['pending', 'approved'])
            ->sum('amount');
            
        // 5. Available (Amount available for payout request right now)
        $available = max(0, $totalEarning - $paid - $requested);
        
        // Fetch payout history
        $payoutRequests = PayoutRequest::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();
            
        // Fetch earnings history
        $earningsHistory = Earning::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('livewire.my-earnings', [
            'totalEarning' => $totalEarning,
            'paid' => $paid,
            'due' => $due,
            'requested' => $requested,
            'available' => $available,
            'payoutRequests' => $payoutRequests,
            'earningsHistory' => $earningsHistory,
        ])->layout('layouts.app', ['header' => 'My Earnings & Payouts']);
    }

    public function requestPayout()
    {
        $this->resetInputFields();
        $this->isModalOpen = true;
    }

    public function submitRequest()
    {
        $this->validate([
            'request_amount' => 'required|numeric|min:0.01',
            'notes' => 'nullable|string|max:255',
        ]);
        
        $user = Auth::user();

        // Calculate available balance
        $totalEarning = Earning::where('user_id', $user->id)->whereIn('status', ['cleared', 'paid'])->sum('amount');
        $paid = PayoutRequest::where('user_id', $user->id)->where('status', 'paid')->sum('amount');
        $requested = PayoutRequest::where('user_id', $user->id)->whereIn('status', ['pending', 'approved'])->sum('amount');
        $available = max(0, $totalEarning - $paid - $requested);

        if ($this->request_amount > $available) {
            $this->addError('request_amount', 'Requested amount exceeds your available balance.');
            return;
        }

        PayoutRequest::create([
            'user_id' => $user->id,
            'amount' => $this->request_amount,
            'status' => 'pending',
            'notes' => $this->notes,
        ]);
        
        // Notify admins
        $requestCount = PayoutRequest::where('user_id', $user->id)->count();
        NotificationService::newPayoutRequest($user->name, $this->request_amount, $requestCount);
        
        \App\Models\ActivityLog::create([
            'user_id' => $user->id,
            'action' => 'Payout request created',
            'old_value' => null,
            'new_value' => 'Requested Amount: ' . $this->request_amount,
            'ip_address' => request()->ip(),
            'device' => request()->header('User-Agent'),
        ]);

        session()->flash('message', 'Payout Request Submitted Successfully. Your amount is now on hold.');

        $this->closeModal();
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
        $this->resetInputFields();
    }

    private function resetInputFields()
    {
        $this->request_amount = '';
        $this->notes = '';
    }
}
