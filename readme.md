


## Pre Requisites

- Make sure to have composer on your system.
- Check the server requirement for the setup in your local machine in XAMPP or WAMP
    - PHP >= 7.1 
    - BCMath PHP Extension 
    - Ctype PHP Extension 
    - Fileinfo PHP extension 
    - JSON PHP Extension 
    - Mbstring PHP Extension 
    - OpenSSL PHP Extension 
    - PDO PHP Extension 
    - Tokenizer PHP Extension 
    - XML PHP Extension
- Create a DB for this project, copy the details which will be used in the installation process.

## Tech Stack

- Laravel
- Sqlite
- JWT Authentication
- Eloquent ORM
- Entrust ACL Package
- Laravel Sluggable Package

## Steps to follow to Install Laravel Project.

- First step is to, clone this project.
  Once cloning of the project is done. Go to root of this project and Open terminal to 
- Run Command "composer install"
- Open .env file and copy .env.example file to .env file.
- using sqlite , so no need to worry about mysql installation.file location: (db file avilable on database/database.sqlite)
- SQLITE Viewer https://inloop.github.io/sqlite-viewer/  - helps to view the database schema and details stored on database

Once done with the changes, Open terminal again to
- Run Command "php artisan migrate:refresh --seed"  - It helps to create table add sample entries
- Run Command "php artisan serve" - It helps start the php server
- If ypu are having issue port run these command ( "php artisan serve --port=8001" as change API end point url on postman collections).

Now you are ready to excute the API collection.

- if u have postman - you can import the collections files and do the api verification

## Work Flow:

User:

- Log In using API POST: http://localhost:8001/api/v1/auth/login </br>
    User logs in to the system, using credentials, used JWT authentication system. </br>
    On successfull login, Bearer token is generated, which is used to access further APIs.
- Role based Access have been used on the routes, via entrust package - it handles roles and permission on controller functions level.

- User applies for loan filling the details(). API POST: http://localhost:8000/api/v1/loan
- On success of saving the application with status "0"- pending, the application goes to Admin.
- And it is visible in the Loan Application list (both admin and user) (Only current users loans list will be displayed). 
- Once loan approved users can able do the repayments  API POST : http://localhost:8000/api/v1/loan/repayment/pay
- Get the detail view of the loan API GET : http://localhost:8001/api/v1/loan/{slug} 
- check the next repayment due API GET : http://localhost:8001/api/v1/loan/repayment/next

Admin:
- Log In http://localhost:8001/api/v1/auth/login
    Admin logs in to the system, using credentials.
- Goes to list of Applications: http://localhost:8001/api/v1/loan

- Based on the status(1-approved,3-rejected)  approve and reject the loan with field on
API POST : http://localhost:8001/api/v1/loan/{slug} 

- On Approval of application, weekly repayment records have been generated for that particular loan application.

Example 1;</br> Loan of amount: 6000 INR,</br> for a tenure of 10 weeks.</br>
We will generate, 10 week repayment records.</br>
Each week user have to pay 6000/10 ie; 600 INR.</br>
Example 2;</br> Loan of amount: 10000 INR,</br> for a tenure of 12 weeks.</br>
We will generate, 12 week repayment records.</br>
Each week user have to pay 10000/10 ie; 833.33 INR.</br>
if round of the value we have 4 was pending after finishing last payment. i have did the fix for when last repayment entry , get the total loan amount -all paid amount = last repayment amount</br>

In the response I am also calculating "remaining balance" after each payment and updating user_loans and user_loan_repayments table.</br>


## Credentials:
As per Seeder.

Admin Credentials:

Email: admin@gmail.com
Password: Admin@123

User Credentials:

User 1: 
    Email: customer@gmail.com
    Password: Welcome@123

User 2: 
    Email: customer1@gmail.com
    Password: Welcome@123

User 3: 
    Email: customer2@gmail.com
    Password: Welcome@123

## Postman collections & Unit test cases avoid fatal errors:
- In root folder check the filename called LocalTest.postman_collection.json

## Schema file
- In root folder check the filename called dump.sqlite.sql

## Flow Diagram With API end point

- In root folder check the filename called flow-diagram.png

## Technical Document

- In root folder check the filename called https://docs.google.com/document/d/1uqkJCxAhNkzkvJ5zhsAPtk8jK-J4-xqk88UGZ7s4oxM/edit?usp=sharing


## Unit Testing

- run this command for create the tables on test database
    - Run command "php artisan migrate:refresh --seed"
- Due to Sqlite DB lock problem run the file one by one
    - Run command "composer test  tests/Unit/UserTest"
    - Run command "composer test  tests/Unit/LoginTest"
    - Run command "composer test  tests/Unit/LoanInitalTest"
    - Run command "composer test  tests/Unit/LoanRepaymentTest"
- covered all the test cases mostly
- If needed we can use separate DataBase but not yet configured
     - php artisan migrate:refresh --seed --env=testing



## Note : ******whenever run the test cases please run migrate command first recommended*****************
