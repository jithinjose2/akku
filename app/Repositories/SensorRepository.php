<?php
namespace Akku\Repositories;

use Akku\Models\Trigger;
use Thing, Value;
/**
 * Created by PhpStorm.
 * User: jithinjose
 * Date: 17/6/16
 * Time: 5:01 PM
 */
class SensorRepository
{
    static function addNewValue($thing, $value, $server)
    {
        $value_d = new Value();
        $value_d->value = $value;
        $value_d->thing()->associate($thing);
        if($thing->key == "POWERUSAGESENSNOR") {
            $value_d->value = $value = (abs(590 - $value) / 512) * 5 * 230;
        }
        $value_d->save();

        // send value to the listening
        if($thing->key == 'SWITCH01' || $thing->key == 'SWITCH02' ||
            $thing->key == 'SWITCH03' || $thing->key == 'SWITCH04')  {
            $server->sendData('WEB1', 'switch_status_change', [
                'value' => $value,'thing_id' => $thing->id
            ]);
        } elseif($thing->key == 'TEMPSENSOR1') {
            $server->sendData('WEB1', 'temp_update', ['value' => $value]);
        } elseif($thing->key == 'HUMID1') {
            $server->sendData('WEB1', 'humid_update', ['value' => $value]);
        } elseif($thing->key == 'POWERUSAGESENSNOR') {
            $server->sendData('WEB1', 'power_update', ['value' => $value]);
        }

        self::checkTriggerStatus($thing, $value, $server);

    }

    static function changeSwitchStatus($switch, $value, $server)
    {
        // Change switch status request received, just frwd it to the Relay module
        $server->sendData('MODULE01', 'update_switch_status', [
            'value' => $value,
            'thing_id' => $switch->id,
            'msg' => 'Manual override'
        ]);
    }

    static function checkTriggerStatus($thing, $value, $server)
    {
        $triggers = Trigger::where('thing_id', $thing->id)->get();
        foreach ($triggers as $trigger) {
            $realTrigger = false;
            if($trigger->comparison_type == "=" && $value == $trigger->value) {
                $realTrigger = true;
            } elseif ($trigger->comparison_type == "<" && $value < $trigger->value) {
                $realTrigger = true;
            } elseif ($trigger->comparison_type == ">" && $value > $trigger->value) {
                $realTrigger = true;
            }

            if($realTrigger) {
                $action = $trigger->rule->action;
                if($action->thing->latestValue->value != $action->value) {
                    self::changeSwitchStatus($action->thing, $action->value, $server);
                }
            }
        }

    }

}