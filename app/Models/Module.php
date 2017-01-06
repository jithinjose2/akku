<?php

namespace Akku\Models;

use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    public function user()
    {
        return $this->belongsTo('Akku\Models\User', 'user_id');
    }
    public function users()
    {
        return $this->belongsToMany('Akku\Models\User');
    }

    public  function things()
    {
        return $this->hasMany('Akku\Models\Thing', 'module_id');
    }
}
