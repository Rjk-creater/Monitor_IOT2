/*----------------CodeBy RJK---------------------------*/
#include <ESP8266WiFi.h>
#include <PubSubClient.h>
#include <ArduinoJson.h>
#define Led LED_BUILTIN 
#define Ledpwm 4 //GPIO 4
#define VR A0 //VR analog input
#define Vpk 0.82 //Peak at 255 in GPIO4 output

//at the beginning of sketch
//ADC_MODE(ADC_VCC); //vcc read

const char* ssid = "RJKgps";
const char* password =  "2018rjkg";
const char* mqttServer = "m23.cloudmqtt.com";
const int mqttPort = 10433;
const char* mqttUser = "kksgqctk";
const char* mqttPassword = "OSQ5fskbM9w8";
const char* Topic0 = "led/ctrl";
const char* Topic1 = "adc/send";
const char* Topic2 = "send/pwm"; //use for MCU 
const char* Topicstart = "rjk/start";
const char* Topic3 = "blk/num";
const char* Topic4 = "vr/send";

int Countblk = 0;

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

float Volts(int adc){
  float v = (3.28/1024)*adc;
  return v;
}

float DutyCycle_8bit(int adc){
  float dc = (adc*100)/255;
  return dc;
}
float Vrms(int adc){
  float vrms = (Vpk*adc)/255;
  return vrms;
}
void SendPWM(int pwm){
  StaticJsonDocument<300> data;
  data["adc1"] = pwm;
  data["duty"] = DutyCycle_8bit(pwm);
  data["vrms"] = Vrms(pwm);
  serializeJson(data, Serial);
  Serial.println("Send OK...");
   char buffer[512];
      serializeJson(data, buffer);
       client.publish(Topic2,buffer);
}

void SendADC_VR(){
  int Adc = analogRead(VR);
  //float vcc = ESP.getVcc();
  float v = Volts(Adc);
  Serial.printf("VR = %d\t Volts = %0.2f\n",Adc,v);
  //Serial.println(vcc);
  StaticJsonDocument<300> vrdata;
  vrdata["value"] = Adc;
  vrdata["volts"] = v;
  char buffer[512];
    serializeJson(vrdata, buffer);
     client.publish(Topic4,buffer);
      delay(2000);  
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
    Countblk++;
    }
  String cb = "LED Blink "+ String(Countblk)+" Times";
  client.publish(Topic0,cb.c_str());
  Countblk = 0;
}
void setup() {
  pinMode(Led,OUTPUT);
  pinMode(Ledpwm,OUTPUT);
  pinMode(VR,INPUT);
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
  client.subscribe(Topic3);
}
 
void effect(char* topic, byte* payload, unsigned int length) {
 
  Serial.print("Message arrived in topic: ");
  Serial.println(topic);
 
  Serial.print("Message:");
  for (int i = 0; i < length; i++) {
    Serial.print((char)payload[i]);
  }
 if(strncmp(Topic0, topic, strlen(Topic0)) == 0){ 
 if((payload[0] == 'o')&&(payload[1] == 'n')){
  digitalWrite(Led,LOW);
  client.publish(Topic0,"Status ON");
 }
 if((payload[0] == 'o')&&(payload[1] == 'f')&&(payload[2] == 'f')){
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
 if((payload[0] == 't')&&(payload[1] == 't')){
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
 
if(strncmp(Topic3, topic, strlen(Topic3)) == 0){
    
    blktimes(atoi((char *)payload));
  }
}

    
void loop() {
  if(!client.connected()){
     mqttreconnect();
  }
  SendADC_VR();
  client.loop();
}
