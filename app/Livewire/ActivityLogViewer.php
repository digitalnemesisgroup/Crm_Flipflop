<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\ActivityLog;

class ActivityLogViewer extends Component
{
    use WithPagination;

    public string $search = '';
    public string $dateFilter = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = ActivityLog::with('user');
        
        // Scope to current user if not admin/superadmin
        if (!auth()->user()->hasAnyRole(['SUPER ADMIN', 'ADMIN'])) {
            $query->where('user_id', auth()->id());
        }

        $logs = $query->when($this->search, fn($q) =>
                $q->where(function($subQ) {
                    $subQ->where('action', 'like', "%{$this->search}%")
                         ->orWhere('new_value', 'like', "%{$this->search}%")
                         ->orWhereHas('user', fn($u) => $u->where('name', 'like', "%{$this->search}%"));
                })
            )
            ->when($this->dateFilter, fn($q) =>
                $q->whereDate('created_at', $this->dateFilter)
            )
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('livewire.activity-log-viewer', [
            'logs' => $logs,
        ])->layout('layouts.app', ['header' => 'Activity Log']);
    }
}
