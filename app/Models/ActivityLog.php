<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $fillable = [
        'user_id',
        'action',
        'old_value',
        'new_value',
        'ip_address',
        'device',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
