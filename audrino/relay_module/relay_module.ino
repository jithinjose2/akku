/*
 * WebSocketClient.ino
 *
 *  Created on: 24.05.2015
 *
 */

#include <Arduino.h>
#include <ESP8266WiFi.h>
#include <ESP8266WiFiMulti.h>
#include <WebSocketsClient.h>
#include <Hash.h>
#include <ArduinoJson.h>


//////////////////////////////////// CONFIG SECTION////////////////////////////////////////////////

ESP8266WiFiMulti WiFiMulti;
WebSocketsClient webSocket;


// Required constants
#define MODULE_KEY "MODULE02"
#define SWITCH_KEY "MOTOR01"
#define METER_KEY "LEDBAR01"
#define WIFI_NAME "HOMENET"
#define WIFI_PASS "myhome02"
#define WEBSOCKET_IP "192.168.1.103"
#define WEBSOCKET_PORT 8001
#define ULTRASONIC_TRIGGER_PIN 14
#define ULTRASONIC_ECHO_PIN    12

#define SWITCH_PIN D8

#define BLED1 D0
#define BLED2 D1
#define BLED3 D2
#define BLED4 D3
#define BLED5 D4
#define BLED6 D5
#define BLED7 D6
#define BLED8 D7


int percent = 0, max_motor_active_time = 120, max_level = 200, min_level = 1400, trigger_percent=10, cutoff_percent = 90, motor_active = 0, motor_time_left = 0;




// Required buffer values
int socket_status = 0, loop_count = 0;
char buffer[256];
StaticJsonBuffer<2000> jsonBuffer;
JsonObject& root = jsonBuffer.createObject();



//////////////////////////////////// LEDBAR SECTION////////////////////////////////////////////////

void sendMotorStatus(int status)
{
    root["action"] = "update_data";
    root["thing_key"] = SWITCH_KEY;
    root["value"] = status;
    root.printTo(buffer, sizeof(buffer));
    Serial.printf(buffer);
    webSocket.sendTXT(buffer);
}

void motorActivate()
{
    sendMotorStatus(1);
    digitalWrite(SWITCH_PIN, HIGH);
    motor_active = 1;
    motor_time_left = (100 - percent) * max_motor_active_time / 100;
}

void motorDeActivate()
{
    sendMotorStatus(0);
    digitalWrite(SWITCH_PIN, LOW);
    motor_active = 0;
    motor_time_left = 0;
}



void setValue(int distance)
{
    percent = (distance - max_level) * 100 / (min_level - max_level);

    digitalWrite(BLED1, LOW);
    digitalWrite(BLED2, LOW);
    digitalWrite(BLED3, LOW);
    digitalWrite(BLED4, LOW);
    digitalWrite(BLED5, LOW);
    digitalWrite(BLED6, LOW);
    digitalWrite(BLED7, LOW);
    digitalWrite(BLED8, LOW);

    if(percent > 10){
        digitalWrite(BLED1, HIGH);
    }

    if(percent > 20){
        digitalWrite(BLED2, HIGH);
    }

    if(percent > 35){
        digitalWrite(BLED3, HIGH);
    }

    if(percent > 45){
        digitalWrite(BLED4, HIGH);
    }

    if(percent > 55){
        digitalWrite(BLED5, HIGH);
    }

    if(percent > 67){
        digitalWrite(BLED6, HIGH);
    }

    if(percent > 80){
        digitalWrite(BLED7, HIGH);
    }

    if(percent > 90){
        digitalWrite(BLED8, HIGH);
    }
    
    Serial.printf("Process: %d- %d - %d - %d \n", percent, distance, trigger_percent, motor_active);
    
    // If level is within trigger level execute motor
    if(percent < trigger_percent && motor_active == 0) {
        motorActivate();
    }
    if(percent > cutoff_percent && motor_active == 1) {
        motorDeActivate();
    }
    
}




//////////////////////////////////// COMM SECTION////////////////////////////////////////////////

