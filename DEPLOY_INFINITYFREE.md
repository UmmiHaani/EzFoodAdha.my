# Deploy EzFoodAdha.my on InfinityFree (free)

GitHub stores your code. InfinityFree runs the live PHP + MySQL site.

## Part 1 — Create your free site (you do this in the browser)

1. Go to [https://infinityfree.com](https://infinityfree.com) and sign up.
2. **Create Account** → **Create Hosting Account** → pick a subdomain (e.g. `ezfoodadha.infinityfreeapp.com`).
3. Open **vPanel** for that account.

### MySQL database

4. vPanel → **MySQL Databases** → create a database.
5. Write down:
   - **MySQL Hostname** (e.g. `sql123.infinityfree.com`) — not `127.0.0.1`
   - **Database name**
   - **Username**
   - **Password**
6. Open **phpMyAdmin** → select your database → **Import** → choose `database/ADHA_db.sql` from this project.

### FTP (for upload or GitHub Actions)

7. vPanel → **FTP Details** — note:
   - FTP hostname (e.g. `ftpupload.net` or shown in panel)
   - Username
   - Password
8. Document root is **`htdocs`**.

---

## Part 2 — Upload files

### Option A: File Manager (easiest first time)

1. Zip this whole project on your computer (exclude `.git` folder).
2. vPanel → **Online File Manager** → open `htdocs`.
3. Upload the zip → **Extract**.
4. You should see `index.php`, `admin/`, `assets/`, etc. directly inside `htdocs`.

### Option B: GitHub Actions (after Part 3 below)

Push to `main` and the workflow uploads files automatically.

---

## Part 3 — Database config on the server (required)

On the server only, create `admin/config.php` (never commit real passwords to GitHub):

1. Copy `admin/config.example.php` → `admin/config.php` in **htdocs/admin/**.
2. Edit with your InfinityFree MySQL details from Part 1.

Example:

```php
<?php
return [
    'db_host' => 'sql123.infinityfree.com',
    'db_user' => 'if0_12345678',
    'db_pass' => 'your_real_password',
    'db_name' => 'if0_12345678_ADHA_db',
    'db_port' => 3306,
];
```

---

## Part 4 — Test

| Page | URL |
|------|-----|
| Storefront | `https://YOUR-SUBDOMAIN.infinityfreeapp.com/` |
| Admin | `https://YOUR-SUBDOMAIN.infinityfreeapp.com/admin/` |

1. Open the storefront — menu should load.
2. Log in as `admin` / `admin123` and **change the password**.
3. vPanel → enable **SSL** if not already on.

---

## Part 5 — Link live site from GitHub

Edit `README.md` and replace the placeholder with your real URL:

```markdown
## Live demo

**[Open EzFoodAdha.my](https://YOUR-SUBDOMAIN.infinityfreeapp.com/)**
```

Commit and push to GitHub.

---

## Part 6 — Optional: deploy from GitHub on every push

1. GitHub repo → **Settings** → **Secrets and variables** → **Actions** → **New repository secret**:
   - `FTP_SERVER` — FTP hostname from vPanel
   - `FTP_USERNAME` — FTP username
   - `FTP_PASSWORD` — FTP password
2. Push to `main` — workflow `.github/workflows/deploy-infinityfree.yml` uploads to `htdocs`.
3. **`admin/config.php` stays on the server only** — create it once manually (Part 3); it is not uploaded from Git.

---

## Troubleshooting

| Problem | Fix |
|---------|-----|
| Could not connect to mysql | Wrong host/user/db in `admin/config.php` |
| Blank page | Check vPanel error logs; confirm PHP files are in `htdocs` |
| 404 on pages | Ensure `index.php` is in `htdocs`, not a subfolder |
| Images won't upload | Set `assets/img/` permission to 755 in File Manager |
| Site slow first visit | Normal on free hosting after idle sleep |
