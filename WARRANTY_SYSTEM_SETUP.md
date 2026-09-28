# Warranty claim system — Hostinger setup

This connects the "Claim Your Warranty" form (`service.php`) to a real
MySQL database, private file storage, and a small admin panel where your
team can view and manage claims.

## 1. Create the database

1. hPanel → **Databases → MySQL Databases**.
2. Create a new database (e.g. `u123456789_warranty`) and a user with a
   strong password, and attach the user to the database with **All
   Privileges**.
3. Note the database host (usually `localhost` on Hostinger), name, user
   and password.

## 2. Load the schema

1. hPanel → **Databases → phpMyAdmin**, open your new database.
2. Click **Import**, choose `public_html/warranty_schema.sql`, click **Go**.
3. Confirm 4 tables now exist: `warranty_claims`, `warranty_claim_files`,
   `warranty_counters`, `warranty_admins`.

## 3. Configure `.env`

Open `public_html/.env` in File Manager and add:

```
DB_HOST="localhost"
DB_PORT="3306"
DB_NAME="u123456789_warranty"
DB_USER="u123456789_warrantyuser"
DB_PASS="the password you set"
WARRANTY_SIGNING_KEY="a long random string — generate a new one, don't reuse any example"
```

To generate a signing key, run this once from any terminal with PHP, or
just mash the keyboard for 40+ random characters:

```
php -r "echo bin2hex(random_bytes(32));"
```

`WARRANTY_STORAGE_DIR` is optional — if you leave it out, files are stored
one folder above `public_html` automatically, which is outside the web
root on Hostinger's standard layout.

## 4. Upload the new files

Along with the earlier warranty-page files, upload these **new** files to
`public_html`, keeping the folder structure:

- `db.php`
- `warranty_helpers.php`
- `service_submit.php` (replaces the existing one)
- `admin/warranty/auth.php`
- `admin/warranty/login.php`
- `admin/warranty/setup_admin.php`
- `admin/warranty/logout.php`
- `admin/warranty/index.php`
- `admin/warranty/claim.php`
- `admin/warranty/file.php`
- `admin/warranty/.htaccess`

Do **not** upload `warranty_schema.sql` or this file — they contain no
secrets, but they're setup instructions, not part of the running site.

## 5. Create your admin account

1. Visit `https://jomointeriors.in/admin/warranty/setup_admin.php`.
2. Pick a username and a password (10+ characters).
3. **Delete `admin/warranty/setup_admin.php` from the server immediately
   after** — it only works once, but leaving it live is unnecessary risk.
4. Log in at `https://jomointeriors.in/admin/warranty/login.php`.

To add more staff logins later, insert directly into the `warranty_admins`
table via phpMyAdmin (password must be a `password_hash()` value, not
plain text) — ask me and I'll talk you through it when you need it.

## 6. Where files are stored

Customer photos/videos are saved outside `public_html`, in a folder called
`warranty_storage`, organized by claim number. There is no public link to
any file — staff view them from the claim page after logging in, via a
link that expires a few minutes after the page loads.

## 7. What still uses the old system

Email notifications (to care@ and info@) and the Google Sheet log both
still work exactly as before — they now also include the claim number.
