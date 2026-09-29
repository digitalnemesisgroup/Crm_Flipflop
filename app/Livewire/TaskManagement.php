<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Task;
use App\Models\TaskSubmission;
use App\Models\User;
use App\Models\Project;
use App\Models\Earning;

class TaskManagement extends Component
{
    use WithPagination;

    public $search = '';
    public $isModalOpen = false;
    public $isVerifyModalOpen = false;
    
    // Form fields
    public $task_id;
    public $user_id = '';
    public $status = 'assigned';
    
    // Array of tasks to be created in bulk
    public $tasks_form = [];
    
    // Verify fields
    public $verify_task;
    public $active_submission_id;
    public $admin_feedback = '';
    public $verify_action = ''; // approve or reject
    
    public $fromDate = '';
    public $toDate = '';

    public function mount()
    {
        $this->fromDate = date('Y-m-d');
        $this->toDate = date('Y-m-d');
    }

    public function updatingSearch() { $this->resetPage(); }
    public function updatingFromDate() { $this->resetPage(); }
    public function updatingToDate() { $this->resetPage(); }

    public function render()
    {
        $assignDateExpr = \Illuminate\Support\Facades\DB::raw('COALESCE(due_date, DATE(created_at))');

        $query = Task::select(
                'user_id',
                \Illuminate\Support\Facades\DB::raw('COALESCE(due_date, DATE(created_at)) as assign_date'),
                \Illuminate\Support\Facades\DB::raw('COUNT(*) as tasks_count')
            )
            ->with('user');
            
        if ($this->fromDate && $this->toDate) {
            $query->whereBetween($assignDateExpr, [$this->fromDate, $this->toDate]);
        } elseif ($this->fromDate) {
            $query->where($assignDateExpr, '>=', $this->fromDate);
        } elseif ($this->toDate) {
            $query->where($assignDateExpr, '<=', $this->toDate);
        }

        $query->groupBy('user_id', $assignDateExpr)
            ->orderBy('assign_date', 'desc');

        if ($this->search) {
            $query->whereHas('user', function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%');
            });
        }

        $groupedTasks = $query->paginate(10);
            
        // We need to calculate pending tasks properly.
        // It's easier to map over the paginated items if we need submissions, but for performance
        // we can just use the status="submitted" on tasks. Or we can just calculate it in the view.
        
        $users = User::role(['EMPLOYEE', 'FREELANCER'])->orderBy('name')->get();
        $projects = Project::orderBy('name')->get();

        return view('livewire.task-management', [
            'groupedTasks' => $groupedTasks,
            'users' => $users,
            'projects' => $projects,
        ])->layout('layouts.app', ['header' => 'Work & Task Management']);
    }

    public function openCreateModal()
    {
        $this->resetInputFields();
        $this->addTaskRow();
        $this->isModalOpen = true;
    }
    
    public function addTaskRow()
    {
        $this->tasks_form[] = [
            'project_id' => '',
            'title' => '',
            'description' => '',
            'amount' => '',
            'start_date' => date('Y-m-d'),
            'end_date' => date('Y-m-d'),
            'target_count' => 1, // default to 1 deliverable
        ];
    }
    
    public function removeTaskRow($index)
    {
        unset($this->tasks_form[$index]);
        $this->tasks_form = array_values($this->tasks_form);
    }

    public function edit($id)
    {
        $task = Task::findOrFail($id);
        $this->task_id = $id;
        $this->user_id = $task->user_id;
        $this->status = $task->status;
        
        $this->tasks_form = [
            [
                'project_id' => $task->project_id ?? '',
                'title' => $task->title,
                'description' => $task->description,
                'amount' => $task->amount,
                'start_date' => $task->due_date ? $task->due_date->format('Y-m-d') : null,
                'end_date' => $task->due_date ? $task->due_date->format('Y-m-d') : null,
                'target_count' => $task->target_count ?? 1,
            ]
        ];
        
        $this->isModalOpen = true;
    }

    public function store()
    {
        $this->validate([
            'user_id' => 'required|exists:users,id',
            'status' => 'required|in:assigned,submitted,approved,rejected',
            'tasks_form' => 'required|array|min:1',
            'tasks_form.*.project_id' => 'nullable|exists:projects,id',
            'tasks_form.*.title' => 'required|string|max:255',
            'tasks_form.*.description' => 'required|string',
            'tasks_form.*.amount' => 'nullable|numeric|min:0',
            'tasks_form.*.start_date' => 'required|date',
            'tasks_form.*.end_date' => 'required|date|after_or_equal:tasks_form.*.start_date',
            'tasks_form.*.target_count' => 'required|integer|min:1',
        ], [
            'user_id.required' => 'Please select an assignee from the dropdown at the top of the modal.',
            'user_id.exists' => 'The selected assignee is invalid.',
            'tasks_form.*.title.required' => 'The task title is required.',
            'tasks_form.*.description.required' => 'The task description is required.',
            'tasks_form.*.start_date.required' => 'The start date is required.',
            'tasks_form.*.end_date.required' => 'The end date is required.',
            'tasks_form.*.end_date.after_or_equal' => 'The end date must be on or after the start date.',
            'tasks_form.*.target_count.required' => 'The total deliverables target count is required.',
        ]);

        $tasks_created_count = 0;
        
        if ($this->task_id) {
            // Updating a single existing task
            $task = Task::findOrFail($this->task_id);
            $task_data = $this->tasks_form[0];
            $task->update([
                'user_id' => $this->user_id,
                'project_id' => $task_data['project_id'] ?: null,
                'title' => $task_data['title'],
                'description' => $task_data['description'],
                'amount' => $task_data['amount'] ?: null,
                'due_date' => $task_data['end_date'] ?: null,
                'target_count' => $task_data['target_count'],
                'status' => $this->status,
            ]);
            session()->flash('message', 'Task Updated Successfully.');
        } else {
            // Creating multiple tasks (and possibly looping through days)
            foreach ($this->tasks_form as $task_data) {
                $start = \Carbon\Carbon::parse($task_data['start_date']);
                $end = \Carbon\Carbon::parse($task_data['end_date']);
                
                for ($d = $start->copy(); $d->lte($end); $d->addDay()) {
                    Task::create([
                        'user_id' => $this->user_id,
                        'project_id' => $task_data['project_id'] ?: null,
                        'title' => $task_data['title'],
                        'description' => $task_data['description'],
                        'amount' => $task_data['amount'] ?: null,
                        'due_date' => $d->format('Y-m-d'),
                        'target_count' => $task_data['target_count'],
                        'status' => $this->status,
                    ]);
                    $tasks_created_count++;
                }
            }
            session()->flash('message', $tasks_created_count . ' Task(s) Assigned Successfully.');
        }

        $this->closeModal();
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
        $this->resetInputFields();
    }

    private function resetInputFields()
    {
        $this->user_id = '';
        $this->status = 'assigned';
        $this->tasks_form = [];
    }
}
