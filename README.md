# Smart Leave Management System

A modern full-stack web application built with **Laravel** for managing employee leave operations digitally. Designed for hospitals, clinics, nursing organizations, and companies.

## Features

### Admin Panel
- **Dashboard** with statistics cards and analytics charts (Chart.js)
- **Employee Management** – Create, edit, delete, reset passwords
- **Department Management** – CRUD operations
- **Leave Type Management** – 13 leave types with configurable default days
- **Leave Requests** – View, approve, reject with filters (status, type, employee, department, date range)
- **Leave Balance** – Add balance individually or to all employees at once
- **Leave Calendar** – Visual calendar view using FullCalendar.js
- **Complaint Management** – View, update status, reply (with 8 complaint categories)
- **Reports & Export** – Filtered reports with CSV export
- **Activity Logs** – Track all system actions

### Employee Panel
- **Dashboard** with leave balance overview and recent requests
- **Apply for Leave** – Select type, dates, reason, upload documents
- **Leave History** – View all submitted requests with status tracking
- **Leave Balance** – View remaining days with usage progress bars
- **Complaints** – Submit and track complaints by category
- **Notifications** – Receive updates on leave approvals/rejections

### REST API (Sanctum)
| Method | Endpoint | Description |
|--------|----------|-------------|
| POST | `/api/login` | Login and get token |
| POST | `/api/reset-password` | Reset password |
| POST | `/api/leave/apply` | Apply for leave |
| GET | `/api/leave/history` | Leave history |
| GET | `/api/leave/balance` | Leave balance |
| GET | `/api/leave/{id}` | Leave status |
| POST | `/api/complaint` | Create complaint |
| GET | `/api/complaint/{id}` | Complaint status |

## Tech Stack

| Layer | Technology |
|-------|------------|
| Backend | PHP Laravel 13 |
| Frontend | Blade, Bootstrap 5, JavaScript, AJAX |
| Database | MySQL |
| API Auth | Laravel Sanctum |
| Charts | Chart.js 4 |
| Calendar | FullCalendar 6 |
| Icons | Bootstrap Icons |

## Requirements

- PHP >= 8.3
- Composer
- MySQL
- Node.js & NPM (for Vite assets)

## Installation

### 1. Clone the repository

```bash
git clone <repository-url>
cd smart-leave-system
```

### 2. Install PHP dependencies

```bash
composer install
```

### 3. Environment setup

```bash
cp .env.example .env
php artisan key:generate
```

### 4. Configure database

Open `.env` and set your database credentials:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=smart_leave_system
DB_USERNAME=root
DB_PASSWORD=
```

Then create the database:

```sql
CREATE DATABASE smart_leave_system;
```

### 5. Run migrations and seeders

```bash
php artisan migrate --seed
```

This will create all tables and seed:
- **Admin account** – `admin@admin.com` / `password`
- **13 Leave Types** – CL, SL, EL, ML, PL, AL, EML, BL, CO, LWP, STL, PHL, QL

### 6. Storage link

```bash
php artisan storage:link
```

### 7. Install frontend dependencies

```bash
npm install
npm run build
```

### 8. Start the server

```bash
php artisan serve
```

Visit: **http://localhost:8000**

## Quick Start with Laragon

1. Place the project folder in `C:\laragon\www\`
2. Start Laragon (Apache + MySQL)
3. Create database `smart_leave_system` via HeidiSQL or phpMyAdmin
4. Open terminal and run:
   ```bash
   cd C:\laragon\www\smart-leave-system
   composer install
   cp .env.example .env
   php artisan key:generate
   php artisan migrate --seed
   php artisan storage:link
   npm install && npm run build
   ```
5. Visit: **http://smart-leave-system.test** or **http://localhost/smart-leave-system/public**

## Default Login

| Role | Email | Password |
|------|-------|----------|
| Admin | `admin@admin.com` | `password` |

> After login, create employee accounts from the Admin dashboard.

## Database Structure

| Table | Description |
|-------|-------------|
| `users` | Admins and employees with role-based access |
| `departments` | Organization departments |
| `leave_types` | Types of leave (CL, SL, EL, etc.) |
| `leave_requests` | Employee leave applications |
| `leave_balances` | Yearly leave balance per employee per type |
| `complaints` | Employee complaints with categories |
| `notifications` | System notifications |
| `activity_logs` | Action audit trail |

## Leave Types

| Code | Name | Default Days |
|------|------|-------------|
| CL | Casual Leave | 10 |
| SL | Sick Leave | 21 |
| EL | Earned Leave | 15 |
| ML | Maternity Leave | 90 |
| PL | Paternity Leave | 15 |
| AL | Annual Leave | 21 |
| EML | Emergency Leave | 5 |
| BL | Bereavement Leave | 5 |
| CO | Compensatory Off | 0 |
| LWP | Unpaid Leave | 0 |
| STL | Study Leave | 10 |
| PHL | Public Holiday Leave | 0 |
| QL | Quarantine Leave | 14 |

## Project Structure

```
smart-leave-system/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Api/
│   │   │   │   └── ApiController.php        # REST API endpoints
│   │   │   ├── ActivityLogController.php     # Activity logging
│   │   │   ├── AuthController.php            # Login / Logout
│   │   │   ├── ComplaintController.php       # Complaints (admin + employee)
│   │   │   ├── DashboardController.php       # Dashboard with charts data
│   │   │   ├── DepartmentController.php      # Department CRUD
│   │   │   ├── EmployeeController.php        # Employee CRUD + password reset
│   │   │   ├── LeaveBalanceController.php    # Balance management
│   │   │   ├── LeaveRequestController.php    # Leave requests + calendar
│   │   │   ├── LeaveTypeController.php       # Leave type CRUD
│   │   │   ├── NotificationController.php    # Notifications
│   │   │   └── ReportController.php          # Reports + CSV export
│   │   └── Middleware/
│   │       └── RoleMiddleware.php            # Role-based access control
│   └── Models/                               # 8 Eloquent models
├── database/
│   ├── migrations/                           # All table schemas
│   └── seeders/                              # Admin + Leave Types
├── resources/views/
│   ├── layouts/
│   │   ├── app.blade.php                     # Main dashboard layout
│   │   └── guest.blade.php                   # Login layout
│   ├── auth/login.blade.php
│   ├── admin/                                # 14 admin views
│   │   ├── dashboard.blade.php               # Stats + Charts
│   │   ├── employees/                        # index, create, edit
│   │   ├── departments/                      # index, create, edit
│   │   ├── leave-types/                      # index, create, edit
│   │   ├── leaves/                           # index, show (approve/reject)
│   │   ├── balances/                         # index (add individual/all)
│   │   ├── complaints/                       # index, show (update)
│   │   ├── reports/                          # index (filter + export)
│   │   ├── calendar.blade.php                # FullCalendar view
│   │   └── activity-logs.blade.php           # Audit log
│   └── employee/                             # 8 employee views
│       ├── dashboard.blade.php
│       ├── leaves/                            # index, create, show
│       ├── balances.blade.php
│       ├── complaints/                        # index, create, show
│       └── notifications.blade.php
└── routes/
    ├── web.php                                # 54 web routes
    └── api.php                                # 8 API routes
```

## Security

- Password hashing with bcrypt
- CSRF protection on all forms
- Input validation on all requests
- Session-based authentication (web)
- Token-based authentication (API via Sanctum)
- Role-based authorization (admin / employee middleware)
