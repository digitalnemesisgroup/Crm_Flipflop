<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Project;
use App\Models\Client;
use App\Models\Lead;
use App\Models\User;
use App\Services\NotificationService;

class ProjectManagement extends Component
{
    use WithPagination;

    public $search = '';
    public $isModalOpen = false;
    
    // Form fields
    public $project_id;
    public $name;
    public $description;
    public $client_id = '';
    public $lead_id = '';
    public $status = 'pending';
    public $start_date;
    public $end_date;
    public $total_budget;
    public $employee_payout_percentage;
    
    // For assigning users
    public $assigned_users = []; // Array of user IDs

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $projects = Project::with(['client', 'users'])
            ->where('name', 'like', '%' . $this->search . '%')
            ->orWhereHas('client', function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%');
            })
            ->paginate(10);
            
        $clients = Client::orderBy('name')->get();
        $leads = Lead::whereIn('status', ['new', 'contacted', 'qualified'])->orderBy('title')->get();
        // Allow assignment to employees and freelancers
        $users = User::whereHas('roles', function ($q) {
            $q->whereIn('name', ['EMPLOYEE', 'FREELANCER', 'MANAGER', 'ACCOUNT TEAM', 'Employee', 'Freelancer', 'Manager', 'Account Team']);
        })->orderBy('name')->get();

        return view('livewire.project-management', [
            'projects' => $projects,
            'clients' => $clients,
            'leads' => $leads,
            'users' => $users,
        ])->layout('layouts.app', ['header' => 'Project Management']);
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
        $project = Project::findOrFail($id);
        $this->project_id = $id;
        $this->name = $project->name;
        $this->description = $project->description;
        $this->client_id = $project->client_id;
        $this->lead_id = $project->lead_id ?? '';
        $this->status = $project->status;
        $this->start_date = $project->start_date?->format('Y-m-d');
        $this->end_date = $project->end_date?->format('Y-m-d');
        $this->total_budget = $project->total_budget;
        $this->employee_payout_percentage = $project->employee_payout_percentage;
        
        $this->assigned_users = $project->users->pluck('id')->toArray();
        
        $this->isModalOpen = true;
    }

    public function store()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'client_id' => 'required|exists:clients,id',
            'lead_id' => 'nullable|exists:leads,id',
            'status' => 'required|in:pending,active,completed,cancelled',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'total_budget' => 'nullable|numeric|min:0',
            'employee_payout_percentage' => 'nullable|numeric|min:0|max:100',
            'assigned_users' => 'nullable|array',
            'assigned_users.*' => 'exists:users,id',
        ]);

        $isNew = !$this->project_id;
        
        $project = Project::updateOrCreate(['id' => $this->project_id], [
            'name' => $this->name,
            'description' => $this->description,
            'client_id' => $this->client_id,
            'lead_id' => $this->lead_id ?: null,
            'status' => $this->status,
            'start_date' => $this->start_date ?: null,
            'end_date' => $this->end_date ?: null,
            'total_budget' => $this->total_budget,
            'employee_payout_percentage' => $this->employee_payout_percentage,
        ]);
        
        // Sync assigned users and notify newly assigned employees
        $previousUserIds = $project->users()->pluck('users.id')->toArray();
        $project->users()->sync($this->assigned_users);
        
        // Fire notifications for newly assigned users
        $newlyAssigned = array_diff($this->assigned_users, $previousUserIds);
        foreach ($newlyAssigned as $userId) {
            NotificationService::projectAssigned($userId, $project->name, $project->id);
        }
        
        // If project just started, notify all assigned employees
        if ($this->status === 'active' && ($project->wasChanged('status') || $isNew)) {
            foreach ($this->assigned_users as $userId) {
                NotificationService::projectStarted($userId, $project->name, $project->id);
            }
        }

        // If project completed, notify all assigned employees
        if ($this->status === 'completed' && $project->wasChanged('status')) {
            foreach ($this->assigned_users as $userId) {
                NotificationService::projectCompleted($userId, $project->name);
            }
        }

        \App\Models\ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => $isNew ? 'Admin created project' : 'Admin updated project details',
            'old_value' => null,
            'new_value' => 'Project: ' . $this->name . ' | Status: ' . $this->status,
            'ip_address' => request()->ip(),
            'device' => request()->header('User-Agent'),
        ]);

        session()->flash('message', $isNew ? 'Project Created Successfully.' : 'Project Updated Successfully.');

        $this->closeModal();
    }

    public function delete($id)
    {
        Project::find($id)->delete();
        session()->flash('message', 'Project Deleted Successfully.');
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
        $this->resetInputFields();
    }

    private function resetInputFields()
    {
        $this->project_id = null;
        $this->name = '';
        $this->description = '';
        $this->client_id = '';
        $this->lead_id = '';
        $this->status = 'pending';
        $this->start_date = null;
        $this->end_date = null;
        $this->total_budget = null;
        $this->employee_payout_percentage = null;
        $this->assigned_users = [];
    }
}
