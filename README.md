# Health Vault

A medical card generation and retrieval system. Administrators create digital
medical-card records; each record gets a unique reference number that the
public can use to instantly look up and print essential medical information —
useful for emergencies, hospital visits, and routine checkups.

This project was built as a practical, functional recreation of the
"Health Vault" project specification (Admin module + User module, medical
cards, inquiries, page management, reports, and authentication/security).

---

## 1. Feature List

**Public (User) side**
- Home page with service overview and navigation
- Medical Card lookup by reference number (with clear error on invalid reference)
- Medical card detail view + clean, print-friendly print view
- About Us page (content managed by admin, stored in the database)
- Contact Us page with a working inquiry form (stored in the database)

**Admin side**
- Secure session-based login / logout, with idle session timeout
- Dashboard with **live** stats: total cards, created today, created
  yesterday, created in the last 7 days, unread inquiries, read inquiries
- Medical Card management: list, search (by reference number or name), add,
  view, edit, delete (with confirmation)
- Duplicate reference numbers are rejected on add/edit
- Inquiry management: list (filter by read/unread), view (auto-marks as
  read), toggle read/unread, and respond (response is stored and shown in
  the admin panel)
- Page management: edit About Us and Contact Us content, reflected
  immediately on the public site
- Reports: generate a medical-card activity report for a date range, with
  total record count, empty-state handling, and a print-friendly view
- Admin profile management (name, email, mobile number)
- Change password (requires current password)
- Forgot / reset password flow using secure, expiring, single-use tokens

**Security**
- PDO prepared statements everywhere (no raw string-built SQL)
- Passwords hashed with `password_hash()` / verified with `password_verify()`
- CSRF tokens on all state-changing forms
- Session-based auth with protected `/admin/*` routes and idle timeout
- All output escaped with `htmlspecialchars()` via the `h()` helper
- Password reset tokens are hashed at rest and expire after 30 minutes
  (see `RESET_TOKEN_TTL_MINUTES` in `config/config.php`)
- Raw SQL/database errors are never shown to end users (see `includes/db.php`)

---

## 2. Technology Stack

- **Backend:** PHP 8.x, PDO (MySQL driver)
- **Database:** MySQL 8.x / MariaDB (compatible with a standard XAMPP/WAMP/MAMP setup)
- **Frontend:** Plain HTML5, CSS3 (custom, no framework), vanilla JavaScript
- No Node/React/build step required — this is a classic server-rendered PHP app.

---

## 3. Requirements

- PHP 8.0+ with the `pdo_mysql` extension enabled
- MySQL 5.7+/8.x or MariaDB 10.x
- Apache (via XAMPP/WAMP/MAMP/LAMP) or PHP's built-in server for local testing

---

## 4. Folder Structure

```
health-vault/
├── admin/
│   ├── login.php, logout.php, dashboard.php
│   ├── forgot-password.php, reset-password.php
│   ├── profile.php, change-password.php
│   ├── medical-cards/  (index, add, edit, view, delete)
│   ├── inquiries/      (index, view, respond)
│   ├── pages/           (index, about, contact)
│   └── reports/        (index)
├── public/
│   ├── index.php, medical-card.php, print-card.php, about.php, contact.php
├── includes/
│   ├── db.php, auth.php, functions.php
│   ├── header.php, footer.php, admin-header.php, admin-footer.php, admin-sidebar.php
├── assets/
│   ├── css/style.css, css/print.css, js/main.js, images/
├── database/
│   ├── schema.sql, seed.sql
├── config/
│   └── config.php
├── index.php   (redirects to public/index.php)
└── README.md
```

---

## 5. Database Setup

1. Create the database and load the schema:
   ```sql
   -- via phpMyAdmin or the mysql CLI
   SOURCE database/schema.sql;
   SOURCE database/seed.sql;
   ```
   (`schema.sql` also contains `CREATE DATABASE IF NOT EXISTS health_vault;`,
   so importing it alone is enough to create the database.)

2. Tables created: `admins`, `medical_cards`, `inquiries`, `pages`,
   `password_resets`.

---

## 6. Installation (XAMPP-style local setup)

1. **Install XAMPP** (or WAMP/MAMP/LAMP) with Apache + MySQL.
2. **Start Apache and MySQL** from the control panel.
3. **Create the database** — open phpMyAdmin and import, in order:
   - `database/schema.sql`
   - `database/seed.sql`
4. **Configure the connection** — edit `config/config.php`:
   ```php
   define('DB_HOST', 'localhost');
   define('DB_NAME', 'health_vault');
   define('DB_USER', 'root');
   define('DB_PASS', '');
   ```
   (These can also be overridden via environment variables `HV_DB_HOST`,
   `HV_DB_NAME`, `HV_DB_USER`, `HV_DB_PASS` if you prefer not to hardcode them.)
5. **Place the project** in your web root, e.g. `C:\xampp\htdocs\health-vault\`.
6. **Open the app**: `http://localhost/health-vault/`

For quick local testing without Apache, you can also use PHP's built-in
server from the project root:
```
php -S localhost:8000
```
then visit `http://localhost:8000/`.

---

## 7. Seed Data & Admin Credentials

The seed data (`database/seed.sql`) creates:

- **1 admin account**
  - Email: `admin@healthvault.local`
  - Password: `Admin@123`
- **6 medical cards** with dates spread across today, yesterday, the last 7
  days, and older — so the dashboard stats are meaningful immediately.
- **4 inquiries** — a mix of read and unread — so inquiry management can be
  tested immediately.
