<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaskAssignment extends Model
{
    protected $fillable = [
        'title',
        'description',
        'assigned_to',
        'assigned_by',
        'status',
        'due_date',
        'completed_at',
        'evidence_path',
        'admin_notes',
    ];

    protected $casts = [
        'due_date' => 'date',
        'completed_at' => 'datetime',
    ];

    public function assignedUser()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function assignedByUser()
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }
}
