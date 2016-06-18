<?php
/**
 * Created by PhpStorm.
 * User: jithinjose
 * Date: 18/6/16
 * Time: 9:16 AM
 */

namespace Akku\Repositories;

use Thing,Module, Value, Setting;


class ModuleRepository
{
    static function getInitialData($module, $server)
    {
        $data = [];
        if($module->key == 'MODULE05') {
            $motor_switch_thing = Thing::where('key', 'MOTOR01')->first();
            $data['motor_switch_status'] = $motor_switch_thing->latestValue ? $motor_switch_thing->latestValue->value : 0;
        } elseif ($module->key == 'MODULE02') {
            // attach settings
            $data['max_motor_active_time'] = Setting::where('key', 'max_motor_active_time')->first()->value;
            $data['max_level'] = Setting::where('key', 'max_level')->first()->value;
            $data['min_level'] = Setting::where('key', 'min_level')->first()->value;
            $data['trigger_percent'] = Setting::where('key', 'trigger_percent')->first()->value;
            $data['cutoff_percent'] = Setting::where('key', 'cutoff_percent')->first()->value;

            // motor will be disabled when relay module starts
            $thing = Thing::where('key', 'MOTOR01')->first();
            SensorRepository::addNewValue($thing, 0, $server);
        }
        return $data;
    }
}