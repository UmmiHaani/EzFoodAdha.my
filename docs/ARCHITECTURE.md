# Architecture

EzFoodAdha.my is a classic PHP monolith: server-rendered HTML, a single MySQL database, and no separate API layer. It is designed for shared hosting (XAMPP locally, InfinityFree in production).

## High-level layout

```
Storefront (public)          Admin panel                 Shared
──────────────────          ─────────────               ──────
index.php (router)          admin/index.php (router)    config/db_connect.php
pages/*.php (views)         admin/*.php (views)         config/config.php (gitignored)
login.php, signup.php       admin/ajax.php (actions)    includes/*_helper.php
header.php, footer.php                                  database/db_adha.sql
css/, js/, assets/
```

## Storefront request flow

1. Browser requests `index.php` (optionally `?page=home|about|cart_list|checkout`).
2. `index.php` starts the session, loads `config/db_connect.php`, and reads `system_settings` into `$_SESSION`.
3. Layout partials (`header.php`, `footer.php`) wrap the response.
4. The `page` query parameter is validated against a whitelist, then `pages/{page}.php` is included.
5. Standalone entry points (`login.php`, `signup.php`) handle auth outside the router.
6. Product detail modals load `pages/view_prod.php` via AJAX (separate request, own DB include).

## Admin request flow

1. Browser requests `admin/index.php` (`?page=menu|orders|...`).
2. Unauthenticated users are redirected to `login.php` at the site root.
3. `admin/index.php` whitelists `page`, then includes the matching `admin/{page}.php`.
4. Mutations (save menu, update order, login/logout) go through `admin/ajax.php` → `admin/admin_class.php`.

## Database

- Schema and seed data: `database/db_adha.sql`
- Runtime connection: `config/db_connect.php` reads `config/config.php` (or legacy `admin/config.php`)
- On first connect, `orders.archived` is added automatically if missing

## Uploaded assets

- Menu images, logos, gallery: `assets/img/` (paths stored in MySQL)
- Stock/demo images may live under `assets/downloaded/`

## Deployment

See the **Deploy online** section in [README.md](../README.md). Production credentials belong only in `config/config.php` on the server (or legacy `admin/config.php`); GitHub Actions excludes `config/config.php` from FTP uploads.