- Editable **About Us** and **Contact Us** page content.

> The admin password above is stored as a bcrypt hash via `password_hash()`
> — never as plaintext. Change it after first login in production use.

---

## 8. Public & Admin Routes

**Public**
| Route | Purpose |
|---|---|
| `/public/index.php` | Home |
| `/public/medical-card.php?reference=...` | Look up a medical card |
| `/public/print-card.php?reference=...` | Print-friendly card view |
| `/public/about.php` | About Us |
| `/public/contact.php` | Contact Us / submit an inquiry |

**Admin** (all require login except `login.php`, `forgot-password.php`, `reset-password.php`)
| Route | Purpose |
|---|---|
| `/admin/login.php` | Admin login |
| `/admin/logout.php` | Logout |
| `/admin/dashboard.php` | Dashboard with live stats |
| `/admin/medical-cards/index.php` | List / search medical cards |
| `/admin/medical-cards/add.php` | Add a medical card |
| `/admin/medical-cards/edit.php?id=` | Edit a medical card |
| `/admin/medical-cards/view.php?id=` | View a medical card |
| `/admin/medical-cards/delete.php` | Delete (POST only) |
| `/admin/inquiries/index.php` | List inquiries |
| `/admin/inquiries/view.php?id=` | View / respond to an inquiry |
| `/admin/pages/index.php` | Page management overview |
| `/admin/pages/about.php` | Edit About Us content |
| `/admin/pages/contact.php` | Edit Contact Us content |
| `/admin/reports/index.php?from=&to=` | Medical card activity report |
| `/admin/profile.php` | Edit admin profile |
| `/admin/change-password.php` | Change password |
| `/admin/forgot-password.php` | Request a password reset |
| `/admin/reset-password.php?token=` | Complete a password reset |

---

## 9. Password Reset — Development Behavior

No external/paid email service is wired into this build. When a password
reset is requested:

1. A secure, random token is generated and its **hash** (not the raw token)
   is stored in `password_resets` with a 30-minute expiry.
2. Because no email service is configured, the app does **not** pretend an
   email was sent. Instead, when `APP_DEBUG` is `true` in
   `config/config.php` (the default for local development), the reset link
   is displayed directly on the "Forgot Password" page in a clearly labeled
   development box.
3. In a real deployment, set `APP_DEBUG` to `false` and wire up an actual
   mail sender (e.g. PHPMailer + SMTP) inside `admin/forgot-password.php`
   at the point marked for that purpose — the token-generation and
   validation logic already in place does not need to change.

---

## 10. How to Test (Manual)

Start the app (via XAMPP or `php -S localhost:8000`) and walk through:

1. Visit the home page, About Us, and Contact Us — submit the contact form.
2. Log in to `/admin/login.php` with the seed credentials.
3. Check the dashboard — stat numbers should match what's in the database.
4. Add, view, edit, search, and delete a medical card.
5. Look up that card's reference number on the public Medical Card page,
   then print it.
6. View an inquiry in the admin panel (it becomes "read"), mark it back to
   unread, then send a response.
7. Edit the About Us / Contact Us content in Pages and confirm the public
   pages reflect the change immediately.
8. Generate a report for a date range with and without results.
9. Update your profile, then change your password, then log out and log
   back in with the new password.
10. Use "Forgot Password" to generate and follow a reset link, set a new
    password, and confirm the old password no longer works.

---

## 11. Mandatory Test Cases

All five test cases below have been implemented and manually verified
end-to-end against a live MySQL database during development:

| ID | Scenario | Expected Result | Status |
|---|---|---|---|
| TC01 | Admin logs in with valid credentials | Redirected to dashboard | ✅ Pass |
| TC02 | User enters an invalid reference number | Clear error message shown | ✅ Pass |
| TC03 | Admin adds a new medical card | Record saved in the database | ✅ Pass |
| TC04 | User views and prints a medical card | Printable format displays correctly | ✅ Pass |
| TC05 | Admin resets password | Password updated, confirmation shown, new password works for login | ✅ Pass |

Additional verified behaviors: invalid login rejection, duplicate reference
rejection, logout + protected-route enforcement, CSRF rejection of forged
submissions, report date-range validation (including From > To and
empty-result handling), and public page content updating immediately after
an admin edit.

---

## 12. Known Limitations

- No outbound email is sent for inquiry responses or password resets — both
  are handled via in-app storage/display, as required for a setup with no
  paid external email service. See section 9 for how to add real email
  delivery later.
- No file/image upload UI is included; `assets/images/` is provided as a
  ready-to-use folder for you to drop in your own logo/photos.
- Reports are on-screen + browser-print only; CSV export is not implemented
  (was explicitly marked optional/secondary in the source specification).
- There is a single admin role — no multi-admin permission levels.
- Rate limiting/lockout after repeated failed logins is not implemented.

## 13. Future Improvements

- Add real transactional email (e.g. PHPMailer + SMTP) for inquiry
  responses and password resets.
- Add CSV export for reports.
- Add pagination for large medical-card / inquiry lists.
- Add multi-admin roles and an activity/audit log.
- Add login rate-limiting / account lockout after repeated failures.

---

## 14. Security Notes

- Change the seed admin password immediately in any shared/production
  environment.
- Set `APP_DEBUG` to `false` in `config/config.php` before deploying
  publicly — this disables verbose error output and hides the development
  password-reset link box.
- Serve the app over HTTPS in production so session cookies and submitted
  credentials are encrypted in transit.
