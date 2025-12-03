# Project Task Manager

### Members:
- Kedith Wuensche
- Ethan Turchyn
- Jordan Kober

### Description:
We created a Trello-like app that serves as a helpful tool for a variety of different uses, from keeping track of personal to-do's, to working collaboratively on large projects with many members. A secure login page leads to the user's personal dashboard where a list of boards are conveniently displayed. Clicking on a board takes the user to a new page that displays that board's tasks, organized into specified cards. User's are owners of the boards they create and they can also invite other user's to join their boards as members.


## How to run 

### Requirements 
```bash
• PHP 8.4 installed
• mySQL 9.5 installed
```
##

### Instructions 
1. Open a terminal and change to the project directory:

```bash
cd /path/to/your/COSC213-Project 
ls -l
```

2. Run this SQL in **phpMyAdmin** or the MySQL terminal:

```bash
mysql -u root -p < schema.sql 
```

```bash
(Optional: If db connection fails) :
ALTER USER 'root'@'localhost' IDENTIFIED BY 'root';
```

3. Start the built-in PHP server on port 8000:
```bash
php -S localhost:8000
```
You should see a message like: `PHP Development Server started at http://localhost:8000`

4. Open the app in your browser:
```bash
`http://localhost:8000/public/login.php
```

4. Login with admin credentials: 
```bash
Username : AdminUser
Password : 12345678

&

Username : Collaborator
Password : 12345678
```
---
