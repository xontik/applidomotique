import serial
import sys
import string
import time 

com = serial.Serial('/dev/ttyACM1', baudrate=115200)

#print "id="+sys.arg[1]+" state="+sys.arg[2]
try:
 h = "r"+sys.argv[1]+""+sys.argv[2]+":"
 com.write(h)

 print h

except:
  print "Unexpected error:", sys.exc_info()
  sys.exit()

