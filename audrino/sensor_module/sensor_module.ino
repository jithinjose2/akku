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
#define MODULE_KEY "MODULE01"
#define SENSOR_KEY "WATERLEVEL01"
#define WIFI_NAME "HOMENET"
#define WIFI_PASS "myhome02"
#define WEBSOCKET_IP "103.3.63.222"
#define WEBSOCKET_PORT 8001
#define ULTRASONIC_TRIGGER_PIN 14
#define ULTRASONIC_ECHO_PIN    12
#define ULTRASONIC_MIN_DISTANCE 10
#define ULTRASONIC_MAX_DISTANCE 4000







// Required buffer values
int socket_status = 0, loop_count = 0;
char buffer[256];
StaticJsonBuffer<2000> jsonBuffer;
JsonObject& root = jsonBuffer.createObject();


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

void sendDistance(int value)
{
  root["action"] = "update_data";
  root["key"] = MODULE_KEY;
  root["thing_key"] = SENSOR_KEY;
  root["value"] = value;
  root.printTo(buffer, sizeof(buffer));
  Serial.printf(buffer);
  webSocket.sendTXT(buffer);
}

void processResponse(char* response) 
{
    Serial.printf("Successssss %s", response);
    JsonObject& root1 = jsonBuffer.parseObject(response);
    if (root1.success()) {
        const char* sensor = root1["action"];
        
        if(strcmp(sensor,"registred") == 0) {
            socket_status = 1;
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

//////////////////////////////////// ULTRASONIC SECTION////////////////////////////////////////////////

/**
 *  take single distance mesurement and return the value
 */
int mesureSingleDistance()
{
    long duration, distance;
    
    digitalWrite(ULTRASONIC_TRIGGER_PIN, LOW);  
    delayMicroseconds(2); 
    digitalWrite(ULTRASONIC_TRIGGER_PIN, HIGH);
    delayMicroseconds(10); 
    digitalWrite(ULTRASONIC_TRIGGER_PIN, LOW);
    duration = pulseIn(ULTRASONIC_ECHO_PIN, HIGH);
    distance = (duration/2) / 2.91;
    return distance;
}

/**
 * mresure distnce average delay 10ms
 */
int mesureDistance()
{
    int i, total_count = 0, total_distance =0,distance, values[10], average, temp, new_count = 0;
    
    for(i=0; i<5; i++){
        distance = mesureSingleDistance();
        if(distance > ULTRASONIC_MIN_DISTANCE && distance < ULTRASONIC_MAX_DISTANCE) {
            total_distance += distance;
            values[total_count++]=distance;
            Serial.printf("Value %d...\n", distance);
        }
    }
    
    if(total_count > 0) {
      
        average = total_distance / total_count;
        total_distance = 0;
        Serial.printf("Average %d...\n", average);
        
        for(i=0; i<total_count; i++){
            temp = (average - values[i])*100/average;
            Serial.printf("Analyze %d....%d...\n", values[i],temp);
            if(temp < 10 && temp > -10){
                total_distance += values[i];
                new_count++;
            }
        }

        if(new_count > 0) {
            return total_distance / new_count;
        }
    }
    return 0;
}








//////////////////////////////////// INTI SECTION ////////////////////////////////////////////////

void setup() {

    // Configure PINS
    pinMode(ULTRASONIC_TRIGGER_PIN, OUTPUT);
    pinMode(ULTRASONIC_ECHO_PIN, INPUT);
  
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

}



void loop() {

    ESP.wdtDisable();

    int distance;
    
    // Call websocket loop
    webSocket.loop();
    
    if(socket_status == 1 && loop_count>5) {
        distance = mesureDistance();
        Serial.printf("Distance %d..\n", distance);
        if(distance > 0 ) {
            sendDistance(distance);
        }
        loop_count = 0;
    }

    loop_count++;
    delay(1000);
    
}
