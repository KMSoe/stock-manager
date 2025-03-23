# Project Setup Guide

## Cloning the Repository

1. Open a terminal or command prompt.
2. Navigate to the directory where you want to clone the project.
   ```sh
   cd /path/to/your/directory
   ```
3. Clone the repository using Git:
   ```sh
   git clone https://github.com/KMSoe/stock-manager.git
   ```
4. change directory into the project directory:
   ```sh
   cd project_name
   ```


## Environment Configuration

Copy the `.env.example` file to create a new `.env` file:
   ```sh
   cp .env.example .env
   ```

   And replace the database credentials.


## Running the Application

   ```sh
   mysql -h localhost -u {MYSQL_USER} -p stock_manager_db < start.sql

   cd ./public/
   php -S localhost:8000
   ```

The project should now be up and running! <br/>
Access it via `http://localhost:8000` in your browser.

## Documentation

### ER Diagram
https://drive.google.com/file/d/1rPkLINC0N3qwvKrD4H9CEnJI3DgYOvYh/view?usp=sharing

```
project_folder/databse/ERD.png
```

## User Credentials

   ```
   # Admin
   admin@stockmanager.com
   Admin123!@#

   # User
   alice@stockmanager.com
   User123!@#
   ```

Thank you.

