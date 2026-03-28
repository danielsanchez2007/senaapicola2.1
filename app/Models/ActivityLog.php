<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $fillable = [
        'user_id',
        'user_nickname',
        'role_name',
        'action',
        'description',
        'ip_address',
        'logged_at',
    ];

    protected $casts = [
        'logged_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
