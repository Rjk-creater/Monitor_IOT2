/*----------------CodeBy RJK---------------------------*/
#include <ESP8266WiFi.h>
#include <PubSubClient.h>
#define Led LED_BUILTIN 

const char* ssid = "RJKgps";
const char* password =  "2018rjkg";
const char* mqttServer = "m23.cloudmqtt.com";
const int mqttPort = 10433;
const char* mqttUser = "kksgqctk";
const char* mqttPassword = "OSQ5fskbM9w8";
const char* Topic = "rjkmq1";

WiFiClient espClient;
PubSubClient client(espClient);

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
 
void setup() {
  pinMode(Led,OUTPUT);
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
 
    if (client.connect("ESP8266Client", mqttUser, mqttPassword )) {
 
      Serial.println("connected");  
 
    } else {
 
      Serial.print("failed with state ");
      Serial.print(client.state());
      delay(2000);
 
    }
  }
 
  client.publish(Topic, "Start By RJK ESP8266(OR Reset)");
  client.subscribe(Topic);
 
}
 
void effect(char* topic, byte* payload, unsigned int length) {
 
  Serial.print("Message arrived in topic: ");
  Serial.println(topic);
 
  Serial.print("Message:");
  for (int i = 0; i < length; i++) {
    Serial.print((char)payload[i]);
  }
 if((payload[0] == '1')||((payload[0] == 'o')&&(payload[1] == 'n'))){
  digitalWrite(Led,LOW);
  client.publish(Topic,"Status ON");
 }
 if((payload[0] == '0')||((payload[0] == 'o')&&(payload[1] == 'f')&&(payload[2] == 'f'))){
  digitalWrite(Led,HIGH);
  client.publish(Topic,"Status OFF");
 }
 if(payload[0] == '2'){
  
  digitalWrite(Led,HIGH);
    delay(100);
  
    for(byte n=0;n<2;n++){
      digitalWrite(Led,!digitalRead(Led));
      delay(500);
    }
  client.publish(Topic,"Status Blink 1 time");
 
 }
 if(payload[0] == '3'){
   client.publish(Topic,"Status Blink slow to fast Rua...");
   ledeffect(); //รัวๆ 
 }
  Serial.println();
  Serial.println("-----------------------");
 
}
 
void loop() {
  client.loop();
}
