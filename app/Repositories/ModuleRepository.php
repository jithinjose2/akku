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
        if($module->key == 'MODULE05' || $module->key == 'MODULE04') {
            $data['motor_switch_status'] = Thing::where('key', 'MOTOR01')->first()->latestValue->value ? 1 : 0;
            $data['led_switch_status'] = Thing::where('key', 'SWITCHLED01')->first()->latestValue->value ? 1 : 0;
            $data['lcd_switch_status'] = Thing::where('key', 'SWITCHLCD02')->first()->latestValue->value ? 1 : 0;
            $data['light_switch_status'] = Thing::where('key', 'SWITCHLIGHT01')->first()->latestValue->value ? 1 : 0;
            $data['led_color'] = Thing::where('key', 'LED01')->first()->latestValue->value_str;
            $data['water_level'] = intval(Thing::where('key', 'WATERLEVEL01')->first()->latestValue->value);
            $data['temperature'] = intval(Thing::where('key', 'TEMP01')->first()->latestValue->value);
            $data['humidity'] = intval(Thing::where('key', 'HUMID01')->first()->latestValue->value);

            $settings = Setting::all();
            $sets = [];
            foreach($settings as $setting){
                $sets[$setting->key] = $setting->value;
            }
            $data['settings'] = $sets;

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
        } elseif ($module->key == 'MODULE03') {
            $data['led_color'] = Thing::where('key', 'LED01')->first()->latestValue->value_str;
        }
        return $data;
    }
}