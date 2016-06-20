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
        $module = $this->createModule($user, 'MODULE01', 'WaterTank Sensor Module', 0);
        $this->createThing($module, 'WATERLEVEL01', 4, 'Water level sensor', 0);

        //Relay Module
        $module = $this->createModule($user, 'MODULE02', 'Electric Pumb relay control', 0);
        $this->createThing($module, 'MOTOR01', 1, 'Water pumb switch', 0);
        $this->createThing($module, 'LEDBAR01', 3, 'Water level meter', 0);

        // Mirror Sensor Module
        $module = $this->createModule($user, 'MODULE03', 'Mirror sensor module', 0);
        $this->createThing($module, 'IRMOTION01', 2, 'IR motion sensor for Mirror', 0);
        $this->createThing($module, 'LED01', 3, 'Mirror LED Color', '0069FF', true);
        $this->createThing($module, 'TEMP01', 4, 'Temperature sensor', 0);
        $this->createThing($module, 'HUMID01', 4, 'Humidity sensor', 0);
        $this->createThing($module, 'SWITCHLIGHT01', 1, 'Main Hall Light', 0);
        $this->createThing($module, 'SWITCHLED01', 1, 'Mirror LED', 0);
        $this->createThing($module, 'SWITCHLCD02', 1, 'Screen monitor', 0);


        // Mirror display module.
        $module = $this->createModule($user, 'MODULE04', 'Mirror display module', 0);

        // Admin panel module
        $module = $this->createModule($user, 'MODULE05', 'Admin panel module', 0);


        $this->createSetting('max_motor_active_time', 120);
        $this->createSetting('max_level', 200);
        $this->createSetting('min_level', 1400);
        $this->createSetting('trigger_percent', 10);
        $this->createSetting('cutoff_percent', 90);
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
        $module->user()->associate($user);
        $module->key = $key;
        $module->name = $name;
        $module->status = $status;
        $module->save();
        return $module;
    }
}
