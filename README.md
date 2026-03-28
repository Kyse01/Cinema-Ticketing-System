# Cinematique - Cinema Ticketing System

A full-stack movie ticketing web application built with Laravel. This system features dynamic movie scheduling, interactive seat selection, proof-of-payment uploads, account activation via email, and OTP-based secure login.

---

## 🚀 Prerequisites

Before you begin, ensure you have the following installed on your machine:
-   **PHP** (^8.1 or higher recommended)
-   **Composer** (Dependency manager for PHP)
-   **MySQL** (or any supported database)
-   **Node.js & npm** (Optional, for frontend asset bundling if utilizing Vite)

---

## 🛠️ Local Development Setup

To run this project on a local development environment, follow these steps sequentially:

### 1. Clone & Install Dependencies
Navigate to your preferred directory and run:
```bash
composer install
npm install
```

### 2. Environment Configuration
Create a copy of your environment file:
```bash
cp .env.example .env
```
Open the `.env` file and generate the application encryption key:
```bash
php artisan key:generate
```

### 3. Database Setup
In your `.env` file, update your database credentials to match your local setup:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=cinema_ticketing_system
DB_USERNAME=root
DB_PASSWORD=your_password
```
Run the migrations to build the necessary tables (Users, Movies, Cinemas, Bookings, etc.):
```bash
php artisan migrate
```

### 4. Storage Link (Critical)
Because the system uploads and serves images (Movie Posters, Payment Proofs) from the `storage/app/public` directory, you must create a symbolic link to the public folder:
```bash
php artisan storage:link
```

### 5. Mailtrap Configuration
The system uses email for **Account Activation** and **OTP Login**. For local testing, use [Mailtrap](https://mailtrap.io/):
```env
MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_mailtrap_username
MAIL_PASSWORD=your_mailtrap_password
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="${APP_NAME}"
```

### 6. Run the Application
Start the Laravel development server:
```bash
php artisan serve
```
If using Vite for asset compilation, run in a separate terminal:
```bash
npm run dev
```

Visit the application at: `http://localhost:8000`

---

## 🌍 Setting Up for Different Environments (Production / Staging)

When moving this project from Local to a Staging or Production server, strict security and performance adjustments are required.

### 1. Update Core Application Variables
Change the environment and disable debugging to prevent sensitive data leaks.
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.com
```

### 2. Configure Real SMTP Server
Mailtrap is only for development. In production, connect to a live transactional email service like SendGrid, Mailgun, Amazon SES, or Postmark:
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.sendgrid.net
MAIL_PORT=587
MAIL_USERNAME=apikey
MAIL_PASSWORD=your_secure_api_key
MAIL_ENCRYPTION=tls
```

### 3. Folder Permissions
Ensure the web server (e.g., `www-data` or `nginx`) has appropriate write permissions for the storage and cache folders:
```bash
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

### 4. Optimize & Cache Laravel
For massive performance gains in production, Laravel should pre-compile and cache its settings. Run these commands on your live server:
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache
```
*(Note: Whenever you modify your `.env` file or routes in production, you must re-run these commands to clear the old cache).*

### 5. Secure Session & Queue Drivers (Optional but Recommended)
For high-traffic environments, do not use the default `file` or `database` drivers for sessions and cache.
- Install Redis.
- Update `.env`:
```env
CACHE_STORE=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis
```
Then start the worker process (managed via Supervisor) to handle background tasks securely:
```bash
php artisan queue:work
```
