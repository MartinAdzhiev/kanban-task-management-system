<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function column() {
        return $this->belongsTo('App\Models\Column', 'column_id');
    }

    public function assignedTo() {
        return $this->belongsTo('App\Models\User', 'assigned_to');
    }

}
