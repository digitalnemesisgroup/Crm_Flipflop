<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Task;
use App\Models\TaskSubmission;
use Illuminate\Support\Facades\Auth;

class MyTasks extends Component
{
    use WithPagination;

    public $search = '';

    // Daily Submission Modal
    public bool $isSubmitModalOpen = false;
    public ?int $activeTaskId = null;
    public string $notes = '';
    public array $links = [];           // grouped as [{url:..., label:...}] per entry
    public string $linkUrl = '';
    public string $linkLabel = '';

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
        $startDateExpr = \Illuminate\Support\Facades\DB::raw('DATE(created_at)');
        $endDateExpr   = \Illuminate\Support\Facades\DB::raw('COALESCE(due_date, DATE(created_at))');

        $query = Task::with(['project', 'submissions'])
            ->where('user_id', Auth::id());

        if ($this->fromDate && $this->toDate) {
            if ($this->fromDate === $this->toDate && $this->fromDate === date('Y-m-d')) {
                // Default view (today): show active tasks covering today OR unapproved past tasks
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
                // Interval filter: task active period overlaps [fromDate, toDate]
                $query->where(function ($q) use ($startDateExpr, $endDateExpr) {
                    $q->where($startDateExpr, '<=', $this->toDate)
                      ->where($endDateExpr, '>=', $this->fromDate);
                });
            }
        } elseif ($this->fromDate) {
            $query->where($endDateExpr, '>=', $this->fromDate);
        } elseif ($this->toDate) {
            $query->where($startDateExpr, '<=', $this->toDate);
        }

        $tasks = $query->where(function ($q) {
                $q->where('title', 'like', '%' . $this->search . '%')
                  ->orWhere('description', 'like', '%' . $this->search . '%');
            })
            ->orderByRaw("CASE status WHEN 'assigned' THEN 1 WHEN 'rejected' THEN 2 WHEN 'submitted' THEN 3 WHEN 'approved' THEN 4 ELSE 5 END")
            ->orderBy('due_date', 'asc')
            ->paginate(10);

        return view('livewire.my-tasks', ['tasks' => $tasks])
            ->layout('layouts.app', ['header' => 'My Tasks']);
    }

    // ── Open submission modal ──────────────────────────────────────────────

    public function openSubmitModal(int $id): void
    {
        $this->activeTaskId = $id;
        $this->notes = '';
        $this->links = [];
        $this->linkUrl = '';
        $this->linkLabel = '';
        $this->isSubmitModalOpen = true;
    }

    // ── Add a link to the current submission group ─────────────────────────
    // Each link in one group (demo, Instagram, FB) counts as 1 deliverable.

    public function addLink(): void
    {
        $this->validate([
            'linkUrl'   => 'required|url',
            'linkLabel' => 'nullable|string|max:60',
        ]);

        $this->links[] = [
            'url'   => $this->linkUrl,
            'label' => $this->linkLabel ?: 'Link ' . (count($this->links) + 1),
        ];

        $this->linkUrl   = '';
        $this->linkLabel = '';
    }

    public function removeLink(int $index): void
    {
        unset($this->links[$index]);
        $this->links = array_values($this->links);
    }

    // ── Submit the grouped links as ONE deliverable ─────────────────────────

    public function submitWork(): void
    {
        $this->validate([
            'notes' => 'nullable|string|max:500',
        ]);

        if (empty($this->links)) {
            $this->addError('linkUrl', 'Please add at least one link before submitting.');
            return;
        }

        $task = Task::where('user_id', Auth::id())->findOrFail($this->activeTaskId);

        // Check if already reached the target
        $approvedAndPending = $task->approvedCount() + $task->pendingCount();
        if ($approvedAndPending >= $task->target_count) {
            session()->flash('error', 'You have already submitted all required deliverables. Please wait for admin review.');
            $this->closeModal();
            return;
        }

        TaskSubmission::create([
            'task_id' => $task->id,
            'user_id' => Auth::id(),
            'links'   => $this->links,   // all links in this group = 1 deliverable
            'notes'   => $this->notes,
            'status'  => 'pending',
        ]);

        // Update task status to submitted (if not already completed)
        if ($task->status === 'assigned') {
            $task->update(['status' => 'submitted']);
        }

        session()->flash('message', 'Daily submission received! Waiting for admin review.');
        $this->closeModal();
    }

    public function closeModal(): void
    {
        $this->isSubmitModalOpen = false;
        $this->activeTaskId      = null;
        $this->notes             = '';
        $this->links             = [];
        $this->linkUrl           = '';
        $this->linkLabel         = '';
    }
}
