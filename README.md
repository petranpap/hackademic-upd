```
 ██╗  ██╗ █████╗  ██████╗██╗  ██╗ █████╗ ██████╗ ███████╗███╗   ███╗██╗ ██████╗
 ██║  ██║██╔══██╗██╔════╝██║ ██╔╝██╔══██╗██╔══██╗██╔════╝████╗ ████║██║██╔════╝
 ███████║███████║██║     █████╔╝ ███████║██║  ██║█████╗  ██╔████╔██║██║██║
 ██╔══██║██╔══██║██║     ██╔═██╗ ██╔══██║██║  ██║██╔══╝  ██║╚██╔╝██║██║██║
 ██║  ██║██║  ██║╚██████╗██║  ██╗██║  ██║██████╔╝███████╗██║ ╚═╝ ██║██║╚██████╗
 ╚═╝  ╚═╝╚═╝  ╚═╝ ╚═════╝╚═╝  ╚═╝╚═╝  ╚═╝╚═════╝ ╚══════╝╚═╝     ╚═╝╚═╝ ╚═════╝
```

**OWASP Hackademic Challenges — PHP 8 Edition**  
*Learn web security by attacking deliberately vulnerable applications in a safe, controlled environment.*

![PHP](https://img.shields.io/badge/PHP-8.x-777BB4?logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-5.7%2B-4479A1?logo=mysql&logoColor=white)
![License](https://img.shields.io/badge/License-GPLv3-green)
![OWASP](https://img.shields.io/badge/OWASP-Hackademic-blue)
![Status](https://img.shields.io/badge/status-active-brightgreen)
![Live](https://img.shields.io/badge/live-hackademic.cycollege.dev-33ff33)

---

## Overview

This is an updated fork of the [OWASP Hackademic Challenges Project](https://owasp.org/www-project-hackademic-challenges/), originally developed at the University of Thessaly. This version has been **migrated to PHP 8**, re-architected with PDO, and is actively used in the **Network & Application Security** course at [Cyprus College](https://cycollege.ac.cy), Limassol.

Students register, log in, and attempt a series of intentionally vulnerable web applications and cryptography puzzles — learning offensive techniques so they can build better defences.

---

## Features

- 20+ challenges covering web attacks and cryptography
- User registration, login, session management (bcrypt passwords, PDO)
- Per-challenge XML metadata (title, category, difficulty, description)
- Challenge completion tracking with database persistence
- Leaderboard and user profiles
- Bilingual landing page (Greek / English) with terminal aesthetic UI

---

## Tech Stack

| Layer      | Technology                        |
|------------|-----------------------------------|
| Backend    | PHP 8.x, PDO/MySQL                |
| Frontend   | HTML/CSS, Bootstrap 5, vanilla JS |
| Database   | MySQL 5.7+                        |
| Auth       | `password_hash()` / bcrypt        |
| Web server | Apache / Nginx (with mod_rewrite) |

---

## Challenge Index

### Web Challenges

| ID     | Title       | Category | Notes                          |
|--------|-------------|----------|--------------------------------|
| ch001  | Challenge 1 | Web      | Classic web exploitation       |
| ch002  | Challenge 2 | Web      |                                |
| ch003  | Challenge 3 | Web      |                                |
| ch004  | Challenge 4 | Web      |                                |
| ch005  | Challenge 5 | Web      |                                |
| ch006  | Challenge 6 | Web      |                                |
| ch007  | Challenge 7 | Web      |                                |
| ch008  | Challenge 8 | Web      |                                |
| ch009  | Challenge 9 | Web      |                                |
| ch010  | Challenge 10| Web      |                                |
| cookiEng | cookiEng | Web      | Cookie manipulation / IDOR     |
| izon   | izon        | Web      | Authorization bypass           |

### Cryptography Challenges

| ID     | Title                              | Category | Difficulty |
|--------|------------------------------------|----------|-----------|
| ch011  | Fun with Frequencies               | Crypto   | 2 / 10    |
| ch012  | OTP Challenge                      | Crypto   | 4 / 10    |
| ch013  | Silly MACs                         | Crypto   | —         |
| ch014  | RSA Challenge I: Bad Primes        | Crypto   | 6 / 10    |
| ch015  | RSA Challenge II: Common Modulus   | Crypto   | 6 / 10    |
| ch016  | RSA Challenge III: Low Enc. Exp.   | Crypto   | 7 / 10    |
| ch017  | Meet in the Middle                 | Crypto   | 8 / 10    |
| ch018  | Blinding Signatures                | Crypto   | 6 / 10    |
| ch020  | RSA Challenge IV: Low Private Exp. | Crypto   | 7 / 10    |

---

## Quick Start

### Requirements

- PHP 8.0+
- MySQL 5.7+ (or MariaDB 10.3+)
- Apache or Nginx
- `pdo_mysql` extension enabled

### 1 — Clone & configure

```bash
git clone https://github.com/petranpap/hackademic-upd.git
cd hackademic-upd
```

Edit `includes/config.php` with your database credentials:

```php
define("DB_DSN",  "mysql:host=localhost;dbname=hackademic_db;charset=utf8mb4");
define("DB_USER", "your_db_user");
define("DB_PASS", "your_db_password");
```

### 2 — Create the database

```sql
CREATE DATABASE hackademic_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

Then import the schema (see `db/schema.sql` if provided, or create the tables below):

```sql
CREATE TABLE users (
    id            INT AUTO_INCREMENT PRIMARY KEY,
    username      VARCHAR(50)  NOT NULL UNIQUE,
    email         VARCHAR(100) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    created_at    TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE challenges (
    id        INT AUTO_INCREMENT PRIMARY KEY,
    title     VARCHAR(100) NOT NULL,
    file_path VARCHAR(100) NOT NULL
);

CREATE TABLE completions (
    id           INT AUTO_INCREMENT PRIMARY KEY,
    user_id      INT NOT NULL,
    challenge_id INT NOT NULL,
    completed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY uq_user_challenge (user_id, challenge_id)
);
```

### 3 — Seed challenges

```sql
INSERT INTO challenges (title, file_path) VALUES
  ('Challenge 1',  'ch001'),
  ('Challenge 2',  'ch002'),
  -- ... add remaining challenges
  ('cookiEng',     'cookiEng'),
  ('izon',         'izon');
```

### 4 — Serve

Point your web server document root at the project directory. For local development:

```bash
php -S localhost:8080
```

Then open `http://localhost:8080`.

---

## Project Structure

```
hackademic-upd/
├── index.html              # Bilingual landing page
├── main.php                # Challenge browser (requires auth)
├── leaderboard.php         # Score rankings
├── profile.php             # User profile
├── track_completion.php    # AJAX completion endpoint
│
├── auth/
│   ├── login.php
│   ├── logout.php
│   └── register.php
│
├── includes/
│   ├── config.php          # DB connection (PDO)
│   └── session.php         # Session guard
│
├── challenges/
│   ├── ch001/ … ch020/     # Individual challenge apps
│   │   ├── index.php       # Vulnerable application
│   │   └── *.xml           # Challenge metadata
│   ├── cookiEng/
│   └── izon/
│
└── assets/
    ├── css/
    └── images/
```

---

## Adding a New Challenge

1. Create a directory under `challenges/` (e.g. `challenges/myChallenge/`)
2. Add `index.php` — the intentionally vulnerable application
3. Add `myChallenge.xml` with the following structure:

```xml
<?xml version="1.0" encoding="UTF-8"?>
<challenge>
    <title>My Challenge Title</title>
    <author>Your Name</author>
    <category>web</category>   <!-- web | crypto -->
    <description>
        <![CDATA[ Challenge narrative here. ]]>
    </description>
    <level>5</level>           <!-- 1–10 -->
    <duration>60</duration>    <!-- suggested minutes -->
</challenge>
```

4. Insert a row into the `challenges` table with `file_path` matching the directory name.

---

## Security & Ethical Use

> **This platform contains deliberately vulnerable code. Deploy only in isolated, controlled environments — never expose to the public internet without proper network-level restrictions.**

- Intended for **authorized educational use** only
- Challenges simulate real attack techniques (SQLi, XSS, broken auth, weak crypto) in sandboxed apps
- Do not host without access controls; challenge apps are intentionally exploitable
- Suitable for: university courses, CTF warm-up, internal security training

---

## Credits

**Original project** — University of Thessaly / OWASP Greece  
Andreas Venieris · Anastasios Stasinopoulos · Dr. Vasilis Vlachos · Dr. Alexandros Papanikolaou · Dr. Konstantinos Papapanagiotou

**PHP 8 Port & Maintenance**  
[Petros Papagiannis](https://petrospapagiannis.com) — CS Instructor, Cyprus College Limassol · PhD Researcher, CUT/TEPAK  
`peterpapagiannis@yahoo.com`

---

## License

GNU General Public License v3.0 — see [LICENSE](https://www.gnu.org/licenses/gpl-3.0.html) for details.

---

*Hosted at [hackademic.cycollege.dev](https://hackademic.cycollege.dev) · Part of the Network & Application Security curriculum at Cyprus College*
