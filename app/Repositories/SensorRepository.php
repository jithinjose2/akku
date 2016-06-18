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
        $value = new Value();
        $value->value = $value;
        $value->thing()->associate($thing);
        $value->save();

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

    static  function  changeSwitchStatus($switch, $value, $server)
    {
        $server->sendData('MODULE02', 'update_switch_status', ['value' => $value, 'msg' => 'Manual override']);
    }
}