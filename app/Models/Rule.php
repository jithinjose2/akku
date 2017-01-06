<?php

namespace Akku\Models;

use Illuminate\Database\Eloquent\Model;

class Rule extends Model
{
    public $timestamps = true;

    protected $fillable = [
        'user_id',
        'name'
    ];

    public  function trigger()
    {
        return $this->hasOne('Akku\Models\Trigger', 'rule_id');
    }

    public  function action()
    {
        return $this->hasOne('Akku\Models\Action', 'rule_id');
    }
}
