import sys
import time
import serial
import string
import MySQLdb
 
ser = serial.Serial("/dev/ttyACM1", 115200)
db = MySQLdb.connect(host="localhost",# your host, usually localhost
 											user="root", # your username
 											passwd="root",# your password
 											db="domotik")# name of the data base
cur = db.cursor()
nb =cur.execute("SELECT * FROM devices")
for i in range(1,nb+1):
    if(i<10):
        s = "r0" + str(i) + sys.argv[1] + ":"
    else:
        s = "r" + str(i) + sys.argv[1] + ":"
    try:
    	ser.write(s)
    except:
    	print "error at i = " + str(i)
    	sys.exit()
    time.sleep(0.1)


    
        
