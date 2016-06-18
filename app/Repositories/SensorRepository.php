<?php
namespace Akku\Repositories;

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
        $value_d->save();

        // send value to the listening websockets
        if($thing->key == 'WATERLEVEL01')  {
            $server->sendData('MODULE02', 'water_level_update', ['value' => $value]);
            $server->sendData('MODULE04', 'water_level_update', ['value' => $value]);
            $server->sendData('MODULE05', 'water_level_update', ['value' => $value]);
        } elseif($thing->key == 'IRMOTION01') {
            $server->sendData('MODULE04', 'motion_detected', ['value' => $value]);
        } elseif($thing->key == 'MOTOR01') {
            $server->sendData('MODULE04', 'motor_switch_status', ['value' => $value]);
            $server->sendData('MODULE05', 'motor_switch_status', ['value' => $value]);
        }

    }

    static function changeSwitchStatus($switch, $value, $server)
    {
        $server->sendData('MODULE02', 'update_switch_status', ['value' => $value, 'msg' => 'Manual override']);
        $server->sendData('MODULE05', 'water_level_update', ['value' => rand(1,100)]);
    }
}