<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApiaryMonitoring extends Model
{
    protected $table = 'apiary_monitorings';

    protected $fillable = [
        'date',
        'apiary_id',
        'user_nickname',
        'role',
        'description',
    ];

    public function apiary()
    {
        return $this->belongsTo(Apiary::class);
    }
}
