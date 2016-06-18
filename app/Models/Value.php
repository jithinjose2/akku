<?php

namespace Akku\Models;

use Illuminate\Database\Eloquent\Model;

class Value extends Model
{
    public function thing()
    {
        return $this->belongsTo('Akku\Models\Thing', 'thing_id');
    }
}
