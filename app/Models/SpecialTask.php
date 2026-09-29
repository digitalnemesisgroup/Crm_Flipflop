<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
class SpecialTask extends Model {
    use HasFactory;
    protected $fillable = ['form_template_id', 'user_id', 'title', 'description', 'target_count', 'due_date', 'status', 'amount'];
    protected $casts = ['due_date' => 'date', 'amount' => 'decimal:2'];
    public function formTemplate(): BelongsTo {
        return $this->belongsTo(FormTemplate::class);
    }
    public function user(): BelongsTo {
        return $this->belongsTo(User::class);
    }
    public function submissions(): HasMany {
        return $this->hasMany(SpecialTaskSubmission::class);
    }
    public function isComplete(): bool {
        return $this->approvedCount() >= $this->target_count;
    }
    public function approvedCount(): int {
        return $this->submissions()->where('status', 'approved')->count();
    }
    public function pendingCount(): int {
        return $this->submissions()->where('status', 'pending')->count();
    }
}
