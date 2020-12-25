# Problems and possible issues

## Issues

** General Issues **

- Too many code repetition on the PHP and HTML files.
- Tightly coupled codes, controller and model and view is written on a single file
- Unreuseable codes.
- Unscalable, has no proper scaffolding.
- Hard to maintain since changes on the code would easily break the application
- No error checking and can be hack through sql injection on the form inputs. 

** Database side **

- Database connection credential is exposed to the code and potential vulnerable to exploits.
- Connecting to database using `mysqli_connect` restricts the application into using MySQL only.
- No Try-Catch when doing database connection.
- There is no abstraction when connecting to database hence changing database product would mean re-writing almost all the codes.
- No separation between object and persistence data.
- Processing data without any validation


** PHP code side **

- Code should be broken down into controller, service, model and views
- Input data provided from form submission is not validated hence could be a potential source of attack
- Improper routing to paths resulting to broken link
- Using mysqli to connect inside the database is good if and only if we are not going to change to other type of database like MSQL, NoSQL etc. 
- Code was closely coupled and hard to maintain

** HTML side **

- Repeated HTML code and broken links
- The page is broken when viewed in mobile
- There is no action on the form to process its data after clicking submit
- No front-end validation of data prior to submission


## Suggested probable solutions

** Without using any framework **

- Have single file for configuration of the application to easily exchange the file to reflect settings for local dev site, test site and production site. It is better if we can hide this settings to the application itself by placing it above the root folder.

> Create an .env file for all the configuration and place it outside the app so it won't be shipped out with the application. 

: mysql.env file

```
; This is the configuration for my own test environment. Changed the values as necessary.
mysql_host = 127.0.0.1
mysql_user = 'root'
mysql_pass = 'Abcd1234'
mysql_dbname = 'exam'

;debug
enable = true
error_level = E_ALL ;equivalent to E_ALL

;possible requirements
phpversion[] = "5.6"
phpversion[] = "7.0"

``` 
then parse this file on the `init.php` which is located inside the root folder.


: init.php

```
<?php 

$env = parse_ini_file('../mysql.env', true);

$GLOBALS['config'] = array(
    'mysql' => [
        'host' => $env['mysql_host'],
        'user' => $env['mysql_user'],
        'pass' => $env['mysql_pass'],
        'dbname'   => $env['mysql_dbname']  
    ],
);
```

- Create folder structures that would separate files according to concerns. All controllers should go to controllers folder, all files that would interact to database should go to model folders and so on.

: Folder structure
---
   |.env
   |__app
        |__index.php
        |__init.php
        |__.htaccess
        |
        |__controllers
        |      |__articleController.php
        |      |__userController.php
        |      
        |__interfaces
        |       |__Database.php
        |
        |__models
        |       |__Blog.php
        |       |__User.php
        |
        |__repositories
        |       |__BlogRepositories.php
        |       |__UserRepositories.php
        |
        |__services
        |       |__Config.php
        |       |__Mysqldb.php
        |       |__Url.php
        |       |__Route.php
        |       |__Validation.php
        |       |__Session.php
        |       |__Token.php
        |
        |__www
            |__css
            |__js
            |__uploads
            |__views
---

- Use PHP Data Object (PDO) to abstract the connection to database
    
    `$pdo = new PDO($dsn, $user, $pass);`

- decouple the database connection.

: In order to prepare the application to be able to use other services aside from MySQL, we need to redesign our database into an interface. Thes database as an interface should be implemented by whatever database product chosen for the application. In this particular example, we are using MySQL hence we will going to implement the Database interface in the file called `mysqldb.php`.If for example we need to change the product to mongoDB then just create a `mongodb.php` and we don't need to change anything to our written code since we make our db as an interface. Now we can inject the interface to the class without caring if we are using mysql, oracle or nosql. 


Create a model to emulate what fields are on the table.

