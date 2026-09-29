<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Task;
use App\Models\User;
use App\Models\Project;
use App\Models\Earning;
use App\Models\TaskSubmission;

class UserTaskManagement extends Component
{
    use WithPagination;

    public $user;
    public $date;
    public $fromDate = '';
    public $toDate = '';
    public $search = '';
    
    // Form fields for editing task
    public $isModalOpen = false;
    public $task_id;
    public $status = 'assigned';
    public $tasks_form = [];
    public $projects;

    // Verify fields
    public $isVerifyModalOpen = false;
    public $verify_task;
    public $active_submission_id;
    public $admin_feedback = '';
    public $verify_action = '';

    public function mount($userId, $date = null)
    {
        $this->user = User::findOrFail($userId);
        $this->date = $date ?: date('Y-m-d');
        $this->fromDate = $this->date;
        $this->toDate = $this->date;
        $this->projects = Project::orderBy('name')->get();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFromDate()
    {
        $this->resetPage();
    }

    public function updatingToDate()
    {
        $this->resetPage();
    }

    public function render()
    {
        $startDateExpr = \Illuminate\Support\Facades\DB::raw('DATE(created_at)');
        $endDateExpr   = \Illuminate\Support\Facades\DB::raw('COALESCE(due_date, DATE(created_at))');

        $query = Task::with(['project', 'submissions'])
            ->where('user_id', $this->user->id);

        if ($this->fromDate && $this->toDate) {
            $query->where(function ($q) use ($startDateExpr, $endDateExpr) {
                $q->where($startDateExpr, '<=', $this->toDate)
                  ->where($endDateExpr, '>=', $this->fromDate);
            });
        } elseif ($this->fromDate) {
            $query->where($endDateExpr, '>=', $this->fromDate);
        } elseif ($this->toDate) {
            $query->where($startDateExpr, '<=', $this->toDate);
        }

        if ($this->search) {
            $query->where(function($q) {
                $q->where('title', 'like', '%' . $this->search . '%')
                  ->orWhereHas('project', function($pq) {
                      $pq->where('name', 'like', '%' . $this->search . '%');
                  });
            });
        }

        $tasks = $query->orderByRaw("CASE status WHEN 'submitted' THEN 1 WHEN 'assigned' THEN 2 WHEN 'rejected' THEN 3 WHEN 'approved' THEN 4 ELSE 5 END")
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $dateRangeText = ($this->fromDate && $this->toDate && $this->fromDate === $this->toDate)
            ? \Carbon\Carbon::parse($this->fromDate)->format('M d, Y')
            : (($this->fromDate && $this->toDate)
                ? \Carbon\Carbon::parse($this->fromDate)->format('M d, Y') . ' - ' . \Carbon\Carbon::parse($this->toDate)->format('M d, Y')
                : 'Filtered');

        return view('livewire.user-task-management', [
            'tasks' => $tasks,
        ])->layout('layouts.app', ['header' => $this->user->name . '\'s Tasks (' . $dateRangeText . ')']);
    }

    public function edit($id)
    {
        $task = Task::findOrFail($id);
        $this->task_id = $id;
        $this->status = $task->status;
        
        $this->tasks_form = [
            [
                'project_id' => $task->project_id ?? '',
                'title' => $task->title,
                'description' => $task->description,
                'amount' => $task->amount,
                'due_date' => $task->due_date ? $task->due_date->format('Y-m-d') : null,
                'target_count' => $task->target_count ?? 1,
            ]
        ];
        
        $this->isModalOpen = true;
    }

    public function store()
    {
        $this->validate([
            'status' => 'required|in:assigned,submitted,approved,rejected',
            'tasks_form' => 'required|array|min:1',
            'tasks_form.*.project_id' => 'nullable|exists:projects,id',
            'tasks_form.*.title' => 'required|string|max:255',
            'tasks_form.*.description' => 'required|string',
            'tasks_form.*.amount' => 'nullable|numeric|min:0',
            'tasks_form.*.due_date' => 'nullable|date',
            'tasks_form.*.target_count' => 'required|integer|min:1',
        ]);

        if ($this->task_id) {
            Task::where('id', $this->task_id)->update([
                'project_id' => $this->tasks_form[0]['project_id'] ?: null,
                'title' => $this->tasks_form[0]['title'],
                'description' => $this->tasks_form[0]['description'],
                'amount' => $this->tasks_form[0]['amount'] ?: null,
                'due_date' => $this->tasks_form[0]['due_date'] ?: null,
                'target_count' => $this->tasks_form[0]['target_count'],
                'status' => $this->status,
            ]);
            session()->flash('message', 'Task Updated Successfully.');
        }

        $this->closeModal();
    }
    
    public function openVerifyModal($taskId, $submissionId)
    {
        $this->verify_task = Task::with('submissions')->findOrFail($taskId);
        $this->active_submission_id = $submissionId;
        $this->admin_feedback = '';
        $this->verify_action = 'approve';
        $this->isVerifyModalOpen = true;
    }
    
    public function processVerification()
    {
        $this->validate([
            'verify_action' => 'required|in:approve,reject',
            'admin_feedback' => 'nullable|string',
        ]);
        
        $submission = TaskSubmission::findOrFail($this->active_submission_id);
        $status = $this->verify_action === 'approve' ? 'approved' : 'rejected';
        
        $submission->update([
            'status' => $status,
            'admin_feedback' => $this->admin_feedback,
        ]);

        $task = $this->verify_task->fresh();

        if ($task->isComplete()) {
            $task->update(['status' => 'approved']);
            if ($task->amount > 0) {
                // Check if earning already exists
                $earningExists = Earning::where('user_id', $task->user_id)
                    ->where('project_id', $task->project_id)
                    ->where('description', 'like', 'Completed Task: ' . $task->title . '%')
                    ->exists();

                if (!$earningExists) {
                    Earning::create([
                        'user_id' => $task->user_id,
                        'project_id' => $task->project_id,
                        'amount' => $task->amount,
                        'date' => now(),
                        'description' => 'Completed Task: ' . $task->title . ' (' . $task->target_count . ' deliverables)',
                        'status' => 'pending', 
                    ]);
                    session()->flash('message', 'Deliverable Approved! Task reached target and Earning of '. number_format($task->amount, 2) .' was automatically generated.');
                } else {
                    session()->flash('message', 'Deliverable Approved! Task is fully complete.');
                }
            } else {
                session()->flash('message', 'Deliverable Approved! Task is now fully complete.');
            }
        } else {
            if ($task->pendingCount() == 0) {
                $task->update(['status' => 'assigned']); 
            }
            session()->flash('message', 'Deliverable ' . ucfirst($status) . '. Progress updated.');
        }

        $this->closeModal();
    }

    public function undoSubmission($submissionId)
    {
        $submission = TaskSubmission::findOrFail($submissionId);
        $task = Task::findOrFail($submission->task_id);
        
        $wasComplete = $task->isComplete();
        
        // Revert submission to pending
        $submission->update([
            'status' => 'pending',
            'admin_feedback' => null,
        ]);

        $task = $task->fresh();
        
        if ($wasComplete && !$task->isComplete()) {
            $task->update(['status' => 'submitted']);
            
            // Delete any generated earning if it hasn't been cleared/paid yet
            if ($task->amount > 0) {
                Earning::where('user_id', $task->user_id)
                    ->where('project_id', $task->project_id)
                    ->where('description', 'like', 'Completed Task: ' . $task->title . '%')
                    ->where('status', 'pending')
                    ->delete();
            }
            session()->flash('message', 'Undo successful. Task reverted from complete, generated earning removed.');
        } else {
            if ($task->status !== 'submitted') {
                $task->update(['status' => 'submitted']);
            }
            session()->flash('message', 'Undo successful. Submission is now pending again.');
        }
    }

    public function delete($id)
    {
        Task::find($id)->delete();
        session()->flash('message', 'Task Deleted Successfully.');
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
        $this->isVerifyModalOpen = false;
        $this->task_id = null;
        $this->status = 'assigned';
        $this->tasks_form = [];
        $this->verify_task = null;
        $this->active_submission_id = null;
        $this->verify_action = '';
        $this->admin_feedback = '';
    }
}

