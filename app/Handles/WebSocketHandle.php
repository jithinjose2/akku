<?php

namespace Akku\Handles;

use Module, Thing;
use Akku\Repositories\SensorRepository;
use Akku\Repositories\ModuleRepository;

class WebSocketHandle
{
    
    public $server;

    public function starting()
    {
        Module::where('status', 1)->update(['status' => 0]);
    }
    
    public function onConnect($data)
    {
        if(!empty($data['key'])) {
            $module = Module::where('key', $data['key'])->first();
            if($module) {
                $module->status = 1;
                $module->save();
                $data = ModuleRepository::getInitialData($module, $this->server);
                $data['key'] = $module->key;
                return $data;
            }
        }
        return false;
    }
    
    public function onDisconnect($id)
    {
        if(!empty($id)) {
            $module = Module::where('id', $id)->first();
            if($module) {
                $module->status = 0;
                $module->save();
                return $module->id;
            }
        }
        return false;
    }
    
    public function onReceive($id, $data)
    {
        dd("Data receive : $id", $data);
        $this->server->sendData($id,'action',$data);
    }

    // Action handlers
    public  function action_update_data($id, $data)
    {
        if(!empty($data['thing_key'])) {
            $thing = Thing::where('key', $data['thing_key'])->first();
            if($thing) {
                SensorRepository::addNewValue($thing, $data['value'], $this->server);
            }
        }
    }

    public function action_change_switch_status($id, $data)
    {
        if(!empty($data['switch_key']))
        {
            $switch = Thing::where('key', $data['switch_key'])->first();
            if($switch) {
                SensorRepository::changeSwitchStatus($switch, $data['value'], $this->server);
            }
        }
    }

    public function action_change_led_color($id, $data)
    {
        if(!empty($data['led_key']))
        {
            $led = Thing::where('key', $data['led_key'])->first();
            if($led) {
                SensorRepository::changeLedColor($led, $data['value'], $this->server);
            }
        }
    }

    
}