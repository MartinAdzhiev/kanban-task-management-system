<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Board extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function project(){
        return $this->belongsTo('App\Models\Project', 'project_id');
    }

    public function columns(){
        return $this->hasMany('App\Models\Column', 'board_id');
    }
}
