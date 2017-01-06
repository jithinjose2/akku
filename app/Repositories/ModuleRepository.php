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
        foreach ($module->things as $thing) {
            $data[$thing->key] = $thing->latestValue ? $thing->latestValue->value : 0;
        }
        return $data;
    }
}