<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
class FormTemplate extends Model {
    use HasFactory;
    protected $fillable = ['name', 'description', 'schema'];
    protected $casts = ['schema' => 'array'];
    public function specialTasks(): HasMany {
        return $this->hasMany(SpecialTask::class);
    }
}
