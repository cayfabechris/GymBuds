from pip._internal import main
#main(['install','mysql-connector-python-rf'])
import mysql.connector
import os
import uuid
import cgi

form = cgi.FieldStorage()
first_name =  form.getvalue('first-name')
last_name =  form.getvalue('last-name')
username =  form.getvalue('username')
email =  form.getvalue('email')
password =  form.getvalue('password')

h = os.environ.get('HOST')
po = os.environ.get('PORT')
d = os.environ.get('DATABASE')
u = os.environ.get('USER')
p = os.environ.get('PASSWORD')

#print(p)
#print(po)
#print(d)
#print(u)
#print(p)

pa = str(p)

mydb = mysql.connector.connect(
  host= h,
  port = po,
  database= d,
  user = u,
  password = pa,
)

mycursor = mydb.cursor()

val = [first_name, last_name, username, email, password]
mycursor.execute("INSERT INTO gymbuds.user (first_name, last_name, user_name, email, password) VALUES (%s, %s, %s, %s, %s)", val)
#mycursor.execute("SHOW TABLES")
mydb.commit()
mydb.close()

#for db in mycursor:
  #print(db[0])

#for table in mycursor:
 # print(table[0])

#print(mycursor.rowcount, "record inserted.")