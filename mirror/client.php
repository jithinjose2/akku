<?php
/**
 * Created by PhpStorm.
 * User: jithinjose
 * Date: 19/6/16
 * Time: 12:14 PM
 */

include "vendor/autoload.php";

use WebSocketClient\WebSocketClient;
use WebSocketClient\WebSocketClientInterface;

class Client implements WebSocketClientInterface
{
    private $client;

    public function onWelcome(array $data)
    {
    }

    public function onEvent($topic, $message)
    {
    }

    public function subscribe($topic)
    {
        $this->client->subscribe($topic);
    }

    public function unsubscribe($topic)
    {
        $this->client->unsubscribe($topic);
    }

    public function call($proc, $args, Closure $callback = null)
    {
        $this->client->call($proc, $args, $callback);
    }

    public function publish($topic, $message)
    {
        $this->client->publish($topic, $message);
    }

    public function setClient(WebSocketClient $client)
    {
        $this->client = $client;
    }
}

$loop = React\EventLoop\Factory::create();

$client = new WebSocketClient(new Client, $loop, '192.168.1.103', '8001');

$loop->run();