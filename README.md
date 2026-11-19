# Portfolio website setup

This repository includes a static portfolio (`index.html`) and a PHP/MySQL-backed portfolio (`index.php`). The PHP version includes an administrator area and stores contact submissions in the database.

## Requirements

- PHP 8.0 or later with PDO and the PDO MySQL driver enabled
- MySQL 8.0+ or a compatible MariaDB server
- A web server configured to execute PHP (such as Apache/PHP-FPM or Nginx/PHP-FPM)
- HTTPS for public deployment, especially for administrator sign-in

GitHub Pages serves static files only; it cannot execute PHP or connect to MySQL. Use a PHP-capable host for the database-backed version. The static `index.html` remains available for GitHub Pages.

## Database setup

Create a database and a dedicated database user with only the permissions the app requires. Import the schema first, then optionally import the sample data:

```sh
mysql -u DB_USER -p DB_NAME < database/schema.sql
mysql -u DB_USER -p DB_NAME < database/sample_data.sql
```

The sample data is intended for a fresh database and is not idempotent. Import it only once. Review the personal information, email address, and image paths before publishing.

## Configure database credentials

Set these environment variables in the hosting control panel or the environment used by PHP-FPM/Apache. Do not commit credentials to Git:

```text
DB_HOST=127.0.0.1
DB_NAME=your_database
DB_USER=your_database_user
DB_PASSWORD=your_database_password
```

The app reads these values in `includes/db.php`. Make sure the PHP process receives them; values set only in an interactive shell may not be available to the web server.

For local development, export the variables in your shell before starting PHP, for example:

```sh
export DB_HOST=127.0.0.1 DB_NAME=your_database DB_USER=your_database_user DB_PASSWORD=your_database_password
```

## Create the first administrator

There is no default admin account. Generate a password hash with PHP, replacing the placeholder with a unique, strong password:

```sh
php -r 'echo password_hash("REPLACE_WITH_A_LONG_UNIQUE_PASSWORD", PASSWORD_DEFAULT), PHP_EOL;'
```

Insert the generated hash into the database, replacing the email and hash with your own values:

```sql
INSERT INTO administrators (email, password_hash)
VALUES ('admin@example.com', 'PASTE_THE_GENERATED_HASH_HERE');
```

Sign in at `/admin/login.php` over HTTPS. Do not save the plaintext password in the repository or SQL history.

## Run locally

After configuring the database environment variables and importing the schema, start PHP's built-in development server from the repository root:

```sh
php -S 127.0.0.1:8000
```

Open `http://127.0.0.1:8000/index.php`. The built-in server is for local development only, not production.

## Deployment checklist

1. Deploy to a PHP-capable host and configure PHP 8+ with PDO MySQL.
2. Set database environment variables securely in the web server's PHP environment.
3. Import `database/schema.sql`. Optionally import `database/sample_data.sql` once.
4. Create an administrator account using the steps above.
5. Set `index.php` as the PHP default document or remove/rename `index.html`; many hosts prefer `index.html` and may otherwise show the static page first.
6. Enable HTTPS and restrict database access to the application host where possible.
7. Test the public portfolio, contact form submission and CSRF check, admin sign-in, project/skill management, and message review.
8. Back up the database and keep credentials and backups out of Git and the public web root.

## Feature status

- The PHP public page reads personal details, skills, projects, education, and experience from MySQL.
- Contact submissions are stored in `contact_messages` and can be reviewed and marked as read at `/admin/messages.php` after sign-in.
- Admin CRUD pages are available for projects and skills.
- Admin login uses password hashes, session authentication, and CSRF tokens.
- Education and experience are displayed publicly but do not yet have admin CRUD pages.
