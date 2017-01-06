<?php

namespace Akku\Models;

use Illuminate\Database\Eloquent\Model;

class Trigger extends Model
{
    public $timestamps = true;

    protected $fillable = [
        'rule_id',
        'thing_id',
        'comparison_type',
        'value'
    ];
}
