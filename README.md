# 🧑‍💼 User Management System

A sleek and responsive web application built using **PHP** & **HTML**, **CSS** and **MySQL** that allows you to manage a list of users with modern UI components and interactive features.

<img width="1918" height="904" alt="image" src="https://github.com/user-attachments/assets/035b1046-70dd-4fc9-b76b-9ec94787bcac" />


## 🚀 Features

- 👤 **Add New Users**: Input user name and age using a modern form.
- 🔄 **Toggle User Status**: Activate or deactivate users in one click.
- ❌ **Delete Users**: Remove unwanted entries with confirmation.
- 📊 **Stats Overview**: See real-time counts of total, active, and inactive users.
- ✨ **Modern Design**: Responsive, animated, and stylish UI using pure HTML & CSS with Font Awesome.

## 📂 Project Structure

```bash
project-root/
│
├── index.php             # Main file for displaying and handling user operations
├── toggle_app.sql        # SQL file to create and set up the database (optional)
├── preview.png           # (Optional) Screenshot of the app UI
```

🛠️ Technologies Used
- PHP (8.x)

- MySQL

- HTML5 & CSS3

- Font Awesome 6

- Google Fonts – Inter

# 🔧 Quick Setup Instructions

Follow these steps to run the **User Management System** on your local machine using PHP and MySQL.

---

## ✅ Requirements

- PHP ≥ 7.4 (preferably 8.x)
- MySQL or MariaDB
- XAMPP / WAMP / MAMP / or manual setup
- Browser (e.g. Chrome)

---

## 🛠 Step-by-Step Guide

### 1. 📁 Place the Files

Extract or clone the project into your server root directory:

- For **XAMPP** → `C:\xampp\htdocs\UserManagementSystem`
- For **WAMP** → `C:\wamp64\www\UserManagementSystem`

Or run it anywhere if using `php -S` (built-in server).

---

### 2. 🧱 Create the Database

Open **phpMyAdmin** or MySQL CLI and run the following to create the database and table:

```sql
CREATE DATABASE toggle_app;

USE toggle_app;

CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  age INT NOT NULL,
  status TINYINT DEFAULT 1
);
```
✅ You can name the database something 

### 3. ⚙️ Configure Database Connection

In index.php, ensure the database configuration matches your local setup:
`$conn = new mysqli("localhost", "root", "", "toggle_app");`

- localhost → Your MySQL host

- root → Your MySQL username

- "" → Your MySQL password (empty by default in XAMPP)

- toggle_app → Your database name

### 4. ▶️ Run the App

- Option A: Using XAMPP/WAMP
Start Apache and MySQL
Visit in your browser:
http://localhost/UserManagementSystem/

- Option B: Using PHP Built-in Server
From the terminal:
cd path/to/UserManagementSystem
php -S localhost:8000

Then open:
http://localhost:8000
