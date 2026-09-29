<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Lead extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'client_id',
        'assigned_to',
        'user_id',       // employee who submitted the lead
        'status',        // new, contacted, qualified, lost, matured (string after migration)
        'work_type',     // Calling, Software Sales, Design, etc.
        'estimated_value',
        'source',
        'notes',
    ];

    protected $casts = [
        'estimated_value' => 'decimal:2',
    ];

    // ── Relationships ──────────────────────────────────────────────────────

    /** Client associated with this lead. */
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    /** Admin/manager assigned to this lead. */
    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    /** Employee who originally submitted the lead. */
    public function submittedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /** Project created from this lead (if matured). */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }
}
