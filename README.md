# Albania Inbound – Tour Agency Web Application

A production-ready tour agency platform built with **Laravel 11**, MySQL, Tailwind CSS, Laravel Breeze, and Filament admin panel. Features dynamic tour listings, booking system, blog, user dashboard, and Stripe-ready payment architecture.

## Features

- **Public site:** Home (hero, featured tours, categories, blog preview), Tours list (filters, sort, pagination), Tour detail (gallery, itinerary, booking sidebar, reviews), Blog, Contact, About
- **Booking flow:** Select date & travelers → Guest form → Confirmation & email
- **User area:** Dashboard (bookings, wishlist), Booking detail, Cancel booking, Profile (Breeze)
- **Admin panel (Filament):** Tours (CRUD, itinerary repeater, images, dates), Categories, Bookings, Users, Blog, Reviews, Settings. Admin-only access by role.
- **SEO:** Slugs, meta tags, canonical, sitemap at `/sitemap.xml`
- **Security:** Rate limiting (contact, booking), policies (booking), CSRF

## Requirements

- PHP 8.2+
- Composer
- Node.js & npm (for Vite)
- MySQL 8 or SQLite (default in `.env` is SQLite for quick start)

## Setup

1. **Install dependencies**
   ```bash
   composer install
   npm install
   ```

2. **Environment**
   - Copy `.env.example` to `.env` if needed; `php artisan key:generate`
   - For MySQL: set `DB_CONNECTION=mysql`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD` and create the database.

3. **Database**
   ```bash
   php artisan migrate
   php artisan db:seed
   ```

4. **Storage**
   ```bash
   php artisan storage:link
   ```

5. **Run**
   ```bash
   php artisan serve
   npm run dev
   ```
   Open `http://localhost:8000`. Admin: `http://localhost:8000/_panel` (login: admin@albaniainbound.com / password). Override with `FILAMENT_ADMIN_PATH` in `.env` (e.g. `admin`).

6. **Email (SMTP) – Booking confirmations**

   After a booking is made, the customer receives a confirmation email and the admin gets a notification. To send real emails:
   - Set `MAIL_MAILER=smtp` in `.env`
   - Configure your SMTP credentials: `MAIL_HOST`, `MAIL_PORT`, `MAIL_USERNAME`, `MAIL_PASSWORD`, `MAIL_ENCRYPTION` (usually `tls`)
   - Set `MAIL_FROM_ADDRESS` and `MAIL_FROM_NAME` for the sender
   - Set `ADMIN_EMAIL` to the address that should receive new booking notifications

   For local testing, keep `MAIL_MAILER=log` – emails are written to `storage/logs/laravel.log`.

## Setting up on another PC (from GitHub)

The code is on GitHub, but **the database and `.env` are not** (they are ignored by git for security). You have two options:

### Option A: Fresh install (no data)

On the new PC:

```bash
git clone https://github.com/YOUR_USERNAME/YOUR_REPO_NAME.git
cd YOUR_REPO_NAME
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan db:seed
php artisan storage:link
php artisan serve
npm run dev
```

You get a working app with seed data (tours, admin user, etc.), but not the data you had on your first PC.

### Option B: Use your existing database (copy your local data)

To keep the same data (tours, bookings, users, etc.) on the new PC:

1. **On this PC:** Copy these to a USB drive or cloud folder:
   - **`.env`** – your environment config (DB, app key, etc.)
   - **`database/database.sqlite`** – your SQLite database file (if you use SQLite; it’s in the project’s `database/` folder)

2. **On the new PC:** After cloning and running `composer install` and `npm install`:
   - Paste **`.env`** into the project root (replace or create `.env`).
   - Paste **`database/database.sqlite`** into the `database/` folder (replace if it exists).
   - Run:
     ```bash
     php artisan storage:link
     php artisan serve
     npm run dev
     ```

The app will use the same data as on your first PC. No need to run `migrate` or `db:seed` if the DB is already up to date.

**If you use MySQL instead of SQLite:** export the database on the first PC (e.g. `mysqldump` or phpMyAdmin), create the same database on the new PC, then import the dump. Your `.env` on the new PC must have the same DB name and credentials (or you create a DB and user to match).

## Moving the database to a live server

You can run the app (and its database) on a live server in two ways.

### Option A: SQLite on the server (simplest)

1. Deploy your code to the server (e.g. via Git clone, FTP, or your host’s deploy).
2. On the server, create `.env` from `.env.example` and set:
   - `APP_ENV=production`
   - `APP_DEBUG=false`
   - `APP_URL=https://yourdomain.com`
   - `APP_KEY=` (run `php artisan key:generate` on the server)
   - Keep `DB_CONNECTION=sqlite` (no other DB_ variables needed for SQLite).
3. Copy your **local** `database/database.sqlite` file to the server into `database/database.sqlite`.
4. On the server run: `php artisan storage:link`, then point your web server to the `public` folder.

