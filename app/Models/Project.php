<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'client_id',
        'lead_id',
        'status',
        'start_date',
        'end_date',
        'total_budget',
        'employee_payout_percentage',
    ];

    protected $casts = [
        'start_date'                => 'date',
        'end_date'                  => 'date',
        'total_budget'              => 'decimal:2',
        'employee_payout_percentage' => 'decimal:2',
    ];

    // ── Relationships ──────────────────────────────────────────────────────

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function lead(): BelongsTo
    {
        return $this->belongsTo(Lead::class);
    }

    /** Employees & freelancers assigned to this project. */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'project_user')
                    ->withPivot('role_in_project')
                    ->withTimestamps();
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    public function earnings(): HasMany
    {
        return $this->hasMany(Earning::class);
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(Document::class);
    }

    // ── Helpers ───────────────────────────────────────────────────────────

    /** Total amount paid by client across all invoices. */
    public function totalClientPaid(): float
    {
        return (float) $this->invoices()->with('payments')
            ->get()
            ->sum(fn($invoice) => $invoice->amount_paid);
    }

    /** Total earnings allocated to employees for this project. */
    public function totalEmployeeEarnings(): float
    {
        return (float) $this->earnings()->whereIn('status', ['cleared', 'paid'])->sum('amount');
    }
}
