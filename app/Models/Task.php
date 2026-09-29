<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Task extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'project_id',
        'title',
        'description',
        'amount',
        'due_date',
        'target_count',   // e.g. 50 posts total required
        'status',
        'submission_notes',
        'submission_links',
        'admin_feedback',
    ];

    protected $casts = [
        'due_date'         => 'date',
        'amount'           => 'decimal:2',
        'submission_links' => 'array',
        'target_count'     => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    /** All daily submissions by the employee for this task. */
    public function submissions(): HasMany
    {
        return $this->hasMany(TaskSubmission::class);
    }

    /** Count of approved submissions = deliverables completed. */
    public function approvedCount(): int
    {
        return $this->submissions()->where('status', 'approved')->count();
    }

    /** Count of pending submissions awaiting admin review. */
    public function pendingCount(): int
    {
        return $this->submissions()->where('status', 'pending')->count();
    }

    /** Progress percentage toward target. */
    public function progressPercent(): float
    {
        if (!$this->target_count) return 0;
        return min(100, round(($this->approvedCount() / $this->target_count) * 100, 1));
    }

    /** Whether all deliverables have been approved. */
    public function isComplete(): bool
    {
        return $this->target_count && $this->approvedCount() >= $this->target_count;
    }
}
