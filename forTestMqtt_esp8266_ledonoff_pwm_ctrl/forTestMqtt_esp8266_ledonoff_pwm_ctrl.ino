/*----------------CodeBy RJK---------------------------*/
#include <ESP8266WiFi.h>
#include <PubSubClient.h>
#include <ArduinoJson.h>
#define Led LED_BUILTIN 
#define Ledpwm 4 //GPIO 4

const char* ssid = "RJKgps";
const char* password =  "2018rjkg";
const char* mqttServer = "m23.cloudmqtt.com";
const int mqttPort = 10433;
const char* mqttUser = "kksgqctk";
const char* mqttPassword = "OSQ5fskbM9w8";
const char* Topic0 = "led/ctrl";
const char* Topic1 = "adc/send";
const char* Topic2 = "send/pwm";
const char* Topicstart = "rjk/start";


WiFiClient espClient;
PubSubClient client(espClient);

void mqttreconnect(){
  while (!client.connected()) {
    Serial.println("Connecting to MQTT...");
     if (client.connect("RJKClient", mqttUser, mqttPassword )) {
 
      Serial.println("connected");  
 
    } else {
 
      Serial.print("failed with state ");
      Serial.print(client.state());
      delay(2000);
 
    }
  }
}


void SendPWM(int pwm){
  StaticJsonDocument<300> data;
  data["adc1"] = pwm;
  serializeJson(data, Serial);
  Serial.println("Send OK...");
   char buffer[512];
      serializeJson(data, buffer);
       client.publish(Topic2,buffer);
}

void ledeffect(){
  for(int b=1000;b>=20;b-=5){
    digitalWrite(Led,LOW);
    delay(b);
    digitalWrite(Led,HIGH);
    delay(b);
    }
    delay(500);
   for(int b=20;b<=1000;b+=5){
    digitalWrite(Led,LOW);
    delay(b);
    digitalWrite(Led,HIGH);
    delay(b);
    }
}

void blktimes(int tm){
  for(int m=0;m<tm;m++){
    digitalWrite(Led,!digitalRead(Led));
    delay(125);
    digitalWrite(Led,!digitalRead(Led));
    delay(125);
  }
}
void setup() {
  pinMode(Led,OUTPUT);
  pinMode(Ledpwm,OUTPUT);
  //analogWrite(Ledpwm,125);
  digitalWrite(Led,HIGH);
  Serial.begin(115200);
 
  WiFi.begin(ssid, password);
 
  while (WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.println("Connecting to WiFi..");
  }
  Serial.println("Connected to the WiFi network");
 
  client.setServer(mqttServer, mqttPort);
  client.setCallback(effect);
 
  while (!client.connected()) {
    Serial.println("Connecting to MQTT...");
 
    if (client.connect("RJKClient", mqttUser, mqttPassword )) {
 
      Serial.println("connected");  
 
    } else {
 
      Serial.print("failed with state ");
      Serial.print(client.state());
      delay(2000);
 
    }
  }
 
  //client.publish(Topicstart, "Start By RJK ESP8266(OR Reset)");
  client.subscribe(Topic1);
  client.subscribe(Topic0);
}
 
void effect(char* topic, byte* payload, unsigned int length) {
 
  Serial.print("Message arrived in topic: ");
  Serial.println(topic);
 
  Serial.print("Message:");
  for (int i = 0; i < length; i++) {
    Serial.print((char)payload[i]);
  }
 if(strncmp(Topic0, topic, strlen(Topic0)) == 0){ 
 if((payload[0] == '1')||((payload[0] == 'o')&&(payload[1] == 'n'))){
  digitalWrite(Led,LOW);
  client.publish(Topic0,"Status ON");
 }
 if((payload[0] == '0')||((payload[0] == 'o')&&(payload[1] == 'f')&&(payload[2] == 'f'))){
  digitalWrite(Led,HIGH);
  client.publish(Topic0,"Status OFF");
 }
 if(payload[0] == '2'){
  
  digitalWrite(Led,HIGH);
    delay(100);
  
    for(byte n=0;n<2;n++){
      digitalWrite(Led,!digitalRead(Led));
      delay(500);
    }
  client.publish(Topic0,"Status Blink 1 time");
 
 }
 if(payload[0] == '3'){
   client.publish(Topic0,"Status Blink slow to fast Rua...");
   ledeffect(); //รัวๆ 
 }
  Serial.println();
  Serial.println("-----------------------");
 }
 
 if(strncmp(Topic1, topic, strlen(Topic1)) == 0){
      Serial.println((char*)payload);
       int aNumber = atoi((char *)payload);
      analogWrite(Ledpwm,aNumber);
      byte p  = analogRead(Ledpwm);
      Serial.write(p);
      Serial.println();
      SendPWM(aNumber);
    }
}
 
void loop() {
  if(!client.connected()){
     mqttreconnect();
  }
 
  client.loop();
}
