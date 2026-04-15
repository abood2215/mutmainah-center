# Clinic Desk - Healthcare Management System

A comprehensive web-based clinic management system built with Laravel and Livewire. This platform enables clinics to manage appointments, patient records, medical documentation, and financial operations efficiently.

## Table of Contents

- [Features](#features)
- [Tech Stack](#tech-stack)
- [Prerequisites](#prerequisites)
- [Installation](#installation)
- [Environment Setup](#environment-setup)
- [Database Setup](#database-setup)
- [Running the Application](#running-the-application)
- [Project Structure](#project-structure)
- [Key Features](#key-features)
- [Development Guidelines](#development-guidelines)

## Features

- **Patient Management** - Register and manage patient profiles with medical history
- **Appointment Scheduling** - Book, track, and manage clinic appointments
- **Medical Records** - Store and retrieve detailed medical records for each patient
- **Invoice Management** - Generate and track invoices for clinic services
- **Employee Management** - Manage staff and employee access levels
- **Activity Logging** - Track all system activities for audit purposes
- **Real-time Dashboard** - Monitor clinic operations with live statistics
- **Role-Based Access Control** - Different permission levels for admin, doctors, and staff

## Tech Stack

- **Backend**: Laravel 11
- **Frontend**: Livewire, Blade Templates, Alpine.js
- **Database**: MySQL/PostgreSQL
- **Build Tool**: Vite
- **Package Manager**: Composer (PHP), NPM (JavaScript)

## Prerequisites

Before you begin, ensure you have the following installed on your system:

- **PHP** 8.2 or higher
- **Composer** (latest version)
- **Node.js** 18 or higher
- **npm** or **yarn**
- **MySQL** 8.0 or **PostgreSQL** 14+
- **Git**

## Installation

### 1. Clone the Repository

```bash
git clone https://github.com/motmaina/clinic-desk.git
cd clinic-desk
```

### 2. Install PHP Dependencies

```bash
composer install
```

### 3. Install Node Dependencies

```bash
npm install
```

### 4. Generate Application Key

```bash
php artisan key:generate
```

## Environment Setup

### 1. Create Environment File

Copy the example environment file:

```bash
cp .env.example .env
```

### 2. Configure Environment Variables

Edit `.env` file with your local settings:

```env
APP_NAME=ClinicDesk
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=clinic_desk
DB_USERNAME=root
DB_PASSWORD=

MAIL_MAILER=log
MAIL_FROM_ADDRESS=admin@mutmainah.com
```

**Important Database Fields:**
- `DB_DATABASE` - Create a new database with this name
- `DB_USERNAME` - Your database user (usually `root` on local)
- `DB_PASSWORD` - Your database password

## Database Setup

### 1. Create Database

Create a new database in MySQL:

```bash
mysql -u root -p
```

In MySQL shell:

```sql
CREATE DATABASE clinic_desk;
EXIT;
```

### 2. Run Migrations

```bash
php artisan migrate
```

This creates all necessary database tables for patients, appointments, invoices, medical records, and user management.

### 3. Seed Demo Data (Optional)

```bash
php artisan db:seed
```

This creates an admin user:
- **Email**: admin@mutmainah.com
- **Password**: password

**Note**: Change the admin password immediately after first login.

## Running the Application

### 1. Build Frontend Assets

For development with hot reload:

```bash
npm run dev
```

For production build:

```bash
npm run build
```

### 2. Start Laravel Development Server

In a separate terminal:

```bash
php artisan serve
```

The application will be available at: `http://localhost:8000`

### 3. Access the Dashboard

- Navigate to `http://localhost:8000` in your browser
- Log in with admin credentials (if seeded)
- Start managing clinic operations

## Project Structure

```
clinic-desk/
├── app/
│   ├── Http/              # Controllers and Middleware
│   ├── Livewire/          # Livewire components for interactive UI
│   ├── Models/            # Eloquent models (Patient, Appointment, etc.)
│   ├── Console/           # Artisan commands
│   ├── Providers/         # Service providers
│   └── Helpers/           # Helper functions and utilities
├── database/
│   ├── migrations/        # Database table schemas
│   ├── seeders/           # Database seeding files
│   └── factories/         # Model factories for testing
├── resources/
│   ├── views/             # Blade templates
│   ├── css/               # Stylesheets
│   └── js/                # JavaScript files
├── routes/
│   ├── web.php            # Web routes
│   └── console.php        # Console commands
├── config/                # Configuration files
├── public/                # Publicly accessible files
└── storage/               # Application storage (logs, files)
```

## Key Features Explained

### Patient Management

Access `/patients` to:
- Register new patients with detailed information
- Update patient contact and medical details
- View complete patient history

### Appointments

Manage appointments through the dashboard:
- Book new appointments for patients
- Track upcoming and past appointments
- Set reminders for scheduled visits

### Medical Records

Store comprehensive medical information:
- Patient diagnoses and treatments
- Lab results and clinical findings
- Medical history documentation

### Financial Management

Track clinic finances:
- Generate invoices for patient services
- Monitor payment status
- View financial reports

## Development Guidelines

### Creating New Features

1. **Create a Model**: 
   ```bash
   php artisan make:model ModelName -m
   ```

2. **Create a Livewire Component**:
   ```bash
   php artisan make:livewire ComponentName
   ```

3. **Create a Migration** (if needed):
   ```bash
   php artisan make:migration create_table_name
   ```

### Running Tests

```bash
php artisan test
```

### Database Rollback and Reset

Rollback one step:
```bash
php artisan migrate:rollback
```

Reset entire database:
```bash
php artisan migrate:reset
```

### Cache and Optimization

Clear all caches:
```bash
php artisan cache:clear
php artisan config:clear
```

Optimize for production:
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## Troubleshooting

### Database Connection Error

- Verify MySQL is running
- Check `.env` database credentials
- Ensure database exists: `CREATE DATABASE clinic_desk;`

### Permission Denied Error

Run the application with proper permissions:
```bash
chmod -R 775 storage bootstrap/cache
```

### Dependencies Error

Clear composer cache and reinstall:
```bash
composer clear-cache
composer install
```

### Asset Not Found

Rebuild frontend assets:
```bash
npm run dev
```

## Team Collaboration

When working with the team:

1. **Pull latest changes**:
   ```bash
   git pull origin master
   ```

2. **Create a feature branch**:
   ```bash
   git checkout -b feature/your-feature-name
   ```

3. **Make your changes** and commit:
   ```bash
   git add .
   git commit -m "Describe your changes"
   ```

4. **Push and create a pull request**:
   ```bash
   git push origin feature/your-feature-name
   ```

## Security Considerations

- **Never commit `.env` file** with sensitive data
- Use strong passwords for database and admin accounts
- Keep Laravel and dependencies updated
- Implement proper authentication and authorization checks
- Validate all user inputs on backend

## Support and Contribution

For issues or questions:

1. Check existing GitHub issues
2. Create a detailed bug report with reproduction steps
3. Follow code style and conventions
4. Submit pull requests for review

## License

This project is proprietary software for Mutmainah Medical Center.

---

**Last Updated**: April 2026
**Version**: 1.0.0
**Maintainers**: Development Team
