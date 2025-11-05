# WordPress Installation

The WordPress core files now live at the project root and `wp-config.php` has been tailored to load credentials from `.env` files.

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

1. If you are not using Docker, create a MySQL (or MariaDB) database and user, then update the following settings inside `wp-config.php` (or set them through `.env` files):
   - `DB_NAME`
   - `DB_USER`
   - `DB_PASSWORD`
   - `DB_HOST` (usually `localhost`)  
   When environment variables such as `WORDPRESS_DB_NAME` are present (e.g. via Docker Compose), the config file will use them automatically.
2. Salts and keys have been pre-generated in `wp-config.php` for this local environment; regenerate them from https://api.wordpress.org/secret-key/1.1/salt/ before deploying anywhere public.
3. If you are running with a custom table prefix, adjust `$table_prefix` or set the `WORDPRESS_TABLE_PREFIX` environment variable.
4. Serve the project root from a PHP-compatible web server (for example, `php -S localhost:8000 -t .`) and follow the on-screen installer if not using Docker.
5. Adjust filesystem permissions as required by your environment so WordPress can manage uploads and updates.

With these steps complete, visiting the site URL in a browser will finalize the WordPress installation.

## Themes

- The Astra parent theme is located in `wp-content/themes/astra`.
- A custom organic-focused child theme lives at `wp-content/themes/astra-organic`. Activate it in the WordPress admin (`Appearance → Themes`) after finishing the installer.
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
6. The must-use helper at `wp-content/mu-plugins/organic-store-pages.php` ensures essential pages (Home, Shop, Cart, Checkout, My Account, Journal, Contact, About) exist and publishes them if missing. Edit these pages freely; the helper will not overwrite your content once created.

## Performance

- `./scripts/serve-local.sh` bootstraps the PHP server with OPCache enabled; override flags via `PHP_FLAGS` if you need different settings.
- `wp-config.php` derives runtime toggles (cache, cron, script concatenation, memory limits) from environment variables and automatically relaxes settings when `WP_ENVIRONMENT_TYPE=local`.
- A must-use plugin at `wp-content/mu-plugins/performance-tweaks.php` trims emoji scripts, Google Fonts, query-string assets, and the comment reply script for leaner pages.
- Because cron is disabled in local mode, run scheduled tasks manually when needed: `wp cron event run --due-now` (via WP-CLI) or set `DISABLE_WP_CRON=false`.

## Prepare for production

1. Update `wp-config.php` (or `.env.production`) with your production database credentials and set `WP_ENVIRONMENT_TYPE=production`. With this value `DISABLE_WP_CRON` automatically becomes `false` and script concatenation/compression are enabled.
2. Replace the salts/keys in `wp-config.php` using https://api.wordpress.org/secret-key/1.1/salt/ for the live site.
3. Remove demo content you do not plan to publish (sample posts, products, imported imagery) and upload your branding assets.
4. Disable any local-only mu-plugins or helpers you don’t want in production (e.g. delete `performance-tweaks.php` if you prefer to keep Google Fonts).
5. Regenerate thumbnails after swapping media (`wp media regenerate` via WP-CLI or a plugin) so migrated assets display crisply.
6. Create a `.env.production` file alongside `wp-config.php` on the server (do not commit it) using `.env.production.example` as a guide; populate your Hostinger database credentials, domain (`WP_HOME`, `WP_SITEURL`), and any other overrides. The config loader will read `.env`, `.env.local`, and `.env.production` automatically if present.

## Deploy to Hostinger

1. **Create the hosting environment**
   - In hPanel, add your domain and create a new site (WordPress or “Other”).
   - Under “Databases”, create a MySQL database and note the DB name, user, password, and host.
2. **Prepare files**
   - From the repository root, zip the entire project (excluding `.git` if you are uploading manually). Hostinger expects the WordPress files directly inside `public_html/`.
   - Upload the archive via hPanel’s File Manager or SFTP, extract it into `public_html`, and ensure `wp-config.php` sits in the document root.
