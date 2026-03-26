<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductionMonitoring extends Model
{
    protected $table = 'production_monitorings';

    protected $fillable = [
        'date',
        'apiary_id',
        'quantity',
        'product',
        'action',
    ];

    public function apiary()
    {
        return $this->belongsTo(Apiary::class);
    }
}
