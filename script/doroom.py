import MySQLdb
import sys
import serial

db = MySQLdb.connect(host="localhost",    # your host, usually localhost
                     user="root",         # your username
                     passwd="root",  # your password
                     db="domotik")        # name of the data base

# you must create a Cursor object. It will let
#  you execute all the queries you need
cur = db.cursor()
ser = serial.Serial("/dev/ttyACM1", 115200)
# Use all the SQL you like
cur.execute("SELECT d.id FROM devices d INNER JOIN rooms r ON r.id=d.room WHERE r.name='"+sys.argv[1]+"'")

# print all the first cell of all the rows
for row in cur.fetchall():
    if(row[0]<10):
        s = "r0" + str(row[0]) + sys.argv[2] + ":"
    else:
        s = "r" + str(row[0]) + sys.argv[2] + ":"
    try:
    	ser.write(s)
    except:
    	print "error at i = " + str(row[0])
    	sys.exit()

db.close()