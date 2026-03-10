# Deployment Troubleshooting: 403 on /admin

If you get **403 Forbidden** when accessing `/admin` after login (works locally but not on cPanel/WHM), follow these steps.

## Step 1: Find where the 403 comes from

Add to your server `.env` (temporarily):

```env
ALLOW_ADMIN_BYPASS=1
```

Then run:

```bash
php artisan config:clear
```

Try logging in and opening the dashboard again.

- **If it works now** → The 403 was from Laravel’s admin middleware. Your user’s `role` is not `admin`, or the session/role check is wrong. Run `php artisan user:make-admin your@email.com`, then **remove** `ALLOW_ADMIN_BYPASS=1` from `.env` and clear config again.
- **If it still returns 403** → The block is at the server (Apache/Nginx/ModSecurity), not Laravel. Continue with Step 2.

## Step 2: Server-level fixes (cPanel/WHM)

### 1. Apache error logs

- **cPanel:** File Manager → `logs/` or `error_log`
- **WHM:** Home → Errors → Error Log, or `/var/log/apache2/error_log`

Look for lines related to `/admin` around the time of the 403 (e.g. ModSecurity, forbidden, access denied).

### 2. ModSecurity (very common on cPanel)

- **WHM:** Security Center → ModSecurity™ Configuration
- Temporarily turn ModSecurity off for your domain and test.
- If 403 goes away, either add an exception for `/admin` or **change the admin URL** (see below).

### 3. .htaccess rules

Check if any `.htaccess` above `public` blocks `/admin`. In `public/.htaccess` you should have Laravel’s default rules. There should be no `Deny` or `Order deny,allow` targeting `/admin`.

### 4. Document root

The site must point to the `public` folder (e.g. `/home/user/public_html/your-app/public`), not the project root. If it points to the root, `/admin` may not be handled correctly.

### 5. PHP handler / CGI

Some hosts run PHP as CGI (e.g. `suphp`). Try switching to **PHP-FPM** or **LSAPI** in:

- **cPanel:** MultiPHP Manager, Select PHP Version, or Setup PHP
- **WHM:** MultiPHP Manager

### 6. Symlinks

If `storage` or other symlinks are used, the host may block them. In **cPanel → File Manager**, check for broken symlinks. Ask the host whether they allow symlinks and how they should be set up.

### 7. Host-level restrictions

Some hosts block:

- Certain paths (e.g. `/admin`, `/wp-admin`)
- POST requests
- Specific user agents or headers

Contact support and mention: “403 on `/admin` after successful login; works locally; need to allow Laravel admin panel.”

## Step 3: Laravel-specific checks

- `APP_URL` in `.env` must match your live URL exactly.
- Clear config and cache:  
  `php artisan config:clear && php artisan cache:clear`
- Ensure `storage/` and `bootstrap/cache/` are writable (e.g. 775).
- Run `php artisan user:make-admin your@email.com` so the user has `role = 'admin'`.

### 8. Change the admin URL

If ModSecurity blocks common paths, use an obscure one. In `.env`:

```env
FILAMENT_ADMIN_PATH=_panel
```

Default is `_panel`. Try `cp`, `x`, or `manage123` if needed. Run `php artisan config:clear` after changes.

### 9. Disable ModSecurity via .htaccess

The `public/.htaccess` includes a ModSecurity bypass. If you still get 403, ensure your host allows `SecRuleEngine Off` in .htaccess. If not, disable ModSecurity for your domain in **WHM → Security Center → ModSecurity**.

## Security reminder

After fixing the issue, **remove** `ALLOW_ADMIN_BYPASS=1` from `.env` and run `php artisan config:clear`.
