<?php
namespace App\Livewire;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\SpecialTask;
use App\Models\SpecialTaskSubmission;
use App\Models\FormTemplate;
use App\Models\User;
use App\Models\Earning;

class SpecialTaskManagement extends Component {
    use WithPagination;
    
    public $search = '';
    public $isAssignModalOpen = false;
    public $isVerifyModalOpen = false;
    
    public $user_id = '';
    public $tasks_form = [];
    
    public $activeTask;
    public $activeSubmissions = [];
    
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

    public function render() {
        $startDateExpr = \Illuminate\Support\Facades\DB::raw('DATE(created_at)');
        $endDateExpr   = \Illuminate\Support\Facades\DB::raw('COALESCE(due_date, DATE(created_at))');

        $query = SpecialTask::with(['user', 'formTemplate', 'submissions'])
            ->where(function($q) {
                $q->whereHas('user', function($u) {
                    $u->where('name', 'like', '%' . $this->search . '%');
                })->orWhere('title', 'like', '%' . $this->search . '%');
            });
            
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
            
        $tasks = $query->orderByRaw("CASE status WHEN 'submitted' THEN 1 WHEN 'assigned' THEN 2 WHEN 'rejected' THEN 3 WHEN 'approved' THEN 4 ELSE 5 END")
            ->orderBy($endDateExpr, 'desc')
            ->paginate(10);
            
        $users = User::whereHas('roles', function ($q) {
            $q->whereIn('name', ['EMPLOYEE', 'FREELANCER', 'MANAGER', 'ACCOUNT TEAM', 'Employee', 'Freelancer', 'Manager']);
        })->orderBy('name')->get();

        return view('livewire.special-task-management', [
            'tasks' => $tasks,
            'users' => $users,
            'templates' => FormTemplate::orderBy('name')->get(),
        ])->layout('layouts.app', ['header' => 'Assign Special Tasks']);
    }

    public function openAssignModal() {
        $this->resetFields();
        $this->addTaskRow();
        $this->isAssignModalOpen = true;
    }
    
    public function addTaskRow() {
        $this->tasks_form[] = [
            'form_template_id' => '',
            'title' => '',
            'target_count' => 1,
            'amount' => '',
            'start_date' => date('Y-m-d'),
            'end_date' => date('Y-m-d'),
        ];
    }
    
    public function removeTaskRow($index) {
        unset($this->tasks_form[$index]);
        $this->tasks_form = array_values($this->tasks_form);
    }

    public function store() {
        $this->validate([
            'user_id' => 'required|exists:users,id',
            'tasks_form' => 'required|array|min:1',
            'tasks_form.*.form_template_id' => 'required|exists:form_templates,id',
            'tasks_form.*.title' => 'required|string|max:255',
            'tasks_form.*.target_count' => 'required|integer|min:1',
            'tasks_form.*.amount' => 'nullable|numeric|min:0',
            'tasks_form.*.start_date' => 'required|date',
            'tasks_form.*.end_date' => 'required|date|after_or_equal:tasks_form.*.start_date',
        ]);
        
        $tasks_created_count = 0;

        foreach ($this->tasks_form as $task_data) {
            $start = \Carbon\Carbon::parse($task_data['start_date']);
            $end = \Carbon\Carbon::parse($task_data['end_date']);

            for ($d = $start->copy(); $d->lte($end); $d->addDay()) {
                SpecialTask::create([
                    'user_id' => $this->user_id,
                    'form_template_id' => $task_data['form_template_id'],
                    'title' => $task_data['title'],
                    'description' => null,
                    'target_count' => $task_data['target_count'],
                    'due_date' => $d->format('Y-m-d'),
                    'amount' => $task_data['amount'] ?: null,
                    'status' => 'assigned',
                ]);
                $tasks_created_count++;
            }
        }
        
        session()->flash('message', $tasks_created_count . ' Special Task(s) Assigned Successfully.');
        $this->isAssignModalOpen = false;
    }
    
    public function openVerifyModal($taskId) {
        $this->activeTask = SpecialTask::with(['submissions', 'formTemplate'])->findOrFail($taskId);
        $this->activeSubmissions = $this->activeTask->submissions()->orderBy('created_at', 'asc')->get();
        $this->isVerifyModalOpen = true;
    }
    
    public function updateSubmissionStatus($submissionId, $status) {
        $submission = SpecialTaskSubmission::findOrFail($submissionId);
        $submission->update(['status' => $status]);
        
        // Check if task is now complete
        $task = $submission->specialTask;
        if ($task->isComplete()) {
            $task->update(['status' => 'approved']);
            // Generate earning
            if ($task->amount > 0) {
                $earningExists = Earning::where('user_id', $task->user_id)
                    ->where('description', 'like', '%Special Task: ' . $task->title . '%')->exists();
                if (!$earningExists) {
                    Earning::create([
                        'user_id' => $task->user_id,
                        'project_id' => null,
                        'amount' => $task->amount,
                        'date' => now(),
                        'description' => 'Completed Special Task: ' . $task->title,
                        'status' => 'pending', 
                    ]);
                }
            }
        } else {
            // Task is not complete. If it was approved before, we might need to revert it? 
            // We just set to assigned if they need more.
            if ($task->status == 'approved') {
                $task->update(['status' => 'assigned']);
            }
        }
        
        $this->activeSubmissions = $task->submissions()->orderBy('created_at', 'asc')->get();
    }
    
    public function delete($id) {
        SpecialTask::find($id)->delete();
        session()->flash('message', 'Special Task deleted.');
    }

    public function resetFields() {
        $this->user_id = '';
        $this->tasks_form = [];
    }
}
