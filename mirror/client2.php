<?php
/**
 * Created by PhpStorm.
 * User: jithinjose
 * Date: 19/6/16
 * Time: 2:19 PM
 */

require_once("vendor/autoload.php");                // Composer autoloader

$loop = \React\EventLoop\Factory::create();

$logger = new \Zend\Log\Logger();
$writer = new Zend\Log\Writer\Stream("php://output");
$logger->addWriter($writer);

$client = new \Devristo\Phpws\Client\WebSocket("ws://192.168.1.103:8001/demo", $loop, $logger);

$client->on("request", function($headers) use ($logger){
    $logger->notice("Request object created!");
});

$client->on("handshake", function() use ($logger) {
    $logger->notice("Handshake received!");
});

$client->on("connect", function($headers) use ($logger, $client){
    $logger->notice("Connected!");
    // Send registration request
    $client->send(json_encode(['action' => 'register', 'key' => 'MODULE03']));
});

$client->on("message", function($message) use ($client, $logger){
    $logger->notice("Got message: ".$message->getData());
});


$loop->addPeriodicTimer(0.25, function(){
    echo "Timer : " . shell_exec('gpio read 29');
});


$client->open();
$loop->run();