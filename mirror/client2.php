<?php
/**
 * Created by PhpStorm.
 * User: jithinjose
 * Date: 19/6/16
 * Time: 2:19 PM
 */

require_once("vendor/autoload.php");                // Composer autoloader

$config = [
    'WS_HOST' => '192.168.1.103',
    'WS_PORT' => 8001,
    'MODULE_KEY' => 'MODULE03',

    'IRSENSOR_KEY'  => 'IRMOTION01',
    'IRSENSOR_PIN'  => 29,

    'TEMPSENSOR_KEY' => 'TEMP01',
    'HUMIDSESNSOR_KEY' => 'HUMID01',
    'TEMP_HUMID_SESNOR_PIN' => 28,

    'LED_KEY'       => 'LED01',
    'LED_RED_PIN'   => 0,
    'LED_GREEN_PIN' => 2,
    'LED_BLUE_PIN'  => 3,

    'SWITCH_LED_KEY'    => 'SWITCHLED01',
    'SWITCH_LIGHT_KEY'  => 'SWITCHLIGHT01',
    'SWITCH_LCD_KEY'    => 'SWITCHLCD02',
    'SWITCH_LED_PIN'    => 21,
    'SWITCH_LIGHT_PIN'  => 22,
    'SWITCH_LCD_PIN'    => 23,

];

$led_color = "";

// Set pin modes & initial status
shell_exec('gpio mode '.$config['IRSENSOR_PIN'].' in');
shell_exec('gpio mode '.$config['TEMP_HUMID_SESNOR_PIN'].' in');
shell_exec('gpio mode '.$config['LED_RED_PIN'].' pwm');
shell_exec('gpio mode '.$config['LED_GREEN_PIN'].' pwm');
shell_exec('gpio mode '.$config['LED_BLUE_PIN'].' pwm');

shell_exec('gpio write '.$config['SWITCH_LED_PIN'].' 1');
shell_exec('gpio write '.$config['SWITCH_LIGHT_PIN'].' 1');
shell_exec('gpio write '.$config['SWITCH_LCD_PIN'].' 1');
shell_exec('gpio mode '.$config['SWITCH_LED_PIN'].' out');
shell_exec('gpio mode '.$config['SWITCH_LIGHT_PIN'].' out');
shell_exec('gpio mode '.$config['SWITCH_LCD_PIN'].' out');
shell_exec('gpio write '.$config['SWITCH_LED_PIN'].' 1');
shell_exec('gpio write '.$config['SWITCH_LIGHT_PIN'].' 1');
shell_exec('gpio write '.$config['SWITCH_LCD_PIN'].' 0');


$loop = \React\EventLoop\Factory::create();
$logger = new \Zend\Log\Logger();
$writer = new Zend\Log\Writer\Stream("php://output");
$logger->addWriter($writer);

$client = new \Devristo\Phpws\Client\WebSocket("ws://" . $config['WS_HOST'] . ":" . $config['WS_PORT'] . "/demo", $loop, $logger);

$client->on("request", function($headers) use ($logger){
    $logger->notice("Request object created!");
});

$client->on("handshake", function() use ($logger) {
    $logger->notice("Handshake received!");
});

$client->on("connect", function($headers) use ($logger, $client, $config){
    $logger->notice("Connected!");
    // Send registration request
    $client->send(json_encode(['action' => 'register', 'key' => $config['MODULE_KEY']]));
});

$client->on("message", function($message) use ($client, $logger){
    $logger->notice("Got message: ".$message->getData());
    if(($data = json_decode($message->getData(), true)) != NULL) {
        $fn_name = 'action_' . $data['action'];
        if(!empty($data['action']) && function_exists($fn_name)) {
            $fn_name($data);
        }
    }
});



// Registered action
function action_registered($data)
{
    if(!empty($data['led_color'])) {
        action_update_led_color(['value' => $data['led_color']]);
    }
}

