<?php

namespace Akku\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function modules()
    {
        return $this->hasMany('Akku\Models\Module', 'user_id');
    }

    public function assignedModules()
    {
        return $this->belongsToMany('Akku\Models\Module')->withPivot('module_id', 'user_id');
    }
}
