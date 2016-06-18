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

        // Create user
        $user = new User();
        $user->name = "Jithin";
        $user->email = "jithinjose2@gmail.com";
        $user->password = Hash::make("password");
        $user->save();

        // Create Sensor Module
        $module = new Module();
        $module->user()->associate($user);
        $module->key = "MODULE01";
        $module->name = "WaterTank Sensor Module";
        $module->status = 0;
        $module->save();
        $thing = new Thing();
        $thing->module()->associate($module);
        $thing->key = 'WATERLEVEL01';
        $thing->type = 4;
        $thing->name = "Water level sensor";
        $thing->save();

        //Relay Module
        $module = new Module();
        $module->user()->associate($user);
        $module->key = "MODULE02";
        $module->name = "Electric Pumb relay control";
        $module->status = 0;
        $module->save();
        $thing = new Thing();
        $thing->module()->associate($module);
        $thing->key = 'MOTOR01';
        $thing->type = 1;
        $thing->name = "Water pumb switch";
        $thing->save();
        $thing = new Thing();
        $thing->module()->associate($module);
        $thing->key = 'LEDBAR01';
        $thing->type = 3;
        $thing->name = "Water level meter";
        $thing->save();

        // Mirror Sensor Module
        $module = new Module();
        $module->user()->associate($user);
        $module->key = "MODULE03";
        $module->name = "Mirror sensor module";
        $module->status = 0;
        $module->save();
        $thing = new Thing();
        $thing->module()->associate($module);
        $thing->key = 'IRMOTION01';
        $thing->type = 2;
        $thing->name = "IR motion sensor for Mirror";
        $thing->save();

        // Mirror display module.
        $module = new Module();
        $module->user()->associate($user);
        $module->key = "MODULE04";
        $module->name = "Mirror display module";
        $module->status = 0;
        $module->save();

        // Admin panel module
        $module = new Module();
        $module->user()->associate($user);
        $module->key = "MODULE05";
        $module->name = "Admin panel module";
        $module->status = 0;
        $module->save();
    }
}
