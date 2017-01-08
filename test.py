import serial
import sys
import string
import time 
ser = serial.Serial('/dev/ttyACM0', baudrate=115200) 
print "gooooooo"
while True :
    try:
        ser.write("r130:")
	time.sleep(1)
	ser.write("r131:")
	time.sleep(1)
    except:
        print "Unexpected error:", sys.exc_info()
        sys.exit()

