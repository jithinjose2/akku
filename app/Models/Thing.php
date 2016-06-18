<?php

namespace Akku\Models;

use Illuminate\Database\Eloquent\Model;

class Thing extends Model
{
    public function module()
    {
        return $this->belongsTo('Akku\Models\Module', 'module_id');
    }

    public  function values()
    {
        return $this->hasMany('Akku\Models\Value', 'thing_id');
    }

    public  function latestValue()
    {
        return $this->hasOne('Akku\Models\Value', 'thing_id')->orderBy('created_at','desc')->limit(1);
    }
}
