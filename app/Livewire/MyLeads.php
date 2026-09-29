<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Lead;
use App\Models\Client;
use Illuminate\Support\Facades\Auth;

class MyLeads extends Component
{
    use WithPagination;

    public $search = '';
    public $isModalOpen = false;
    
    // Form fields
    public $lead_id;
    public $title;
    public $description;
    public $client_id = '';
    public $estimated_value;
    public $work_type = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $leads = Lead::with(['client'])
            ->where('assigned_to', Auth::id())
            ->where(function($q) {
                $q->where('title', 'like', '%' . $this->search . '%')
                  ->orWhereHas('client', function ($cq) {
                      $cq->where('name', 'like', '%' . $this->search . '%')
                         ->orWhere('company', 'like', '%' . $this->search . '%');
                  });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        $clients = Client::orderBy('name')->get();

        return view('livewire.my-leads', [
            'leads' => $leads,
            'clients' => $clients,
        ])->layout('layouts.app', ['header' => 'My Work / Leads']);
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
        $lead = Lead::where('assigned_to', Auth::id())->findOrFail($id);
        $this->lead_id = $id;
        $this->title = $lead->title;
        $this->description = $lead->description;
        $this->client_id = $lead->client_id ?? '';
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
            'estimated_value' => 'nullable|numeric|min:0',
            'work_type' => 'nullable|string|max:255',
        ]);

        $data = [
            'title' => $this->title,
            'description' => $this->description,
            'client_id' => $this->client_id ?: null,
            'assigned_to' => Auth::id(),
            'estimated_value' => $this->estimated_value,
            'work_type' => $this->work_type,
        ];

        if (!$this->lead_id) {
            // New leads start as 'new' status
            $data['status'] = 'new';
        } else {
            // Verify if lead is assigned to user and not matured
            $lead = Lead::findOrFail($this->lead_id);
            if (in_array($lead->status, ['matured', 'converted'])) {
                session()->flash('error', 'Cannot edit matured or converted leads.');
                return;
            }
        }

        Lead::updateOrCreate(['id' => $this->lead_id], $data);

        session()->flash('message', $this->lead_id ? 'Work Lead Updated Successfully.' : 'Work Lead Submitted Successfully. Pending Admin Review.');

        $this->closeModal();
    }

    public function delete($id)
    {
        $lead = Lead::where('assigned_to', Auth::id())->findOrFail($id);
        if (in_array($lead->status, ['matured', 'converted'])) {
            session()->flash('error', 'Cannot delete matured or converted leads.');
            return;
        }
        $lead->delete();
        session()->flash('message', 'Work Lead Deleted Successfully.');
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
        $this->resetInputFields();
    }

    private function resetInputFields()
    {
        $this->lead_id = null;
        $this->title = '';
        $this->description = '';
        $this->client_id = '';
        $this->estimated_value = null;
        $this->work_type = '';
    }
}
