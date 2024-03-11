<?php

namespace App\Models;

use App\Enums\TaskPriority;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'priority' => TaskPriority::class,
    ];

    public function column() {
        return $this->belongsTo('App\Models\Column', 'column_id');
    }

    public function assignedTo() {
        return $this->belongsTo('App\Models\User', 'assigned_to');
    }

}
