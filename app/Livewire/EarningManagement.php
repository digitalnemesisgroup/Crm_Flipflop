<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Earning;
use App\Models\User;
use App\Models\Project;

class EarningManagement extends Component
{
    use WithPagination;

    public $search = '';
    public $isModalOpen = false;
    
    // Form fields
    public $earning_id;
    public $user_id = '';
    public $project_id = '';
    public $amount;
    public $date;
    public $description = '';
    public $status = 'pending';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $earnings = Earning::with(['user', 'project'])
            ->whereHas('user', function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%');
            })
            ->orWhereHas('project', function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%');
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        $users = User::role(['EMPLOYEE', 'FREELANCER'])->orderBy('name')->get();
        $projects = Project::orderBy('name')->get();

        return view('livewire.earning-management', [
            'earnings' => $earnings,
            'users' => $users,
            'projects' => $projects,
        ])->layout('layouts.app', ['header' => 'Employee & Freelancer Earnings']);
    }

    public function create()
    {
        $this->resetInputFields();
        $this->date = date('Y-m-d');
        $this->isModalOpen = true;
    }

    public function edit($id)
    {
        $earning = Earning::findOrFail($id);
        $this->earning_id = $id;
        $this->user_id = $earning->user_id;
        $this->project_id = $earning->project_id;
        $this->amount = $earning->amount;
        $this->date = $earning->date->format('Y-m-d');
        $this->description = $earning->description;
        $this->status = $earning->status;
        $this->isModalOpen = true;
    }

    public function store()
    {
        $this->validate([
            'user_id' => 'required|exists:users,id',
            'project_id' => 'required|exists:projects,id',
            'amount' => 'required|numeric|min:0.01',
            'date' => 'required|date',
            'description' => 'required|string|max:255',
            'status' => 'required|in:pending,cleared,paid',
        ]);

        Earning::updateOrCreate(['id' => $this->earning_id], [
            'user_id' => $this->user_id,
            'project_id' => $this->project_id,
            'amount' => $this->amount,
            'date' => $this->date,
            'description' => $this->description,
            'status' => $this->status,
        ]);

        session()->flash('message', $this->earning_id ? 'Earning Updated Successfully.' : 'Earning Recorded Successfully.');

        $this->closeModal();
    }

    public function markCleared($id)
    {
        $earning = Earning::findOrFail($id);
        $earning->update(['status' => 'cleared']);
        session()->flash('message', 'Earning Marked as Cleared (Funds Available).');
    }

    public function delete($id)
    {
        Earning::find($id)->delete();
        session()->flash('message', 'Earning Deleted Successfully.');
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
        $this->resetInputFields();
    }

    private function resetInputFields()
    {
        $this->earning_id = null;
        $this->user_id = '';
        $this->project_id = '';
        $this->amount = '';
        $this->date = '';
        $this->description = '';
        $this->status = 'pending';
    }
}
