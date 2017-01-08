import MySQLdb
import sys
import serial
import time

db = MySQLdb.connect(host="localhost",    # your host, usually localhost
                     user="root",         # your username
                     passwd="root",  # your password
                     db="domotik")        # name of the data base

# you must create a Cursor object. It will let
#  you execute all the queries you need
cur = db.cursor()
ser = serial.Serial("/dev/ttyACM1", 115200)
# Use all the SQL you like
nb =cur.execute("SELECT * FROM devices")
ser.write("n"+str(nb)+":")
# print all the first cell of all the rows
for row in cur.fetchall():
	if(row[0]<10):
		rang = "0"+str(row[0])
	else:
		rang = str(row[0])

	pin = row[6]
	pinout = row[7]
	nc= row[8]
	pulsed = row[9]
	if(row[3]=="on"):
		state="01"
	else:
		state="00"
	h = "maj"+rang+pin+pinout+nc+pulsed+state+":"
	print(h)
	ser.write(h)
	time.sleep(0.5)
ser.write("end:")

db.close()
