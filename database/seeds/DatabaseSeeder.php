<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        DB::table('users')->truncate();
        DB::table('modules')->truncate();
        DB::table('things')->truncate();
        DB::table('values')->truncate();
        DB::table('settings')->truncate();

        // Create user
        $user = new User();
        $user->name = "Jithin";
        $user->email = "jithinjose2@gmail.com";
        $user->password = Hash::make("password");
        $user->save();

        // Create Sensor Module
        $module = $this->createModule($user, 'MODULE01', 'Bedroom Module', 0);

        $this->createThing($module, 'SWITCH01', 1, 'Switch 1', 0);
        $this->createThing($module, 'SWITCH02', 1, 'Switch 2', 0);
        $this->createThing($module, 'SWITCH03', 1, 'Switch 3', 0);
        $this->createThing($module, 'SWITCH04', 1, 'Switch 4', 0);
        $this->createThing($module, 'TEMPSENSOR1', 2, 'Temperature Sensor', 0);
        $this->createThing($module, 'HUMID1', 2, 'Humidity Sensor', 0);
        $this->createThing($module, 'POWERUSAGESENSNOR', 2, 'Power sensor', 0);
    }

    public function createSetting($key, $value)
    {
        $setting = new Setting();
        $setting->key = $key;
        $setting->value = $value;
        $setting->save();
        return $setting;
    }

    public function createThing($module, $key, $type, $name, $default_value = '', $is_string = false)
    {
        $thing = new Thing();
        $thing->module()->associate($module);
        $thing->key = $key;
        $thing->type = $type;
        $thing->name = $name;
        $thing->save();
        if($default_value !== '') {
            $value = new Value();
            $value->thing()->associate($thing);
            if($is_string){
                $value->value_str = $default_value;
            } else {
                $value->value = $default_value;
            }
            $value->save();
        }
        return $thing;
    }

    public function createModule($user, $key, $name, $status)
    {
        $module = new Module();
        //$module->user()->associate($user);
        $module->key = $key;
        $module->name = $name;
        $module->status = $status;
        $module->save();
        return $module;
    }
}
