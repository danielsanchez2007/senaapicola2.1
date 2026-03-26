<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Hive extends Model
{
    protected $fillable = [
        'name',
        'apiary_id',
    ];

    public function apiary()
    {
        return $this->belongsTo(Apiary::class);
    }
}
