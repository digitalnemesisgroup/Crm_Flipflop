<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Invoice;
use App\Models\Project;
use App\Models\Client;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use App\Services\NotificationService;

class InvoiceManagement extends Component
{
    use WithPagination;

    public $search = '';
    public $isModalOpen = false;
    public $isPaymentModalOpen = false;
    
    // Invoice Form fields
    public $invoice_id;
    public $invoice_number;
    public $project_id = '';
    public $client_id = '';
    public $issue_date;
    public $due_date;
    public $amount;
    public $status = 'draft';
    public $notes;
    
    // Payment Form fields
    public $payment_amount;
    public $payment_date;
    public $payment_method = '';
    public $reference_number = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $invoices = Invoice::with(['client', 'project', 'payments'])
            ->where('invoice_number', 'like', '%' . $this->search . '%')
            ->orWhereHas('client', function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%');
            })
            ->orWhereHas('project', function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%');
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        $clients = Client::orderBy('name')->get();
        // Only active or completed projects make sense for invoicing typically
        $projects = Project::whereIn('status', ['active', 'completed'])->orderBy('name')->get();

        return view('livewire.invoice-management', [
            'invoices' => $invoices,
            'clients' => $clients,
            'projects' => $projects,
        ])->layout('layouts.app', ['header' => 'Billing & Invoices']);
    }

    public $milestone_percentage = '';

    public function updatedProjectId($value)
    {
        // Auto-select client based on project
        if ($value) {
            $project = Project::find($value);
            if ($project) {
                $this->client_id = $project->client_id;
                $this->calculateMilestoneAmount();
            }
        }
    }
    
    public function updatedMilestonePercentage($value)
    {
        $this->calculateMilestoneAmount();
    }
    
    private function calculateMilestoneAmount()
    {
        if ($this->project_id && $this->milestone_percentage) {
            $project = Project::find($this->project_id);
            if ($project) {
                $percentage = (float) $this->milestone_percentage;
                $this->amount = ($project->total_budget * $percentage) / 100;
            }
        }
    }

    public function create()
    {
        $this->resetInputFields();
        $this->invoice_number = 'INV-' . strtoupper(Str::random(6)); // Auto-generate
        $this->issue_date = date('Y-m-d');
        $this->isModalOpen = true;
    }

    public function edit($id)
    {
        $invoice = Invoice::findOrFail($id);
        $this->invoice_id = $id;
        $this->invoice_number = $invoice->invoice_number;
        $this->project_id = $invoice->project_id;
        $this->client_id = $invoice->client_id;
        $this->issue_date = $invoice->issue_date->format('Y-m-d');
        $this->due_date = $invoice->due_date->format('Y-m-d');
        $this->amount = $invoice->amount;
        $this->status = $invoice->status;
        $this->notes = $invoice->notes;
        $this->isModalOpen = true;
    }

    public function store()
    {
        $this->validate([
            'invoice_number' => ['required', 'string', 'max:255', Rule::unique('invoices')->ignore($this->invoice_id)],
            'project_id' => 'required|exists:projects,id',
            'client_id' => 'required|exists:clients,id',
            'issue_date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:issue_date',
            'amount' => 'required|numeric|min:0.01',
            'status' => 'required|in:draft,sent,paid,overdue,cancelled',
            'notes' => 'nullable|string',
        ]);

        Invoice::updateOrCreate(['id' => $this->invoice_id], [
            'invoice_number' => $this->invoice_number,
            'project_id' => $this->project_id,
            'client_id' => $this->client_id,
            'issue_date' => $this->issue_date,
            'due_date' => $this->due_date,
            'amount' => $this->amount,
            'status' => $this->status,
            'notes' => $this->notes,
        ]);

        session()->flash('message', $this->invoice_id ? 'Invoice Updated Successfully.' : 'Invoice Created Successfully.');

        $this->closeModal();
    }

    public function addPayment($id)
    {
        $this->resetPaymentFields();
        $invoice = Invoice::findOrFail($id);
        $this->invoice_id = $id;
        // Default payment amount to remaining balance
        $this->payment_amount = $invoice->amount - $invoice->amount_paid;
        $this->payment_date = date('Y-m-d');
        $this->isPaymentModalOpen = true;
    }
    
    public function storePayment()
    {
        $this->validate([
            'payment_amount' => 'required|numeric|min:0.01',
            'payment_date' => 'required|date',
            'payment_method' => 'nullable|string|max:255',
            'reference_number' => 'nullable|string|max:255',
        ]);
        
        $invoice = Invoice::findOrFail($this->invoice_id);
        
        $invoice->payments()->create([
            'amount_paid' => $this->payment_amount,
            'payment_date' => $this->payment_date,
            'payment_method' => $this->payment_method,
            'reference_number' => $this->reference_number,
        ]);
        
        // Auto update status to paid if fully paid
        if ($invoice->amount_paid >= $invoice->amount) {
            $invoice->update(['status' => 'paid']);
        }
        
        \App\Models\ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'Client payment received',
            'old_value' => null,
            'new_value' => 'Amount: ' . $this->payment_amount . ' for Invoice: ' . $invoice->invoice_number,
            'ip_address' => request()->ip(),
            'device' => request()->header('User-Agent'),
        ]);
        
        // --- Calculate Employee Earning ---
        $project = $invoice->project;
        if ($project && $project->employee_payout_percentage > 0) {
            $earningAmount = ($this->payment_amount * $project->employee_payout_percentage) / 100;
            
            $employees = $project->users()->whereHas('roles', function($q) {
                $q->whereIn('name', ['EMPLOYEE', 'FREELANCER']);
            })->get();
            
            if ($employees->count() > 0) {
                $splitAmount = $earningAmount / $employees->count();
                
                foreach ($employees as $employee) {
                    \App\Models\Earning::create([
                        'user_id' => $employee->id,
                        'project_id' => $project->id,
                        'amount' => $splitAmount,
                        'date' => now(),
                        'description' => 'Earning from Client Payment (Invoice #' . $invoice->invoice_number . ')',
                        'status' => 'cleared',
                    ]);

                    // Notify employee
                    NotificationService::clientPaymentReceived($employee->id, $project->name, $this->payment_amount);
                    NotificationService::earningUpdated($employee->id, $splitAmount);
                }
            }
        }
        
        session()->flash('message', 'Payment Recorded Successfully. Employee Earning automatically generated.');
        $this->isPaymentModalOpen = false;
    }

    public function delete($id)
    {
        Invoice::find($id)->delete();
        session()->flash('message', 'Invoice Deleted Successfully.');
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
        $this->isPaymentModalOpen = false;
        $this->resetInputFields();
    }

    private function resetInputFields()
    {
        $this->invoice_id = null;
        $this->invoice_number = '';
        $this->project_id = '';
        $this->client_id = '';
        $this->issue_date = '';
        $this->due_date = '';
        $this->amount = '';
        $this->status = 'draft';
        $this->notes = '';
    }
    
    private function resetPaymentFields()
    {
        $this->payment_amount = '';
        $this->payment_date = '';
        $this->payment_method = '';
        $this->reference_number = '';
    }
}
