# disabling python warnings
import warnings

# mysql
import mysql.connector
from mysql.connector import Error

# ignore warnings
warnings.filterwarnings("ignore")


channels = [
    # 'https://t.me/snipersahamyab',
    'snipersahamyab',
    'ChanelVIP20',
    'grouprezabourse',
    'signalle_bartare_bours',
]


# conntect to database
try:
    connection = mysql.connector.connect(host='localhost',
                                         port='8889',
                                         database='stocks',
                                         user='root',
                                         password='root',
                                         # use_unicode='true',
                                         # charset='utf8mb4_general_ci',
                                         )
    if connection.is_connected():
        db_Info = connection.get_server_info()
        print("Connected to MySQL Server version ", db_Info)

        # create tables
        mySql_Create_Table_Query = """CREATE TABLE messages ( 
                             id int(11) NOT NULL AUTO_INCREMENT,
                             message_id INT(11) NULL DEFAULT NULL,
                             reply_to_message_id INT(11) NULL DEFAULT NULL,
                             channel_id INT(11) NULL DEFAULT NULL,
                             channel_name varchar(250),
                             message TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
                             found_namads TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
                             found_companies TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
                             sentiment_label TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
                             signal_label TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
                             views INT(11) NULL DEFAULT NULL,
                             forwards INT(11) NULL DEFAULT NULL,
                             replies INT(11) NULL DEFAULT NULL,
                             published_at TIMESTAMP NULL DEFAULT NULL,
                             created_at TIMESTAMP NULL DEFAULT NULL,
                             params MEDIUMTEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
                             PRIMARY KEY (id)) """

        cursor = connection.cursor()
        cursor.execute(mySql_Create_Table_Query)
        record = cursor.fetchone()
        print("You're connected to database: ", record)

except Error as e:
    print("Error while connecting to MySQL", e)
finally:
    if connection.is_connected():
        cursor.close()
        connection.close()
        print("MySQL connection is closed")
