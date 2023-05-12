import MySQLdb

db = MySQLdb.connect(host="mysql.studev.groept.be",  # your host, usually localhost
                     user="a22web32",  # your username
                     passwd="8PWOhcGt",  # your password
                     db="a22web32")  # name of the data base

cur = db.cursor()


cur.execute("INSERT INTO book (author, title, isbn, book_cover, summary) VALUES (%s, %s, %s, %s, %s)",
            (author, title, isbn, cover, summary))
db.commit()