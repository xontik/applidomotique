import sys
import serial
import time
import MySQLdb
 
ser = serial.Serial('/dev/ttyACM1', 115200, timeout=None)

while True :
	data_raw = ser.readline()
	db = MySQLdb.connect(host="localhost",# your host, usually localhost
 											user="root", # your username
 											passwd="root",# your password
 											db="domotik")# name of the data base
	cur = db.cursor()
	if(data_raw[0:1]=="z"):
		print("Initialisation ta mere ------------")
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

			if(row[3]=="on"):
				state="01"
			else:
				state="00"
			pulsed = row[9]
			h = "maj"+rang+pin+pinout+nc+pulsed+state+":"
			print(h)
			ser.write(h)
			time.sleep(0.1)
		ser.write("end:")
	else:
		num = data_raw[1:3]
		state = data_raw[3:4]
		print("num: ")
		print(num)
		print("state: ")
		print(state)
		if(state=="1"):
			s = "on"
		else:
			s = "off"
		r = "UPDATE devices SET state='"+s+"' WHERE id='"+num+"'"
		print(r)
		try:
			if(cur.execute(r)>0):
				db.commit()
		except:
			print("erreur bdd")
		cur.close()
		db.close()

