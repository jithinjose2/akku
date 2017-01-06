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
        if(!empty($data['key']) && !empty($data['pin'])) {
            $module = Module::where('key', $data['key'])->where('pin', $data['pin'])->first();
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
        if(!empty($data['thing_id'])) {
            $thing = Thing::find($data['thing_id']);
            if($thing) {
                SensorRepository::addNewValue($thing, $data['value'], $this->server);
            }
        }
    }

    public function action_change_switch_status($id, $data)
    {
        if(!empty($data['thing_id']))
        {
            $switch = Thing::find($data['thing_id']);
            if($switch) {
                SensorRepository::changeSwitchStatus($switch, $data['value'], $this->server);
            }
        }
    }


    
}