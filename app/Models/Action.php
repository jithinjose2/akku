<?php

namespace Akku\Models;

use Illuminate\Database\Eloquent\Model;

class Action extends Model
{
    public $timestamps = true;

    protected $fillable = [
        'rule_id',
        'thing_id',
        'value'
    ];
}
