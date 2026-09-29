<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Client extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'company',
        'company_name',   // added via expand migration
        'gst_number',     // added via expand migration
        'address',
        'status',
        'website',
        'notes',
    ];

    // ── Relationships ──────────────────────────────────────────────────────

    public function projects(): HasMany
    {
        return $this->hasMany(Project::class);
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    public function leads(): HasMany
    {
        return $this->hasMany(Lead::class);
    }
}
