# EzFoodAdha.my — Online Food Ordering System

**My first website. A project I am genuinely proud of.**

During my high school years, the world changed because of COVID-19. Like many schools, ours had to rethink how everyday things worked—including the canteen. Queues, crowded counters, and close contact were risks we wanted to reduce.

This was the first website I ever built. I created **EzFoodAdha.my** so students and staff could browse the canteen menu, place orders, and help the school deliver food with **less physical contact**—a small but meaningful way to support safer days on campus during the pandemic.

Years later, this project still matters to me. It is not just code; it is proof that I could turn a real problem at school into something useful. If you are viewing this repository, thank you for taking the time to look at where my journey started.

— **Haani Shahrul** · © 2021

---

## What this project does

- **Public storefront** — browse menu, cart, checkout, customer login/signup  
- **Admin panel** — manage menu, categories, orders, site settings  
- **Order flow** — customers place orders; staff confirm and track them  
- **School branding** — logos, cover image, gallery, and about content via admin settings  

## Tech stack

- PHP  
- MySQL (database: `ADHA_db`)  
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
   - Create a database named **`ADHA_db`**
   - Import: `database/ADHA_db.sql`

4. **Database connection** (if needed)  
   Edit `admin/db_connect.php` to match your MySQL user/password:
   ```php
   $conn = new mysqli('127.0.0.1', 'root', '', 'ADHA_db', 3306);
   ```

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
├── index.php          # Main storefront router
├── home.php           # Home / menu browsing
├── cart_list.php      # Shopping cart
├── checkout.php       # Checkout
├── login.php / signup.php
├── admin/             # Admin panel
│   ├── index.php
│   ├── home.php       # Dashboard
│   └── ...
├── database/
│   └── ADHA_db.sql    # Database schema & sample data
└── assets/img/        # Uploaded images (logos, menu photos)
```

---

## Notes for developers

- The `archived` column on `orders` is added automatically on first admin load if missing.
- Site name, logos, and content are managed under **Admin → Site settings**.
- Copyright: © 2021 **Haani Shahrul**

---

## License & use

This project was built for learning and for my school’s canteen during COVID-19. Feel free to study the code; please credit the original author if you share or adapt it.

**Built with care during a difficult time—and still one of my proudest first projects.**
