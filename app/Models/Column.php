<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Column extends Model
{
    use HasFactory;

    public $guarded = [];


    public function board(){
        return $this->belongsTo('App\Models\Board', 'board_id');
    }

    public function tasks(){
        return $this->hasMany('App\Models\Task', 'column_id');
    }

}
