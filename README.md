# Portfolio website setup

This repository contains the static portfolio and a PHP/MySQL version. The database-backed public page is `index.php`; `index.html` remains for the original static GitHub Pages site.

## Requirements

- PHP 8.0 or later with PDO and PDO MySQL
- MySQL 8.0+ or compatible MariaDB
- A PHP-capable web server
- HTTPS for public and admin use

GitHub Pages cannot run PHP or MySQL. Deploy the database-backed branch to a PHP hosting platform; use the static `index.html` only for GitHub Pages.

## Database setup

Create a database and a dedicated database user. Import the schema first, then optionally the sample data:

```sh
mysql -u DB_USER -p DB_NAME < database/schema.sql
mysql -u DB_USER -p DB_NAME < database/sample_data.sql
```

The sample data is for a fresh database. Review its personal details and image paths before publishing.

## Database credentials

Set these environment variables in the hosting panel or PHP-FPM/Apache environment:

```text
DB_HOST=127.0.0.1
DB_NAME=your_database
DB_USER=your_database_user
DB_PASSWORD=your_database_password
```

The application reads these values from `includes/db.php`. Never commit database passwords.

## Create the first administrator

Generate a password hash:

```sh
php -r 'echo password_hash("REPLACE_WITH_A_LONG_UNIQUE_PASSWORD", PASSWORD_DEFAULT), PHP_EOL;'
```

Insert the generated hash into MySQL:

```sql
INSERT INTO administrators (email, password_hash)
VALUES ('admin@example.com', 'PASTE_THE_GENERATED_HASH_HERE');
```

Use a unique password and sign in at `/admin/login.php` through HTTPS.

## Local development

Configure the database environment variables, then run:

```sh
php -S 127.0.0.1:8000
```

Open `http://127.0.0.1:8000/index.php`. The built-in server is for development only.

## Deployment checklist

1. Deploy to a PHP-capable host and provide PHP 8+.
2. Configure the database environment variables securely.
3. Import `database/schema.sql`; optionally import sample data once.
4. Create the first administrator account.
5. Configure `index.php` as the PHP default document or remove/rename `index.html` so the database page is served.
6. Enable HTTPS and restrict database access.
7. Test public content, contact submissions, CSRF protection, and admin CRUD.
8. Back up the database and keep credentials outside Git.

## Feature status

- Database-driven public portfolio: `index.php`
- Contact-message storage: `contact.php`
- Skills and project admin CRUD
- CSRF and session-based administrator authentication
- Education and experience schema tables; their CRUD pages are not yet implemented
