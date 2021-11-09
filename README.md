# kapweb

CMS made too create easly and quickly a new website.

## INSTALLATION

To install kapweb you just need to clone this repo in your website folder and run it.

## START WITH KAPWEB

There is 4 important steps to use kapweb.

#### Step 1 : Connect kapweb to your database

Connect kapweb to your database by the website.
Without Mysql database, kapweb does not work.
On the website you have to input : database host, database user and database password.

#### Step 2 : Connect with empty database or create a new one

After the connection, kapweb needs to know which database get.
There is 2 solutions :
- Connect kapweb with a new database
- Create a new database (enter a name of new database)

#### Step 3 : Create super user account

If kapweb is correctly connect, tables will be automaticly generate. So you just need to create a new super user account. You have to enter your first name, last name, email, password and pseudo.

#### Step 4 : Valid your super user account

If your website support mail() function from PHP, then you will receive a mail to confirm the super user account.
If your website does not support the mail() function, then you have to follow what will write behind :
- You have to go in the database and select the table ```kp_mailconfirm```
- You must copy the value of ```cid ```and paste it in url bar like that : \[mwebsite url\]/KW/confirmemail/\[cid\]
- Then you just have to confirm your email

