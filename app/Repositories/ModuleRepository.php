<?php
/**
 * Created by PhpStorm.
 * User: jithinjose
 * Date: 18/6/16
 * Time: 9:16 AM
 */

namespace Akku\Repositories;

use Thing,Module, Value;


class ModuleRepository
{
    static function getInitialData($module)
    {
        $data = [];
        if($module->key == 'MODULE05') {
            $motor_switch_thing = Thing::where('key', 'MOTOR01')->first();
            $data['light_switch_status'] = $motor_switch_thing->latestValue ? $motor_switch_thing->latestValue->value : 0;
        }
        return $data;
    }
}