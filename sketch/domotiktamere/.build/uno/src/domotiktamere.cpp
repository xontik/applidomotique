#include <Arduino.h>

void setup();
int getState(int i);
void setState(int i, int state);
void writeState(int i);
void sendState(int id, int state);
void checkNewState();
void loop();
bool serverreceive();
void command();
#line 1 "src/domotiktamere.ino"
#define NB_DEVICES 25
#define dev(x) devices[x]

String cmd;
unsigned long time;
typedef struct Device{
  int pin;
  int pinout;
  bool pulsed;
  bool normallyClosed;
  int state;
};
Device devices[NB_DEVICES];



void setup() {
  for(int i =0;i<NB_DEVICES;i++){
   dev(i).pin = i;
   pinMode(i,OUTPUT);
   dev(i).pinout = -1;
   dev(i).pulsed = false;
   dev(i).normallyClosed = false;
   dev(i).state = 0;
  }
  Serial.begin(115200);
  Serial1.begin(115200);
  Serial1.println("Starting...");
 
}
int getState(int i){
   if(dev(i).pinout == -1){
      return dev(i).state;
   }else{
      return digitalRead(dev(i).pinout); 
   }
}
void setState(int i, int state){
   dev(i).state = state; 
   Serial1.print("Setting ");
   Serial1.print(i);
   Serial1.print(" as ");
   Serial1.println(state);
}
void writeState(int i){
   if(dev(i).normallyClosed){
      digitalWrite(dev(i).pin,1-dev(i).state);
   }else{
      digitalWrite(dev(i).pin,dev(i).state);
   }   
}

void sendState(int id, int state){
  Serial.print("c");
  
  if(id<10){
    Serial.print("0");
  }
  Serial.print(id);
  Serial.print(state);
  Serial.print("\n\t");
  
  Serial1.println("Some states have changed !");

}
void checkNewState(){
  for(int i = 0; i<14;i++){
    if(dev(i).state != getState(i)){
       dev(i).state = getState(i);
       sendState(i,dev(i).state);
    }
  }
}
      


void loop() {
  if(serverreceive()){
    command();
  }
  if(millis() - time > 3000){
    time = millis();
    sendState(3,1);
  }
  
}
bool serverreceive() {
  if (Serial.available() > 0) {
    cmd = Serial.readStringUntil(':');
    return true;
  }
  return false;
}

void command(){
  Serial1.print("Received from pi :");
  Serial1.println(cmd);
  if(cmd.substring(0,1)=="r"){
    byte id = cmd.substring(1,3).toInt();
    byte state = cmd.substring(3,4).toInt();
    setState(id,state);
    writeState(id);
  }else if(cmd.substring(0,1)=="q"){
    sendState(3,1);
  }
}
