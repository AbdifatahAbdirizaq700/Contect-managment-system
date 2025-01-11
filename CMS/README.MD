group members :
Liiban maxamed nuur c1211038
Zakriye Bashir maxamuud c1210088
C/kaafi xasan xuseen C1211025
Abdifatah Abdirizaq Abdullahi C121036


The CMS serves as a comprehensive solution for managing contacts and users with different access levels. It implements secure user authentication, detailed activity tracking, and efficient contact management. The system provides administrators with tools to monitor user activities, manage user accounts, and maintain system security. Regular users can manage their contacts while admins have additional capabilities for system oversight and user management.


how to run the system :

1 : Database Setup:
Open phpMyAdmin (http://localhost/phpmyadmin)
- Create new database named 'cms_db'
- Import the provided database.sql file

2 : run the system 

http://localhost/CMS/login.php

admin login :
Username: admin
Password: admin
or create new user 



if admin not login update the user database :

{  UPDATE users SET is_admin = 1 WHERE username = 'admin'; }
