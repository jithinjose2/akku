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
#include "DHT.h"


//////////////////////////////////// CONFIG SECTION ////////////////////////////////////////////////

ESP8266WiFiMulti WiFiMulti;
WebSocketsClient webSocket;


// Required constants
#define MODULE_KEY "MODULE01"
#define MODULE_PIN "1234"
#define SWITCH1_KEY 1
#define SWITCH2_KEY 2
#define SWITCH3_KEY 3
#define SWITCH4_KEY 4
#define WIFI_NAME "jithinband"
#define WIFI_PASS "jithinband"
#define WEBSOCKET_IP "10.42.0.1"
#define WEBSOCKET_PORT 8001

#define SWITCH1 D0
#define SWITCH2 D1
#define SWITCH3 D2
#define SWITCH4 D3
#define BLED1 D4
#define BLED2 D5
#define TEMP1 D7
#define POWER1 A0

int percent = 0, max_motor_active_time = 120, max_level = 200, min_level = 1400, trigger_percent=10, cutoff_percent = 90, motor_active = 0, motor_time_left = 0;
float temp = 21.0;
float humid = 22.0;
int power = 570;


// Required buffer values
int socket_status = 0, loopcount=0;
char buffer[256];
StaticJsonBuffer<2000> jsonBuffer;
JsonObject& root = jsonBuffer.createObject();

DHT dht(TEMP1, DHT11);



//////////////////////////////////// LEDBAR SECTION////////////////////////////////////////////////

void sendSwitchStatus(int THINGID, int status)
{
    root["action"] = "update_data";
    root["thing_id"] = THINGID;
    root["value"] = status;
    root.printTo(buffer, sizeof(buffer));
    Serial.printf(buffer);
    webSocket.sendTXT(buffer);
}

void sendSensnorValue(int THINGID, int value)
{
  root["action"] = "update_data";
  root["thing_id"] = THINGID;
  root["value"] = value;
  root.printTo(buffer, sizeof(buffer));
  Serial.printf(buffer);
  webSocket.sendTXT(buffer);
}

void updateSwitchStatus(int SWITCH_KEY, int SWITCH_STATUS)
{
    int SWITCH_PIN = SWITCH1;
    
    if(SWITCH_KEY == 1){
         SWITCH_PIN = SWITCH1;
    } else if(SWITCH_KEY == 2){
         SWITCH_PIN = SWITCH2;
    } else if(SWITCH_KEY == 3){
         SWITCH_PIN = SWITCH3;
    } else if(SWITCH_KEY == 4){
         SWITCH_PIN = SWITCH4;
    }
  
    if(SWITCH_STATUS == 1) {
        digitalWrite(SWITCH_PIN, HIGH);
    } else {
        digitalWrite(SWITCH_PIN, LOW);
    }
    
    sendSwitchStatus(SWITCH_KEY, SWITCH_STATUS);
}


void updateTemprature()
{
  float h = dht.readHumidity();
  // Read temperature as Celsius (the default)
  float t = dht.readTemperature();
  // Read temperature as Fahrenheit (isFahrenheit = true)
  float f = dht.readTemperature(true);

  if (isnan(h) || isnan(t) || isnan(f)) {
    Serial.println("Failed to read from DHT sensor!");        
  }
  else{
    temp = dht.computeHeatIndex(t, h, false); 
    humid = h;
  }  
}


//////////////////////////////////// COMM SECTION////////////////////////////////////////////////

// String convertion req.
void registerModule()
{
  root["action"] = "register";
  root["key"] = MODULE_KEY;
  root["pin"] = MODULE_PIN;
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
        // Set conf
        if(strcmp(sensor, "registred") == 0) {
            socket_status = 1;
        } else if(strcmp(sensor,"update_switch_status") == 0) {
            updateSwitchStatus(root1["THING_ID"], root1["value"]);
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
    pinMode(SWITCH1, OUTPUT);
    pinMode(SWITCH2, OUTPUT);
    pinMode(SWITCH3, OUTPUT);
    pinMode(SWITCH4, OUTPUT);
    pinMode(BLED1, OUTPUT);
    pinMode(BLED2, OUTPUT);

    pinMode(TEMP1, INPUT);
    pinMode(POWER1, INPUT);
  
    Serial.begin(115200);
    Serial.setDebugOutput(true);
    Serial.println();
    Serial.println();
    Serial.println();

    WiFiMulti.addAP(WIFI_NAME, WIFI_PASS);

    dht.begin();

    //WiFi.disconnect();
    while(WiFiMulti.run() != WL_CONNECTED) {
        delay(100);
    }

    webSocket.begin(WEBSOCKET_IP, WEBSOCKET_PORT);
    webSocket.onEvent(webSocketEvent);

    // Set initial status
    digitalWrite(SWITCH1, HIGH);
    digitalWrite(SWITCH2, HIGH);
    digitalWrite(SWITCH3, HIGH);
    digitalWrite(SWITCH3, HIGH);
    digitalWrite(BLED1, LOW);
    digitalWrite(BLED1, LOW);

}



void loop() {

    ESP.wdtDisable();

    int distance;
    
    // Call websocket loop
    webSocket.loop();

    int abc= analogRead(POWER1);
    Serial.println(abc);

    if(socket_status == 1) {
      if(loopcount == 1) {
        updateTemprature();
      } else if(loopcount == 2) {
        sendSensnorValue(5,temp);
      } else if(loopcount == 3) {
        sendSensnorValue(6,humid);
      } else if(loopcount == 4) {
        int power= analogRead(POWER1);
        sendSensnorValue(7,power);
        loopcount = 0;
      }
      loopcount++;
    }

    delay(3000);
    
    
}
