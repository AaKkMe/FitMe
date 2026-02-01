# FitMe Gym Management System

A complete gym management system built with **plain PHP, vanilla JavaScript, and CSS** (no frameworks).

## Features Implemented

- **User Roles:** Admin, Trainer, Member (user)
- **Admin Panel:** User management, workout CRUD, attendance viewing, log attendance
- **Trainer Panel:** Manage own workouts, view registrations, log attendance
- **User Panel:** Browse workouts, register/unregister, view attendance history
- **Authentication:** Login, signup with validation
- **CRUD:** Full create, read, update, delete for users and workouts
- **Search:** Advanced search for users (name, email, role) and workouts (name, category, trainer, day)
- **Attendance Logger:** Check-in members (with optional workout), view by date
- **Security:** Prepared statements (SQL injection prevention), `htmlspecialchars` (XSS prevention), CSRF tokens
- **Ajax:** Live email validation on signup/edit, autocomplete member search when logging attendance

## Tech Stack

- PHP 7.4+
- MySQL 5.7+
- Plain HTML/CSS/JavaScript (no frameworks)

## Setup Instructions

### 1. Database Setup

1. Create the database and tables using phpMyAdmin or MySQL CLI:
   - Import or run the SQL in `database/schema.sql`

2. Update database credentials in `config/database.php`:
   ```php
   define('DB_HOST', 'localhost');
   define('DB_NAME', 'fitme_gym');
   define('DB_USER', 'your_username');
   define('DB_PASS', 'your_password');
   ```

### 2. Default Login Credentials

**All accounts use password: `password`**

See `CREDENTIALS.md` for complete list of demo accounts including:
- 1 Admin account
- 5 Trainer accounts (Ram Bahadur Thapa, Hari Prasad Sharma, Sita Kumari Gurung, Krishna Bahadur Rai, Maya Devi Tamang)
- 15 Member accounts (Bikash Shrestha, Anjali Thapa, Rajesh Karki, Sunita Adhikari, etc.)

**Quick Login:**
- **Admin:** admin@fitme.com / password
- **Trainer:** trainer@fitme.com / password
- **Member:** user@fitme.com / password

### 3. Hosting on School Server

1. Upload the entire `FitMe` folder to your web directory
2. Set the document root to the folder containing `index.php` (or use `public/` as document root)
3. Ensure `.htaccess` is allowed (for clean URLs) - if not, all links use `?page=` which works without mod_rewrite

### 4. Directory Structure

```
FitMe/
├── config/          # Database, constants
├── controllers/     # Auth, Admin, Trainer, User, Api
├── models/          # User, Workout, Attendance, Category
├── views/           # Layouts, auth, admin, trainer, user
├── includes/        # functions.php
├── database/        # schema.sql, seed_admin.php
├── assets/
│   ├── css/
│   └── js/
├── public/          # index.php (router), .htaccess
├── index.php        # Entry point
├── CREDENTIALS.md   # All login credentials
└── README.md
```

## MVC Architecture

- **Models:** `User`, `Workout`, `Attendance`, `Category` - database operations with prepared statements
- **Views:** PHP templates in `views/` with layouts
- **Controllers:** Handle requests, load models, render views

## Sample Data

The database includes realistic Nepali gym data:
- **21 Users:** 1 Admin, 5 Trainers, 15 Members
- **8 Workout Categories:** Cardio, Strength, Yoga, HIIT, CrossFit, Pilates, Zumba, Spinning
- **27 Workouts:** Scheduled throughout the week with various trainers
- **50+ Workout Registrations:** Members registered for different classes
- **30+ Attendance Records:** Past attendance data for demonstration

## Assignment Requirements Met

✅ **Tech Stack:** PHP + MySQL (no frameworks)  
✅ **MVC Architecture:** Proper separation of models, views, controllers  
✅ **CRUD Functionality:** Create, Read, Update, Delete for users and workouts  
✅ **Search Feature:** Advanced search with multiple criteria (role, category, trainer, day)  
✅ **Security:**  
  - SQL Injection: Prepared statements throughout  
  - XSS: htmlspecialchars() on all output  
  - CSRF: Token validation on forms  
✅ **Ajax Features:**  
  - Live email validation (checks if email exists)  
  - Autocomplete member search (when logging attendance)  
✅ **Hosted Ready:** Works on school servers with proper structure  

## Known Issues

- None at this time.

## Security Note

**IMPORTANT:** All demo accounts use password: `password`. 