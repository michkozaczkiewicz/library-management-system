# Library management system
## Table of Contents
- [About the Project](#about-the-project)
- [Built With](#built-with)
- [Features](#features)
- [Installation](#installation)
- [Screenshots](#screenshots)

## About The Project
Simple library management system. Admin has all the features including adding a new library employee. Useres can register themselves, and check their issues and status.
## Built With
- Back End - **PHP** 
Framework: **Symfony**
- Database - **MySql**

## Features
Admin user of the library can create librarians, create and edit authors, genres, publishers and books. 
Admin user also have the ability to manage book issues.
Librarians have the ability to create book issues and manage return of book issues.
Users have the ability to check their issues and edit profiles.

## Installation
1. Clone the repository into your htdocs folder
```
$ git clone https://github.com/michkozaczkiewicz/library-management-system.git
```
2. Create database and configure `.env` file, so it has correct database name
```
    DATABASE_URL=mysql://root:@127.0.0.1:3306/database_name
```
3. Intall dependencies
```
$ composer install
```
4. Migrate database and load admin user
```
php bin/console doctrine:migrations:migrate
php bin/console doctrine:fixtures:load
```
5. Start server, and run application
```
symfony server:start
```
6. Use those credentials to log as admin:
```
    username: admin
    password: admin
```
## Screenshots
![Alt text](https://github.com/michkozaczkiewicz/library-management-system/blob/master/public/assets/images/admin_issueslist2.png?raw=true "Admin_issues_list")
![Alt text](https://github.com/michkozaczkiewicz/library-management-system/blob/master/public/assets/images/admin_issue.png?raw=true "Admin_create_issue")
