<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class SpecialTaskSubmission extends Model {
    use HasFactory;
    protected $fillable = ['special_task_id', 'user_id', 'data', 'status', 'admin_feedback'];
    protected $casts = ['data' => 'array'];
    public function specialTask(): BelongsTo {
        return $this->belongsTo(SpecialTask::class);
    }
    public function user(): BelongsTo {
        return $this->belongsTo(User::class)->withTrashed();
    }
}