**Pros:** No MySQL to install; your existing data is used as-is.  
**Cons:** SQLite is less suited to high traffic and many concurrent writes; backups are “copy the file”.

---

### Option B: MySQL on the server (recommended for production)

1. On the **live server** (or your host’s panel): create a **MySQL database** and a user with access to it. Note the database name, username, password, and host (often `localhost`).
2. Deploy your code to the server.
3. On the server, create `.env` and set:
   - `APP_ENV=production`
   - `APP_DEBUG=false`
   - `APP_URL=https://yourdomain.com`
   - `APP_KEY=` (run `php artisan key:generate` on the server)
   - Database:
     ```env
     DB_CONNECTION=mysql
     DB_HOST=127.0.0.1
     DB_PORT=3306
     DB_DATABASE=your_database_name
     DB_USERNAME=your_database_user
     DB_PASSWORD=your_database_password
     ```
4. On the server run:
   ```bash
   php artisan migrate
   php artisan storage:link
   ```
   This creates empty tables. Then either:

   **B1 – Start fresh on the server**  
   Run `php artisan db:seed` to get demo data. You can then re-add your real content via the admin panel.

   **B2 – Copy your local data into MySQL**  
   - Export your **local SQLite** data (e.g. with a “SQLite to MySQL” converter or a small script that reads SQLite and inserts into MySQL).  
   - Or: use your host’s **phpMyAdmin** (or similar): create the database, run the migrations there if needed, then import a MySQL dump you generated from a converted SQLite export.  
   - Tools that can help: [sqlite3-to-mysql](https://github.com/techouse/sqlite3-to-mysql) (Python), or DB GUI tools that support both SQLite and MySQL.

5. Copy **uploaded files** from your local `storage/app/public/` (tour images, cities, hotels) to the server’s `storage/app/public/` so images and assets work.

**Pros:** Better for production (concurrent users, backups, scaling).  
**Cons:** Requires MySQL and a one-time data migration from SQLite if you want to keep current data.

---

### Checklist for any live deployment

- [ ] `.env` on server has `APP_ENV=production`, `APP_DEBUG=false`, correct `APP_URL`
- [ ] `APP_KEY` generated on server (`php artisan key:generate`)
- [ ] Database on server: either SQLite file in place or MySQL configured and migrated
- [ ] `php artisan storage:link` run on server
- [ ] Web server document root is the app’s **`public`** directory
- [ ] If you use MySQL: `storage/` and `bootstrap/cache/` writable by the web server

### Fixing 403 Forbidden on /admin (cPanel / shared hosting)

If login works but dashboard pages show **403 Forbidden**:

1. **User role** – The admin user must have `role = 'admin'` in the database. Run via SSH (or cPanel Terminal):
   ```bash
   php artisan user:make-admin your@email.com
   ```

2. **Session & cookies** – In `.env`, ensure:
   - `APP_URL` matches your live URL exactly (e.g. `https://yourdomain.com`, no trailing slash)
   - `SESSION_DOMAIN` is empty or matches your domain (e.g. `.yourdomain.com` for subdomains)
   - `SESSION_SECURE_COOKIE=true` if using HTTPS

3. **Clear caches** on the server:
   ```bash
   php artisan config:clear
   php artisan cache:clear
   php artisan view:clear
   ```

4. **ModSecurity** – cPanel’s ModSecurity may block requests. In WHM → Security Center → ModSecurity, try disabling for your domain or adding an exception for `/admin/*`.

### Reviews not showing on tour pages (after import)

If you imported reviews but they don't appear: run `php artisan reviews:approve-all` (reviews may be unapproved). Also clear caches: `php artisan view:clear` and `php artisan cache:clear`.

### Duplicate tours (after running import twice)

If you ran `ToursFromExportSeeder` more than once and have duplicate tours:

```bash
php artisan tours:remove-duplicates --dry-run   # preview
php artisan tours:remove-duplicates             # apply
```

Keeps one tour per slug (the one with the most reviews). The seeder is now idempotent—re-running it will not create duplicates.

## Default accounts (after seed)

- **Admin:** admin@albaniainbound.com / password  
- **User:** test@example.com / password  

## Stripe (optional)

The app uses a `PaymentServiceInterface` (bound to `NullPaymentService` by default). To enable Stripe: implement a `StripePaymentService`, bind it in `AppServiceProvider`, and add a checkout redirect + webhook route.

## License

Laravel is open-sourced under the [MIT license](https://opensource.org/licenses/MIT).

---

<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com/)**
- **[Tighten Co.](https://tighten.co)**
- **[WebReinvent](https://webreinvent.com/)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel/)**
- **[Cyber-Duck](https://cyber-duck.co.uk)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Jump24](https://jump24.co.uk)**
- **[Redberry](https://redberry.international/laravel/)**
- **[Active Logic](https://activelogic.com)**
- **[byte5](https://byte5.de)**
- **[OP.GG](https://op.gg)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
