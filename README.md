# johnDo
Get your tasks done with johnDo!
## hosting

The main johnDo instance is currently only available locally on my dev computer. To setup your own johnDo on a LAMP server, enter the following to setup the database, change the password in config-example.php, and clone the repo to your httpd directory.

    CREATE DATABASE johndo; USE johndo; CREATE TABLE users (id INT NOT NULL AUTO_INCREMENT PRIMARY KEY, email TEXT, password TEXT) character set utf8;
    CREATE TABLE reminders (id INT NOT NULL AUTO_INCREMENT PRIMARY KEY, title TEXT, more TEXT, date TEXT, user TEXT) character set utf8;
