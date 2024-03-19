<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function owner(){
        return $this->belongsTo('App\Models\User', 'owner_id');
    }

    public function boards(){
        return $this->hasMany('App\Models\Board', 'project_id');
    }

    public function members() {
        return $this->belongsToMany(User::class, 'project_member');
    }
}
