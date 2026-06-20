# Deploying the Salale Clearance System to Railway

This app is a stateful Laravel 12 application (MySQL, DomPDF certificates, Excel
exports, uploaded photos). It is deployed on **Railway** using the included
`Dockerfile` (php-fpm + nginx) plus a managed MySQL database and a persistent
volume for uploaded/generated files.

> **Why not Vercel?** Vercel runs PHP as serverless functions on a read-only,
> ephemeral filesystem. This app writes certificate PDFs, student photos and DB
> backups to disk and serves them later — those files would vanish between
> requests. Railway gives us a persistent volume and a long-running container,
> so the app runs essentially as-is.

---

## What's in this repo for deployment

| File | Purpose |
|------|---------|
| `Dockerfile` | Multi-stage build: compiles Vite assets, installs PHP deps, runs php-fpm + nginx |
| `.dockerignore` | Keeps `vendor`, `node_modules`, `.env`, local storage out of the image |
| `docker/nginx.conf` | Nginx site (binds to Railway's `$PORT`, serves `public/`) |
| `docker/supervisord.conf` | Runs php-fpm and nginx together |
| `docker/entrypoint.sh` | On boot: fixes storage perms, links storage, caches config, runs migrations |

---

## One-time setup

### 1. Push to GitHub
Commit these new files and push to a GitHub repo Railway can access.

### 2. Create the Railway project + MySQL
1. Go to [railway.app](https://railway.app) → **New Project**.
2. **+ New → Database → Add MySQL**. Railway provisions it and exposes
   `MYSQLHOST`, `MYSQLPORT`, `MYSQLDATABASE`, `MYSQLUSER`, `MYSQLPASSWORD`.

### 3. Add the app service
1. **+ New → GitHub Repo** → select this repo. Railway auto-detects the
   `Dockerfile` and builds it.

### 4. Add the persistent volume (important!)
On the app service: **Settings → Volumes → Add Volume**, mount path:

```
/var/www/html/storage/app
```

This is where certificates, student photos and backups live. Without it, every
redeploy wipes generated files.

### 5. Generate an APP_KEY
Locally run:

```bash
php artisan key:generate --show
```

Copy the `base64:...` value — you'll paste it as `APP_KEY` below.
**Do not let it change between deploys** (it encrypts sessions and data).

### 6. Set environment variables
On the app service → **Variables**, add the following. The `${{MySQL.*}}`
references pull live values from the MySQL service.

```env
APP_NAME=Salale Clearance System
APP_ENV=production
APP_KEY=base64:PASTE_THE_KEY_FROM_STEP_5
APP_DEBUG=false
APP_URL=https://REPLACE_WITH_YOUR_RAILWAY_DOMAIN

LOG_CHANNEL=stderr
LOG_LEVEL=error

DB_CONNECTION=mysql
DB_HOST=${{MySQL.MYSQLHOST}}
DB_PORT=${{MySQL.MYSQLPORT}}
DB_DATABASE=${{MySQL.MYSQLDATABASE}}
DB_USERNAME=${{MySQL.MYSQLUSER}}
DB_PASSWORD=${{MySQL.MYSQLPASSWORD}}

SESSION_DRIVER=database
SESSION_LIFETIME=120
SESSION_ENCRYPT=true

CACHE_STORE=database
QUEUE_CONNECTION=sync

FILESYSTEM_DISK=local
BCRYPT_ROUNDS=12

# Mail: 'log' just records mail to the log. Swap for real SMTP when ready.
MAIL_MAILER=log
MAIL_FROM_ADDRESS=noreply@salale-clearance.local
MAIL_FROM_NAME=${APP_NAME}

# Leave blank to auto-derive a strong PDF owner password from APP_KEY
PDF_OWNER_PASSWORD=
```

> **Sessions, cache and queue are set to the database / sync** because the
> container has no persistent `storage/framework` directory and a Railway volume
> can attach to only one service. The required `sessions`, `cache` and `jobs`
> tables are created by the existing migrations. With `QUEUE_CONNECTION=sync`,
> queued jobs (`GeneratePdfJob`, `SendClearanceMailJob`, reminders) run inline
> during the request — simple and reliable at this app's scale.

### 7. Generate the public domain
App service → **Settings → Networking → Generate Domain**. Put that URL in
`APP_URL` (step 6) and redeploy so the value is baked into the cached config and
the `public` disk URLs.

### 8. Seed the initial accounts (first deploy only)
Migrations run automatically on every boot. To create the initial super admin /
registrar / etc., run the seeder once. Either:

- **Railway CLI:** `railway run php artisan db:seed --force`, or
- Add the seeder passwords as variables and run `php artisan db:seed --force`
  from the service's shell.

The seeder reads `SEED_SUPER_ADMIN_PASSWORD`, `SEED_REGISTRAR_PASSWORD`,
`SEED_DEPARTMENT_OFFICER_PASSWORD`, `SEED_STUDENT_PASSWORD`,
`SEED_DEPARTMENT_STAFF_PASSWORD`. Set strong values, run once, then you may
remove them. **Change these passwords after first login.**

---

## Routine deploys
Push to the connected branch → Railway rebuilds and redeploys. On each boot the
entrypoint re-caches config/routes/views and runs `migrate --force`.

## Notes & caveats
- **No async queue worker.** Jobs run synchronously (`QUEUE_CONNECTION=sync`).
  To process them in the background later, move file storage to S3
  (`FILESYSTEM_DISK=s3`, fill the `AWS_*` vars) so a separate worker service can
  share storage, then run a second service with `php artisan queue:work`.
- **DB backups** written to `storage/app/backups` persist on the volume, but the
  in-app "create backup" runs `mysqldump`-style logic — verify it works against
  the managed MySQL before relying on it.
- **Logs** go to stdout/stderr (visible in Railway's log viewer) via
  `LOG_CHANNEL=stderr`.
- To run any artisan command in production: `railway run php artisan <cmd>`.