3. **Configure `wp-config.php` on the server**
   - Set `DB_NAME`, `DB_USER`, `DB_PASSWORD`, and adjust `DB_HOST` (Hostinger typically uses `localhost` or the server IP).
   - Define `WP_ENVIRONMENT_TYPE` as `production`; optionally set `WP_HOME` and `WP_SITEURL` to your domain to prevent URL mismatches.
4. **Migrate the database**
   - Export your local database (`mysqldump wordpress > wordpress.sql` or via phpMyAdmin).
   - Import the SQL file using Hostinger’s phpMyAdmin for the new database.
   - Run a search-replace to swap local URLs for the live domain (phpMyAdmin’s SQL tab or WP-CLI, e.g. `wp search-replace 'http://127.0.0.1:8080' 'https://yourdomain.com'`).
5. **Finalize in WordPress**
   - Log into the live admin to flush permalinks (`Settings → Permalinks → Save`).
   - Verify WooCommerce pages (`WooCommerce → Settings → Advanced`) still point to the correct URLs.
   - Re-run Starter Templates if you want demo content refreshed on the live site.
6. **Hostinger extras**
   - Enable free SSL (hPanel → SSL) and force HTTPS in `Settings → General` or via `.htaccess`.
   - Configure SMTP/email delivery (Hostinger Email or a transactional service) for order notifications.
   - Set up a cron job in hPanel (`wp-cron.php`) if you intentionally set `DISABLE_WP_CRON=true`.

## GitHub CI/CD to Hostinger

1. In your GitHub repo, open `Settings → Secrets and variables → Actions` and add:
   - `HOSTINGER_HOST` = `seashell-opossum-486356.hostingersite.com` (or the FTP IP).
   - `HOSTINGER_USERNAME` = `u722617394.seashell-opossum-486356.hostingersite.com`.
   - `HOSTINGER_PASSWORD` = *(the FTP/SFTP password you set in hPanel)*.
   - `HOSTINGER_REMOTE_DIR` = `public_html/` (include the trailing slash).
2. The workflow at `.github/workflows/deploy-hostinger.yml` syncs the project root (excluding uploads, scripts, etc.) to the remote `public_html/` folder on every push to `main`. Trigger it manually from the Actions tab via “Run workflow” when needed. By default it uses FTPS; if you need SFTP/rsync, uncomment the alternate step in the workflow and add the `HOSTINGER_SSH_KEY` secret containing your private key.
3. For a secure connection the workflow defaults to FTPS (`protocol: ftps`). If Hostinger requires SFTP, change the `protocol` value to `sftp` and optionally set the `port` input (e.g. `22`) in the workflow file.
4. Dynamic media uploads are excluded (`wp-content/uploads/**`). Keep managing media within WordPress so that user uploads on production are not overwritten. Remove the exclude pattern if you prefer to version-control uploads.
5. Remember that `.env` files are ignored by Git; create your `.env.production` (with DB credentials, domain, and SSL preference) directly on Hostinger after the first deployment.
6. If you set `WP_HOME`/`WP_SITEURL` in `.env.production`, make sure the values match the domain serving WordPress (e.g. `https://seashell-opossum-486356.hostingersite.com`).
7. The first live deployment should start from a clean `public_html` (zip the project root and upload/extract once, or run the action after confirming the directory is empty). After that, committing to `main` from VS Code will automatically sync changes to Hostinger.

## Post-deployment checklist

- Browse all key pages (Home, Shop, Cart, Checkout, My Account, Blog, Contact) to confirm layouts, menus, and forms load correctly.
- Place a test order in WooCommerce (sandbox/offline mode) and ensure confirmation emails arrive.
- Check performance with PageSpeed Insights or GTmetrix and enable additional caching/CDN if needed (LiteSpeed Cache is available on Hostinger).
- Create regular backups (Hostinger Backups or a plugin like UpdraftPlus) before making future changes.
- Monitor logs (`wp-content/debug.log` if `WP_DEBUG_LOG` is enabled) and the Hostinger error log for the first 24 hours to catch configuration issues.
