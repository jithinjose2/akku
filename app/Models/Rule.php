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
}
