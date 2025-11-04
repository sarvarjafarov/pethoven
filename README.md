# WordPress Installation

The latest WordPress core files have been downloaded into the `wordpress/` directory and a starter `wp-config.php` has been created from the upstream sample.

## Run locally with Docker

1. Ensure Docker Desktop (or another Docker engine) is running.
2. From the project root run `docker compose up` to start both WordPress and MySQL.
3. Browse to http://localhost:8000 and follow the WordPress installer. The database credentials are pre-wired in `docker-compose.yml`.
4. Stop the stack with `docker compose down`. Use `docker compose down -v` to remove the database volume if you want a clean install.

## Run locally with PHP's built-in server

1. Install PHP 8+ and MySQL locally (e.g. `brew install php mysql` on macOS) and start the MySQL service (`brew services start mysql`).
2. Create a database and user for WordPress:
   ```bash
   mysql -u root -p -e "CREATE DATABASE wordpress;"
   mysql -u root -p -e "CREATE USER 'wordpress'@'localhost' IDENTIFIED BY 'wordpress';"
   mysql -u root -p -e "GRANT ALL PRIVILEGES ON wordpress.* TO 'wordpress'@'localhost';"
   ```
3. Copy the environment template: `cp .env.local.example .env.local`, then adjust the credentials if they differ.
4. Launch the development server with `./scripts/serve-local.sh`. Feel free to override the host/port via `WP_HOST`/`WP_PORT` in `.env.local`.
5. Open `http://127.0.0.1:8000/wp-admin/install.php` (or your chosen host/port) to finish the WordPress installer.
6. Stop the server with `Ctrl+C`. The script merely runs `php -S`, so no background processes remain.

## Next steps

1. If you are not using Docker, create a MySQL (or MariaDB) database and user, then update the following settings inside `wordpress/wp-config.php`:
   - `DB_NAME`
   - `DB_USER`
   - `DB_PASSWORD`
   - `DB_HOST` (usually `localhost`)  
   When environment variables such as `WORDPRESS_DB_NAME` are present (e.g. via Docker Compose), the config file will use them automatically.
2. Salts and keys have been pre-generated in `wordpress/wp-config.php` for this local environment; regenerate them from https://api.wordpress.org/secret-key/1.1/salt/ before deploying anywhere public.
3. If you are running with a custom table prefix, adjust `$table_prefix` or set the `WORDPRESS_TABLE_PREFIX` environment variable.
4. Serve the `wordpress` directory from a PHP-compatible web server (for example, `php -S localhost:8000 -t wordpress`) and follow the on-screen installer if not using Docker.
5. Adjust filesystem permissions as required by your environment so WordPress can manage uploads and updates.

With these steps complete, visiting the site URL in a browser will finalize the WordPress installation.

## Themes

- The Astra parent theme is located in `wordpress/wp-content/themes/astra`.
- A custom organic-focused child theme lives at `wordpress/wp-content/themes/astra-organic`. Activate it in the WordPress admin (`Appearance → Themes`) after finishing the installer.
- The child theme depends on the Astra parent theme and applies earthy colors, button styles, and WooCommerce-friendly accents tailored to organic brands.

## Organic Store Template

1. Visit `Plugins → Installed Plugins` and activate the bundled free plugins:
   - Starter Templates (Astra)
   - Spectra (Ultimate Addons for Gutenberg)
   - WooCommerce
   - WPForms Lite
2. Go to `Appearance → Starter Templates`, pick the Gutenberg builder, and search for “Organic Store”.
3. Click “Import Template” → choose either full site or specific pages. Keep the WooCommerce sample products checked for a complete demo.
4. After the import finishes, set the imported “Shop” page as the shop archive via `WooCommerce → Settings → Products`.
5. Review the menus/widgets/pages and tweak colors via the WordPress Customizer as needed—the Astra Organic child theme styles will carry over the earthy palette.
6. The must-use helper at `mu-plugins/organic-store-pages.php` ensures essential pages (Home, Shop, Cart, Checkout, My Account, Journal, Contact, About) exist and publishes them if missing. Edit these pages freely; the helper will not overwrite your content once created.

## Performance

- `./scripts/serve-local.sh` bootstraps the PHP server with OPCache enabled; override flags via `PHP_FLAGS` if you need different settings.
- `wordpress/wp-config.php` now defaults to `WP_CACHE` and `DISABLE_WP_CRON` set to `true` in local mode. Adjust via `.env.local` as needed (set `WP_CACHE=false` or `DISABLE_WP_CRON=false`).
- A must-use plugin at `wordpress/wp-content/mu-plugins/performance-tweaks.php` trims emoji scripts, Google Fonts, query-string assets, and the comment reply script for quicker local loads.
- Because cron is disabled, run scheduled tasks manually when needed: `wp cron event run --due-now` (via WP-CLI) or temporarily set `DISABLE_WP_CRON=false`.
