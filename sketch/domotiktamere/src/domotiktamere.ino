#define NB_DEVICES 25
#define dev(x) devices[x]

String cmd;
unsigned long time;
int devicesConnected;
typedef struct Device{
  int pin;
  int pinout;
  bool pulsed;
  bool normallyClosed;
  int state;
};
Device devices[NB_DEVICES];



void setup() {
  
  Serial.begin(115200);
  Serial1.begin(115200);
  Serial1.println("Starting...");
  Serial.println("z");
  Serial1.println("Wait for data to be initialised ...");
  serverreceive();
  while(cmd.substring(0,1)!="n"){
    while(!serverreceive()){
      delay(1);
    }
  }
  Serial1.print("Data inocmming ...");
  devicesConnected = cmd.substring(1,3).toInt();
  while(cmd.substring(0,3)!="end"){
    while(serverreceive()){
      command();
      Serial1.print(".");
    }
  }
  Serial1.println("Done.");
  
  
 
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
void tgglState(int i){
   dev(i).state = 1 - getState(i); 
   Serial1.print("Toggling : ");
   Serial1.print(i);
   Serial1.print(" as ");
   Serial1.println(dev(i).state);
   
   if(dev(i).normallyClosed){
      Serial1.println("///// NORMALY CLOSED --- PRISE");
      digitalWrite(dev(i).pin,dev(i).state);
   }else if(dev(i).pulsed){
      digitalWrite(dev(i).pin,LOW);
      delay(100);
      digitalWrite(dev(i).pin,HIGH);
      Serial1.println("///// PULSED --- TELERUPTEUR");
   }else{
     digitalWrite(dev(i).pin,!digitalRead(dev(i).pin));
     Serial1.println("///// VA ET VIENT");
   }
   
   Serial1.print("New state toggled omfg :");
   Serial1.println(dev(i).state);
   Serial1.print("Level on pinpinback ");
   Serial1.println(digitalRead(dev(i).pinout));
   Serial1.print("Level on relay ");
   Serial1.println(digitalRead(dev(i).pin));
   
}



void sendState(int id, int state){
  Serial.print("c");
  
  if(id<10){
    Serial.print("0");
  }
  Serial.print(id);
  Serial.println(state);
  
  Serial1.println("Sending ..");

}
void checkNewState(){
  for(int i =1;i<devicesConnected;i++){
    int s = getState(i);
        
    if(dev(i).state != s){
      
         Serial1.print("Compare states of dev :");
        Serial1.println(i);
        Serial1.print("Actual state : ");
        Serial1.print(dev(i).state);
        Serial1.print(" state read at pin ");
        Serial1.print(dev(i).pinout);
        Serial1.print(" : ");
        Serial1.println(s);
        dev(i).state = s;
       
       sendState(i,s);
    }
  }
}
      


void loop() {
  if(serverreceive()){
    command();
  }
  if (Serial1.available() > 0) {
    for(int i =1;i<devicesConnected;i++){
     printDev(i);
    }
    Serial1.read();
  }
  if(millis() - time > 1000){
    time = millis();
    checkNewState();
  }
  
  
}
bool serverreceive() {
  if (Serial.available() > 0) {
    cmd = Serial.readStringUntil(':');
    return true;
  }
  return false;
}
void printDev(int i){
 Serial1.print("--------Device ");
 Serial1.print(i);
 Serial1.println("------");
 Serial1.print("Pin :");
 Serial1.print(dev(i).pin);
 Serial1.print(" Pinout :");
 Serial1.print(dev(i).pinout);
 Serial1.print(" Pulsed :");
 Serial1.print(dev(i).pulsed);
 Serial1.print(" NC :");
 Serial1.print(dev(i).normallyClosed);
 Serial1.print(" State :");
 Serial1.println(dev(i).state);
  
}
void command(){
  Serial1.print("Received from pi :");
  Serial1.println(cmd);
  if(cmd.substring(0,1)=="r"){
    byte id = cmd.substring(1,3).toInt();
    byte state = cmd.substring(3,4).toInt();
    if(state != getState(id)){
      tgglState(id);
    }
  }else if(cmd.substring(0,1)=="t"){
    sendState(4,1);
  }else if(cmd.substring(0,3)=="maj"){
    int id = cmd.substring(3,5).toInt();
    dev(id).pin = cmd.substring(5,7).toInt();
    dev(id).pinout = cmd.substring(7,9).toInt();
    dev(id).normallyClosed = cmd.substring(9,11).toInt();
    dev(id).pulsed = cmd.substring(11,13).toInt();
    pinMode(dev(id).pin, OUTPUT);
    digitalWrite(dev(id).pin,HIGH);
    pinMode(dev(id).pinout, INPUT);
    dev(id).state = cmd.substring(13,15).toInt();

  if(dev(id).normallyClosed){
      digitalWrite(dev(id).pin,dev(id).state);
   }else if(!dev(id).pulsed){
     digitalWrite(dev(id).pin,1-dev(id).state);
   }
  }
  else if(cmd.substring(0,1)=="n"){
    devicesConnected = cmd.substring(1,3).toInt();
  } 
  
}
