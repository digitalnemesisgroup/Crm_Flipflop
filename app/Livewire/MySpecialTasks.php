<?php
namespace App\Livewire;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\SpecialTask;
use App\Models\SpecialTaskSubmission;
use Illuminate\Support\Facades\Auth;

class MySpecialTasks extends Component {
    use WithPagination;
    
    public $search = '';
    public $fromDate = '';
    public $toDate = '';

    public function mount()
    {
        $this->fromDate = date('Y-m-d');
        $this->toDate = date('Y-m-d');
    }
    
    // Modal fields
    public $isSubmitModalOpen = false;
    public $activeTask;
    public $formData = [];

    public function updatingSearch() { $this->resetPage(); }
    public function updatingFromDate() { $this->resetPage(); }
    public function updatingToDate() { $this->resetPage(); }
    
    public function render() {
        $startDateExpr = \Illuminate\Support\Facades\DB::raw('DATE(created_at)');
        $endDateExpr   = \Illuminate\Support\Facades\DB::raw('COALESCE(due_date, DATE(created_at))');

        $query = SpecialTask::with(['formTemplate', 'submissions'])
            ->where('user_id', Auth::id());
            
        if ($this->fromDate && $this->toDate) {
            if ($this->fromDate === $this->toDate && $this->fromDate === date('Y-m-d')) {
                // Default view (today): show active special tasks covering today OR unapproved past tasks
                $query->where(function ($q) use ($startDateExpr, $endDateExpr) {
                    $q->where(function($sub) use ($startDateExpr, $endDateExpr) {
                        $sub->where($startDateExpr, '<=', today())
                            ->where($endDateExpr, '>=', today());
                    })
                    ->orWhere(function($sub) use ($endDateExpr) {
                        $sub->where($endDateExpr, '<=', today())
                            ->where('status', '!=', 'approved');
                    });
                });
            } else {
                // Interval filter: special task active period overlaps [fromDate, toDate]
                $query->where(function ($q) use ($startDateExpr, $endDateExpr) {
                    $q->where($startDateExpr, '<=', $this->toDate)
                      ->where($endDateExpr, '>=', $this->fromDate);
                });
            }
        } elseif ($this->fromDate) {
            $query->where($endDateExpr, '>=', $this->fromDate);
        } elseif ($this->toDate) {
            $query->where($startDateExpr, '<=', $this->toDate);
        } else {
            $query->where('status', '!=', 'approved');
        }
            
        $tasks = $query->where('title', 'like', '%' . $this->search . '%')
            ->orderByRaw("CASE status WHEN 'assigned' THEN 1 WHEN 'rejected' THEN 2 WHEN 'submitted' THEN 3 WHEN 'approved' THEN 4 ELSE 5 END")
            ->orderBy($endDateExpr, 'asc')
            ->paginate(10);
            
        return view('livewire.my-special-tasks', [
            'tasks' => $tasks
        ])->layout('layouts.app', ['header' => 'My Special Tasks']);
    }

    public function openSubmitModal($id) {
        $this->activeTask = SpecialTask::with('formTemplate')->findOrFail($id);
        $this->formData = [];
        
        // Initialize form data array with default empty values
        foreach ($this->activeTask->formTemplate->schema as $field) {
            $this->formData[$field['id']] = $field['type'] == 'checkbox' ? [] : '';
        }
        
        $this->isSubmitModalOpen = true;
    }

    public function submitForm() {
        // Build validation rules dynamically
        $rules = [];
        foreach ($this->activeTask->formTemplate->schema as $field) {
            $fieldRules = [];
            if (!empty($field['required'])) {
                $fieldRules[] = 'required';
            } else {
                $fieldRules[] = 'nullable';
            }
            
            if (isset($field['type']) && $field['type'] == 'number') {
                $fieldRules[] = 'numeric';
                if (!empty($field['min_value'])) $fieldRules[] = 'min:' . $field['min_value'];
                if (!empty($field['max_value'])) $fieldRules[] = 'max:' . $field['max_value'];
            }
            
            if (isset($field['type']) && in_array($field['type'], ['text', 'textarea'])) {
                $fieldRules[] = 'string';
                if (!empty($field['min_length'])) $fieldRules[] = 'min:' . $field['min_length'];
                if (!empty($field['max_length'])) $fieldRules[] = 'max:' . $field['max_length'];
                if (!empty($field['only_characters'])) $fieldRules[] = 'regex:/^[a-zA-Z\s]+$/';
                if (!empty($field['only_numbers'])) $fieldRules[] = 'regex:/^[0-9]+$/';
            }
            
            if (!empty($fieldRules)) {
                $rules['formData.'.$field['id']] = implode('|', $fieldRules);
            }
        }
        
        if (!empty($rules)) {
            $messages = [
                'required' => 'This field is required.',
                'min' => 'Value is too small/short.',
                'max' => 'Value is too large/long.',
                'regex' => 'Invalid format. Please check constraints (e.g. only characters or only numbers).',
                'numeric' => 'Must be a valid number.'
            ];
            
            // Custom messages for specific regex rules can be tricky generically, so we use a general regex message
            
            $this->validate($rules, $messages);
        }
        
        SpecialTaskSubmission::create([
            'special_task_id' => $this->activeTask->id,
            'user_id' => Auth::id(),
            'data' => $this->formData,
            'status' => 'pending',
        ]);
        
        // Update task status if it was just assigned
        if ($this->activeTask->status === 'assigned') {
            $this->activeTask->update(['status' => 'submitted']);
        }
        
        session()->flash('message', 'Form submitted successfully! (' . ($this->activeTask->approvedCount() + $this->activeTask->pendingCount() + 1) . '/' . $this->activeTask->target_count . ')');
        $this->isSubmitModalOpen = false;
    }
}
