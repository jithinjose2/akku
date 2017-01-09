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

    public function rule()
    {
        return $this->belongsTo('Akku\Models\Rule', 'rule_id');
    }

    public function thing()
    {
        return $this->belongsTo('Akku\Models\Thing', 'thing_id');
    }
}