// IR sensor handling
$last_ir_value = 0;
$loop->addPeriodicTimer(0.25, function() use($client, $last_ir_value, $config){
    $ir_value = shell_exec('gpio read ' . $config['IRSENSOR_PIN']);
    if($ir_value != $last_ir_value) {
        $client->send(json_encode(['action' => 'update_data', 'thing_key' => $config['IRSENSOR_KEY'], 'value' => $ir_value]));
        $last_ir_value = $ir_value;
    }
});

// Temperature humidity sensor section
$loop->addPeriodicTimer(5, function() use($client, $config){
    // $config['TEMP_HUMID_SESNOR_PIN'];
    $temperature = rand(5,10);//shell_exec('gpio read 29');
    $client->send(json_encode(['action' => 'update_data', 'thing_key' => $config['TEMP01'], 'value' => $temperature]));
    $humidity = rand(5,10);//shell_exec('gpio read 29');
    $client->send(json_encode(['action' => 'update_data', 'thing_key' => $config['HUMIDSESNSOR_KEY'], 'value' => $humidity]));
});

// LED color update event
function action_update_led_color($data)
{
    global $config, $client;
    if(!empty($data['value'])) {
        if(($rgb = hex2RGB($data['value'])) !== false ) {
            gpio_pwm_write($config['LED_RED_PIN'], $rgb['r']);
            gpio_pwm_write($config['LED_GREEN_PIN'], $rgb['g']);
            gpio_pwm_write($config['LED_BLUE_PIN'], $rgb['b']);
            $client->send(json_encode(['action' => 'update_data', 'thing_key' => $config['LED_KEY'], 'value' => $data['value']]));
        }
    }
}

// Switch updated
function update_switch_status($key, $pin, $data) {
    global  $config, $client;
    if($data['value']) {
        $client->send(json_encode(['action' => 'update_data', 'thing_key' => $config[$key], 'value' => $data['value']]));
        shell_exec('gpio write '.$config[$pin].' 0');
    } else {
        $client->send(json_encode(['action' => 'update_data', 'thing_key' => $config[$key], 'value' => $data['value']]));
        shell_exec('gpio write '.$config[$pin].' 1');
    }
}
function action_update_led_status($data)
{
    update_switch_status('SWITCH_LED_KEY', 'SWITCH_LED_PIN', $data);
}
function action_update_lcd_status($data)
{
    update_switch_status('SWITCH_LCD_KEY', 'SWITCH_LCD_PIN', $data);
}
function action_update_light_status($data)
{
    update_switch_status('SWITCH_LIGHT_KEY', 'SWITCH_LIGHT_PIN', $data);
}












// CORE functions
$client->open();
$loop->run();


function hex2RGB($hexStr, $returnAsString = false, $seperator = ',') {
    $hexStr = preg_replace("/[^0-9A-Fa-f]/", '', $hexStr); // Gets a proper hex string
    $rgbArray = array();
    if (strlen($hexStr) == 6) { //If a proper hex code, convert using bitwise operation. No overhead... faster
        $colorVal = hexdec($hexStr);
        $rgbArray['r'] = 0xFF & ($colorVal >> 0x10);
        $rgbArray['g'] = 0xFF & ($colorVal >> 0x8);
        $rgbArray['b'] = 0xFF & $colorVal;
    } elseif (strlen($hexStr) == 3) { //if shorthand notation, need some string manipulations
        $rgbArray['r'] = hexdec(str_repeat(substr($hexStr, 0, 1), 2));
        $rgbArray['g'] = hexdec(str_repeat(substr($hexStr, 1, 1), 2));
        $rgbArray['b'] = hexdec(str_repeat(substr($hexStr, 2, 1), 2));
    } else {
        return false; //Invalid hex color code
    }
    return $returnAsString ? implode($seperator, $rgbArray) : $rgbArray; // returns the rgb string or the associative array
}

function gpio_pwm_write($pin, $value){
    $value = ($value * 4 ) + 3;
    shell_exec('gpio write ' . $pin . ' ' . $value);
}