// String convertion req.
void registerModule()
{
  root["action"] = "register";
  root["key"] = MODULE_KEY;
  root.printTo(buffer, sizeof(buffer));
  Serial.printf(buffer);
  webSocket.sendTXT(buffer);
}



/*
void sendDistance(int value)
{
  root["action"] = "update_data";
  root["key"] = MODULE_KEY;
  root["thing_key"] = SENSOR_KEY;
  root["value"] = value;
  root.printTo(buffer, sizeof(buffer));
  Serial.printf(buffer);
  webSocket.sendTXT(buffer);
}*/

void processResponse(char* response) 
{
    Serial.printf("Successssss %s", response);
    JsonObject& root1 = jsonBuffer.parseObject(response);
    if (root1.success()) {
        const char* sensor = root1["action"];

        // Set configuration
        if(strcmp(sensor,"registred") == 0) {
            max_motor_active_time = root1["max_motor_active_time"];
            max_level = root1["max_level"];
            min_level = root1["min_level"];
            trigger_percent = root1["trigger_percent"];
            cutoff_percent = root1["cutoff_percent"];
            socket_status = 1;
        } else if(strcmp(sensor,"water_level_update") == 0) {
            setValue(root1["value"]);
        } else if(strcmp(sensor,"update_switch_status") == 0) {
            if(root1["value"] == 1) {
                motorActivate();
            } else {
                motorDeActivate();
            }
        }
        
    }
    return;
}

//////////////////////////////////// WEBSOCKET SECTION////////////////////////////////////////////////

void webSocketEvent(WStype_t type, uint8_t * payload, size_t lenght) {

    switch(type) {
        case WStype_DISCONNECTED:
            Serial.printf("[WSc] Disconnected!\n");
            socket_status = 0;
            break;
        case WStype_CONNECTED:
            {
                socket_status = 0;
                registerModule();
            }
            break;
        case WStype_TEXT:
            processResponse((char*)payload);
            break;
        case WStype_BIN:
            Serial.printf("[WSc] get binary lenght: %u\n", lenght);
            hexdump(payload, lenght);
            break;
    }

}



//////////////////////////////////// INTI SECTION ////////////////////////////////////////////////

void setup() {

    // Configure PINS
    pinMode(BLED1, OUTPUT);
    pinMode(BLED2, OUTPUT);
    pinMode(BLED3, OUTPUT);
    pinMode(BLED4, OUTPUT);
    pinMode(BLED5, OUTPUT);
    pinMode(BLED6, OUTPUT);
    pinMode(BLED7, OUTPUT);
    pinMode(BLED8, OUTPUT);
    pinMode(BLED8, OUTPUT);
    pinMode(SWITCH_PIN, OUTPUT);

    
  
    Serial.begin(115200);
    Serial.setDebugOutput(true);
    Serial.println();
    Serial.println();
    Serial.println();

    for(uint8_t t = 4; t > 0; t--) {
        Serial.printf("[SETUP] BOOT WAIT %d...\n", t);
        Serial.flush();
        delay(1000);
    }

    WiFiMulti.addAP(WIFI_NAME, WIFI_PASS);

    //WiFi.disconnect();
    while(WiFiMulti.run() != WL_CONNECTED) {
        delay(100);
    }

    webSocket.begin(WEBSOCKET_IP, WEBSOCKET_PORT);
    webSocket.onEvent(webSocketEvent);

    // Set initial status
    digitalWrite(BLED1, LOW);
    digitalWrite(BLED2, LOW);
    digitalWrite(BLED3, LOW);
    digitalWrite(BLED4, LOW);
    digitalWrite(BLED5, LOW);
    digitalWrite(BLED6, LOW);
    digitalWrite(BLED7, LOW);
    digitalWrite(BLED8, LOW); 
    digitalWrite(SWITCH_PIN, LOW);

}



void loop() {

    ESP.wdtDisable();

    int distance;
    
    // Call websocket loop
    webSocket.loop();
    
    if(socket_status == 1) {
        setValue(800);
    }

    if(motor_active == 1) {
        motor_time_left -= 1;
        if(motor_time_left < 1) {
            motorDeActivate();
        }
    }

    delay(1000);
    
}
