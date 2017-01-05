<?php
/**
 * Created by PhpStorm.
 * User: jithin
 * Date: 2/1/17
 * Time: 12:01 PM
 */

namespace Akku\Repositories;


use Akku\Models\Rule;
use Akku\Models\Trigger;

class RuleRepository
{
    function addNewValue($thing, $value, $server)
    {
        $triggers = Trigger::where('thing_id', $thing->id);

        foreach ($triggers as $trigger) {
            // check if $value matches condition according to trigger,
            // If yes, based on action turn switch on/off

        }
    }
}