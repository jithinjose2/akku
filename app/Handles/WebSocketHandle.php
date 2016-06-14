<?php

namespace Akku\Handles;

class WebSocketHandle
{
    
    public $server;
    
    public function onConnect($data)
    {
        $i = rand(1, 4500);
        dd("New Connected: " . $i);
        return $i;
    }
    
    public function onDisconnect($id)
    {
        dd("Disconnected: " . $id);
    }
    
    public function onReceive($id, $receive)
    {
        dd("Data receive : $id", $data);
        $this->server->sendData($connection_id,$action,$data);
    }
    
    
    
}