# Software Engineering - Group 4
Software Engineering Spring 2016 Project

## Project Summary
This project is an effort in utilizing Software Engineering to create a website similar to the popular [Linkedin](https://www.linkedin.com/). We will accomplish this by using the Software Engineering methodology learned in class, by researching, designing, and implementing solutions to solve the problem. We will also be using the Agile methodology of breaking up the larger problem into multiple Sprints and completing the typical Software Development cycle in each Sprint.  

## Group Members
* Quinton Miller
* Zach Hogan
* Keenan Shumard
* Jordan Jones
* Austin Dotto


## Software
* Database
    * MySQL
* Scripting Language
    * PHP
* Client Side Language
    * JavaScript
    * JQuery
    * D3.js
* Front End Framework
    * Bootstrap
* Operating System
    * Ubuntu 14.04

## Installation

### Prerequisites:
* Ubuntu Server (14.04 used)
* SSH access
* Ability to run commands with sudo privileges

### Steps:
1. Log into server with SSH
    * `ssh USERNAME@SERVERIP`
2. Update package lists
    * `sudo apt-get update`
3. Install the LAMP (Linux, Apache, MySQL, PHP) requirements
    * `sudo apt-get install lamp-server^`
4. Install git
    * `sudo apt-get install git`
5. Change directory to `/var/www/html`
    * `cd /var/www/html`
6. Clone the repo
    * `git clone https://github.com/zrhkc7/SWEGroup4.git .`
7. Create the database
    * `mysql -p -u USER_NAME SWEGroup4 < installation/schema.sql`
        * Replace `USER_NAME` with your MySQL username
8. Fill out the database configuration file
    * `sudo vim db.php`
    * Fill out the `$db_user` and `$db_pass`
    * Write and quit vim
