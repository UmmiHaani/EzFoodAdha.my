# EzFoodAdha.my — Online Food Ordering System

**My first website. A project I am genuinely proud of.**

During my high school years, the world changed because of COVID-19. Like many schools, ours had to rethink how everyday things worked—including the canteen. Queues, crowded counters, and close contact were risks we wanted to reduce.

This was the first website I ever built. I created **EzFoodAdha.my** so students and staff could browse the canteen menu, place orders, and help the school deliver food with **less physical contact**—a small but meaningful way to support safer days on campus during the pandemic.

Years later, this project still matters to me. It is not just code; it is proof that I could turn a real problem at school into something useful. If you are viewing this repository, thank you for taking the time to look at where my journey started.

— **Haani Shahrul** · © 2021

---

## Live demo

<!-- Replace the URL below after you deploy -->
**Live site:** _Coming soon_

> GitHub shows source code only. The working app runs on a PHP host (e.g. [InfinityFree](https://infinityfree.com)). See **Deploy online** below.

---

## What this project does

- **Public storefront** — browse menu, cart, checkout, customer login/signup  
- **Admin panel** — manage menu, categories, orders, site settings  
- **Order flow** — customers place orders; staff confirm and track them  
- **School branding** — logos, cover image, gallery, and about content via admin settings  

## Tech stack

- PHP  
- MySQL (database: `db_adha`)  
- HTML, CSS, JavaScript  
- Bootstrap 4  

---

## Requirements

- [XAMPP](https://www.apachefriends.org/) (Apache + MySQL + PHP), **or**
- PHP 7.4+ and MySQL/MariaDB with Apache (or PHP built-in server for quick tests)

---

## Installation (XAMPP)

1. **Install XAMPP** and start **Apache** and **MySQL**.

2. **Copy this project** into your web root, for example:
   ```
   C:\xampp\htdocs\Online_Food_Ordering_System
   ```
   On macOS:
   ```
   /Applications/XAMPP/htdocs/Online_Food_Ordering_System
   ```

3. **Create the database**
   - Open [phpMyAdmin](http://localhost/phpmyadmin)
   - Create a database named **`db_adha`**
   - Click **`db_adha`** in the left sidebar, then **Import** → `database/db_adha.sql`  
   - (On free hosts like InfinityFree, create the database in the hosting panel first — the SQL file does not run `CREATE DATABASE`.)

4. **Database connection** (if needed)  
   Copy `config/config.example.php` to `config/config.php` and set local MySQL details (XAMPP defaults are already in the example comments).

5. **Run the site**
   - Storefront: `http://localhost/Online_Food_Ordering_System/`
   - Or with PHP’s built-in server from the project folder:
     ```bash
     php -S localhost:8000
     ```
     Then open: `http://localhost:8000`

6. **Admin panel**  
   Log in from the site login page with admin credentials (below), or go to:
   `http://localhost/Online_Food_Ordering_System/admin/`

---

## Default login

| Role | Username / email | Password |
|------|------------------|----------|
| **Admin** | `admin` | `admin123` |

> Change these passwords before deploying to a live server.

Sample customer accounts may exist in the imported SQL dump (`user_info` table).

---

## Project structure (overview)

```
├── index.php              # Storefront router (?page=home|about|cart_list|checkout)
├── pages/                 # Storefront views (included by router)
├── login.php / signup.php # Customer auth (standalone)
├── header.php / footer.php
├── config/
│   ├── config.example.php # Copy to config.php (gitignored)
│   └── db_connect.php     # MySQL connection
├── admin/                 # Admin panel
│   ├── index.php          # Admin router
│   ├── ajax.php           # Form/AJAX actions
│   └── ...
├── includes/              # Shared PHP helpers
├── database/
│   └── db_adha.sql        # Schema & sample data
├── docs/
│   └── ARCHITECTURE.md    # Request flow & layout
└── assets/img/            # Uploaded images (logos, menu photos)
```

---

## Deploy online (free)

Example flow for [InfinityFree](https://infinityfree.com) (other PHP hosts are similar):

1. Create a hosting account and a MySQL database in the control panel (note hostname, database name, user, password).
2. In phpMyAdmin, select your database, then **Import** → `database/db_adha.sql` (create the database in the panel first — the SQL file does not run `CREATE DATABASE`).
3. Upload the project to `htdocs` so `index.php` is at the web root.
4. On the server only, copy `config/config.example.php` → `config/config.php` and enter your MySQL details. Do not commit real passwords to Git. Legacy `admin/config.php` still works if you already use it.
5. Open the storefront URL, then `/admin/`. Change the default admin password.
6. **Optional:** add GitHub Actions secrets `FTP_SERVER`, `FTP_USERNAME`, `FTP_PASSWORD` — the workflow in `.github/workflows/deploy-infinityfree.yml` deploys on push to `main` and skips `config/config.php`.

---

## Notes for developers

- See **[docs/ARCHITECTURE.md](docs/ARCHITECTURE.md)** for request flow and folder roles.
- The `archived` column on `orders` is added automatically on first admin load if missing.
- Production DB credentials go in `config/config.php` (gitignored), not in Git. Legacy `admin/config.php` is still supported if present.
- Site name, logos, and content are managed under **Admin → Site settings**.
- Copyright: © 2021 **Haani Shahrul**

---

## License & use

This project was built for learning and for my school’s canteen during COVID-19. Feel free to study the code; please credit **Haani Shahrul** if you share or adapt it.

**Built with care during a difficult time—and still one of my proudest first projects.**
