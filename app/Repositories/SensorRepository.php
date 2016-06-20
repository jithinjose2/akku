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
        if($thing->key == 'LED01') {
            $value_d->value_str = $value;
        } else {
            $value_d->value = $value;
        }
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
        } elseif($thing->key == 'TEMP01') {
            $server->sendData('MODULE04', 'temperature_update', ['value' => $value]);
            $server->sendData('MODULE05', 'temperature_update', ['value' => $value]);
        } elseif($thing->key == 'HUMID01') {
            $server->sendData('MODULE04', 'humidity_update', ['value' => $value]);
            $server->sendData('MODULE05', 'humidity_update', ['value' => $value]);
        } elseif($thing->key == 'LED01') {
            $server->sendData('MODULE04', 'led_color_update', ['value' => $value]);
            $server->sendData('MODULE05', 'led_color_update', ['value' => $value]);
        } elseif($thing->key == 'SWITCHLED01') {
            $server->sendData('MODULE04', 'switch_led_update', ['value' => $value]);
            $server->sendData('MODULE05', 'switch_led_update', ['value' => $value]);
        } elseif($thing->key == 'SWITCHLCD02') {
            $server->sendData('MODULE04', 'switch_lcd_update', ['value' => $value]);
            $server->sendData('MODULE05', 'switch_lcd_update', ['value' => $value]);
        } elseif($thing->key == 'SWITCHLIGHT01') {
            $server->sendData('MODULE04', 'switch_light_update', ['value' => $value]);
            $server->sendData('MODULE05', 'switch_light_update', ['value' => $value]);
        }

    }

    static function changeSwitchStatus($switch, $value, $server)
    {
        // Change switch status request received, just frwd it to the Relay module
        if($switch->key == 'MOTOR01') {
            $server->sendData('MODULE02', 'update_switch_status', ['value' => $value, 'msg' => 'Manual override']);
        } elseif($switch->key == 'SWITCHLED01') {
            $server->sendData('MODULE03', 'update_led_status', ['value' => $value, 'msg' => 'Manual override']);
        } elseif($switch->key == 'SWITCHLCD02') {
            $server->sendData('MODULE03', 'update_lcd_status', ['value' => $value, 'msg' => 'Manual override']);
        } elseif($switch->key == 'SWITCHLIGHT01') {
            $server->sendData('MODULE03', 'update_light_status', ['value' => $value, 'msg' => 'Manual override']);
        }
    }

    static function changeLedColor($led, $value, $server)
    {
        // Led color change request received frwd it to mirror module.
        $server->sendData('MODULE03', 'update_led_color', ['value' => $value, 'msg' => 'Change color request']);
    }

}