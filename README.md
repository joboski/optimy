# optimy
Blogging Application

This blog application mimic is built from scratch putting in considerations the scaleability and best practices. Composer was use for autoloading the files to ease up in the development.
To instantiate the application. run the db_migration file. This file will create a table migrations and execute the files inside the migrations folder.
Inside the migrations folder are php code for creating tables on UP() function and maintenance of table on DOWN(). 

Tables should be created incrementally by adding files inside the migrations folder which mimics the liquibase. Sessions object is for persisting the user on all pages.

Thats it :D happy new year!
