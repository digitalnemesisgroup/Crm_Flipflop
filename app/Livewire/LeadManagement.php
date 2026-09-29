<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Lead;
use App\Models\Client;
use App\Models\User;
use App\Models\Project;

class LeadManagement extends Component
{
    use WithPagination;

    public $search = '';
    public $isModalOpen = false;
    public $isConvertModalOpen = false;
    
    // Conversion fields
    public $convert_lead_id = null;
    public $convert_payout_percentage = 40;
    
    // Form fields
    public $lead_id;
    public $title;
    public $description;
    public $client_id = '';
    public $assigned_to = '';
    public $status = 'new';
    public $estimated_value;
    public $work_type = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $leads = Lead::with(['client', 'assignee'])
            ->where('title', 'like', '%' . $this->search . '%')
            ->orWhereHas('client', function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('company', 'like', '%' . $this->search . '%');
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        $clients = Client::orderBy('name')->get();
        // Allow assignment to account team, managers, or employees
        $users = User::whereHas('roles', function ($q) {
            $q->whereIn('name', ['ACCOUNT TEAM', 'MANAGER', 'EMPLOYEE', 'FREELANCER', 'Account Team', 'Manager', 'Employee', 'Freelancer']);
        })->orderBy('name')->get();

        return view('livewire.lead-management', [
            'leads' => $leads,
            'clients' => $clients,
            'users' => $users,
        ])->layout('layouts.app', ['header' => 'Lead/Work Management']);
    }

    public function create()
    {
        $this->resetInputFields();
        $this->isModalOpen = true;
    }

    public function openCreateModal()
    {
        $this->create();
    }

    public function edit($id)
    {
        $lead = Lead::findOrFail($id);
        $this->lead_id = $id;
        $this->title = $lead->title;
        $this->description = $lead->description;
        $this->client_id = $lead->client_id ?? '';
        $this->assigned_to = $lead->assigned_to ?? '';
        $this->status = $lead->status;
        $this->estimated_value = $lead->estimated_value;
        $this->work_type = $lead->work_type ?? '';
        $this->isModalOpen = true;
    }

    public function store()
    {
        $this->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'client_id' => 'nullable|exists:clients,id',
            'assigned_to' => 'nullable|exists:users,id',
            'status' => 'required|in:new,contacted,follow-up,interested,matured,converted,lost',
            'estimated_value' => 'nullable|numeric|min:0',
            'work_type' => 'nullable|string|max:255',
        ]);

        Lead::updateOrCreate(['id' => $this->lead_id], [
            'title' => $this->title,
            'description' => $this->description,
            'client_id' => $this->client_id ?: null,
            'assigned_to' => $this->assigned_to ?: null,
            'status' => $this->status,
            'estimated_value' => $this->estimated_value,
            'work_type' => $this->work_type,
        ]);

        session()->flash('message', $this->lead_id ? 'Lead Updated Successfully.' : 'Lead Created Successfully.');

        $this->closeModal();
    }
    
    public function openConvertModal($id)
    {
        $this->convert_lead_id = $id;
        $this->convert_payout_percentage = 40;
        $this->isConvertModalOpen = true;
    }

    public function processConversion()
    {
        $this->validate([
            'convert_payout_percentage' => 'required|numeric|min:0|max:100',
        ]);

        $lead = Lead::findOrFail($this->convert_lead_id);
        
        if (!$lead->client_id) {
            session()->flash('error', 'Cannot convert to project: Lead must have a Client assigned first.');
            $this->closeModal();
            return;
        }
        
        $project = Project::create([
            'name' => $lead->title,
            'client_id' => $lead->client_id,
            'status' => 'created',
            'total_budget' => $lead->estimated_value ?? 0,
            'start_date' => now(),
            'description' => $lead->description ?? 'Converted from Lead ID: ' . $lead->id,
            'employee_payout_percentage' => $this->convert_payout_percentage,
        ]);
        
        if ($lead->assigned_to) {
            $project->users()->attach($lead->assigned_to, ['role_in_project' => 'Converted Lead Assignee']);
        }
        
        $lead->update(['status' => 'converted']);
        
        session()->flash('message', 'Success! Project created automatically from Matured Lead.');
        $this->closeModal();
    }

    public function delete($id)
    {
        Lead::find($id)->delete();
        session()->flash('message', 'Lead Deleted Successfully.');
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
        $this->isConvertModalOpen = false;
        $this->resetInputFields();
        $this->convert_lead_id = null;
    }

    private function resetInputFields()
    {
        $this->lead_id = null;
        $this->title = '';
        $this->description = '';
        $this->client_id = '';
        $this->assigned_to = '';
        $this->status = 'new';
        $this->estimated_value = null;
        $this->work_type = '';
    }
}
