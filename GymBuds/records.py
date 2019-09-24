from pip._internal import main
main(['install','mysql-connector-python-rf'])
import mysql.connector

mydb = mysql.connector.connect(
  host="",
  port = "",
  database="",
  user = "",
  password="",
)

mycursor = mydb.cursor()

sql = 'INSERT INTO users (idUsers, first_name, last_name, user_name, email, password) VALUES (%s, %s, %s, %s, %s, %s)'
val = ("", "", "", "", "", "")
mycursor.execute(sql, val)

mydb.commit()

print(mycursor.rowcount, "record inserted.")