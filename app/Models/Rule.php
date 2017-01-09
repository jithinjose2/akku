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
    protected $appends = ['triggermessage'];

    public function trigger()
    {
        return $this->hasOne('Akku\Models\Trigger', 'rule_id');
    }

    public function action()
    {
        return $this->hasOne('Akku\Models\Action', 'rule_id');
    }

    public function getTriggermessageAttribute()
    {
        $msg = "When value of ";
        $msg .= $this->trigger->thing->module->name . ' ' .$this->trigger->thing->name .' is ';
        $msg .= '<span class="label label-info">';
        if ($this->trigger->comparison_type === '=') {
            $msg .= ' equal to ';
        } else {
            if ($this->trigger->comparison_type === '>') {
                $msg .= ' greater than ';
            } else {
                $msg .= 'less than ';
            }
        }
        $msg .= '</span>';

        $msg .= $this->trigger->value . ', then ';

        $msg .= '<span class="label label-info">' . \Akku\Models\Thing::find($this->action->thing_id)->name . '</span>';
        $msg .= ' will get';
        if ($this->action->value === 1) {
            $msg .= ' Turn On';
        } else {
            $msg .= ' Turn Off';
        }
        return $msg;
    }
}
