# Expense Tracker

A web-based Expense Tracker application that helps users manage their daily expenses, track spending habits, and maintain budgets efficiently.

## Features

- User Registration and Login
- Secure Authentication
- Add Income Records
- Add Expense Records
- Categorize Expenses
- View Transaction History
- Budget Management
- Dashboard with Expense Summary
- MySQL Database Integration
- Responsive User Interface

## Technologies Used

### Frontend
- HTML5
- CSS3
- JavaScript

### Backend
- PHP

### Database
- MySQL

### Server
- XAMPP / Apache Server

---

## Project Structure

```
expense-tracker/
│
├── css/
├── js/
├── images/
├── database/
│   └── expense_tracker.sql
├── dashboard.php
├── login.php
├── register.php
├── add_expense.php
├── add_income.php
├── logout.php
├── config.php
└── README.md
```

---

## Prerequisites

Before running the project, ensure the following software is installed:

- PHP (Version 8.0 or above)
- MySQL Server
- XAMPP/WAMP/LAMP
- Web Browser (Chrome, Firefox, Edge)

---

## Installation Guide

### Step 1: Clone the Repository

```bash
git clone https://github.com/your-username/expense-tracker.git
```

Or download the ZIP file and extract it.

---

### Step 2: Move Project to Server Directory

For XAMPP:

```text
C:\xampp\htdocs\
```

Place the project folder inside the `htdocs` directory.

Example:

```text
C:\xampp\htdocs\expense-tracker
```

---

### Step 3: Start Apache and MySQL

1. Open XAMPP Control Panel.
2. Start:
   - Apache
   - MySQL

---

### Step 4: Create Database

1. Open phpMyAdmin:

```text
http://localhost/phpmyadmin
```

2. Create a new database:

```sql
expense_tracker
```

---

### Step 5: Import Database

1. Select the created database.
2. Click **Import**.
3. Choose:

```text
database/expense_tracker.sql
```

4. Click **Go**.

---

### Step 6: Configure Database Connection

Open:

```php
config.php
```

Update database credentials:

```php
<?php
$host = "localhost";
$username = "root";
$password = "";
$database = "expense_tracker";

$conn = mysqli_connect(
    $host,
    $username,
    $password,
    $database
);
?>
```

---

### Step 7: Run the Application

Open browser and navigate to:

```text
http://localhost/expense-tracker
```

---

## Usage

### Register

Create a new account using the registration page.

### Login

Login using registered credentials.

### Add Income

Record monthly or daily income.

### Add Expenses

Add expenses under different categories.

### Track Spending

View and analyze transaction history.

### Manage Budget

Set budgets and monitor spending limits.

---

## Database Schema

Main Tables:

- users
- income
- expenses
- categories
- budgets

---

## Future Enhancements

- Expense Analytics Charts
- Export Reports (PDF/Excel)
- Email Notifications
- Budget Alerts
- Mobile Responsive Design
- Dark Mode
- Multi-Currency Support

---

## Screenshots

Add screenshots of:

- Login Page
- Registration Page
- Dashboard
- Expense Form
- Reports Page

---

## Author

Felixeena Thomas

Bachelor of Engineering (Information Technology)

---

## License

This project is developed for educational and learning purposes.
