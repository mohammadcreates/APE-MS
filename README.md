# 🦍 APE — Health Club Management System

> A full-stack, semi-automated web application for managing gyms, martial arts studios, and sports centers — built as a Senior Capstone Project at the Lebanese International University.

---

## 📋 Abstract

APE solves the real-world operational challenges health clubs face daily: time-consuming member sign-ups, disorganized class schedules, and difficulty tracking payments. It delivers a secure, user-friendly platform that reduces manual workload, gives administrators full control, and even generates personalized AI-powered workout plans for members.

---

## ✨ Features

- 👤 **Member Management** — Register and track regular members, PT clients, and potential leads
- 🗓️ **Class & Schedule Management** — Create recurring classes and assign trainers based on availability
- 🤖 **AI Workout Generator** — Personalized workout plans powered by AI, backed by a comprehensive exercise database *(2-minute cooldown between generations)*
- 💰 **Financial Management** — Record payments, track pending/completed transactions, and generate revenue reports
- 📊 **Financial Analytics** — Visual reports using Google Charts (expected vs. actual revenue)
- 🔐 **Role-Based Access Control** — Admin and staff roles with separate permissions
- 🛡️ **Security-First Design** — bcrypt hashing, PDO prepared statements, session protection, and input sanitization

---

## 🛠️ Tech Stack

| Layer         | Technology                              |
|---------------|-----------------------------------------|
| Frontend      | HTML5, CSS3, Bootstrap 5, JavaScript    |
| Backend       | PHP 8.1                                 |
| Database      | MySQL 8.0 (via PDO)                     |
| Charts        | Google Charts                           |
| Hosting       | InfinityFree (HTTPS)                    |

---

## 🏗️ Architecture

APE follows a **three-tier architecture**:

- **Presentation Layer** — Responsive UI built with Bootstrap 5, custom CSS, and JavaScript DOM manipulation
- **Application Layer** — PHP 8.1 with modular server-side logic, session handling, and server-side validation
- **Data Layer** — MySQL 8.0 with 10 normalized, indexed tables accessed exclusively through PDO prepared statements

---

## 📁 Folder Structure

```
project-root/
├── aiplanner/                  # AI Workout Generator module
│   ├── index.php
│   ├── workout.php
│   ├── script.js
│   └── style.css
│
├── lib/                        # Backend logic & handlers
│   ├── config.php
│   ├── login.php
│   ├── register-members.php
│   ├── register-ptmembers.php
│   ├── register-potentials.php
│   ├── add-class.php
│   ├── add-trainer.php
│   ├── revenues.php
│   └── ...
│
├── navbars/                    # Reusable navigation components
│   ├── sidenavbar.php
│   ├── uppernavbar.php
│   └── login.php
│
├── public/                     # Static assets
│   ├── css/
│   ├── js/
│   └── img/
│
├── dashboard.php               # Main dashboard
├── members.php                 # Member payment records
├── ptmembers.php               # PT member records
├── potentials.php              # Lead tracking
├── classes.php                 # Class listings
├── financial-reports.php       # Financial analytics
├── admin.php                   # User management (admin only)
├── login.php
└── database-setting.php
```

---

## ⚙️ Installation & Setup

### Prerequisites

- PHP 8.1+
- MySQL 8.0+
- Apache/Nginx web server (or XAMPP / WAMP locally)

### Steps

1. **Clone the repository**
   ```bash
   git clone https://github.com/mohammadcreates/APE-MS.git
   cd APE-MS
   ```

2. **Create and import the database**
   ```bash
   mysql -u root -p ape_db < database/ape_db.sql
   ```

3. **Configure the database connection**

   Open `lib/config.php` and update:
   ```php
   $host     = "localhost";
   $dbname   = "ape_db";
   $username = "your_username";
   $password = "your_password";
   ```

4. **Serve the application**
   - Place the project in your server root (e.g., `htdocs` for XAMPP)
   - Visit `http://localhost/APE-MS/` in your browser

---

## 🌐 Deploying on InfinityFree

1. Sign up at [infinityfree.net](https://infinityfree.net/)
2. Create a hosting account and note your FTP credentials
3. Upload all project files via FTP (e.g., FileZilla)
4. Create a MySQL database from the InfinityFree control panel
5. Import your SQL file via the provided phpMyAdmin
6. Update `lib/config.php` with your InfinityFree DB host, name, and credentials

---

## 🔐 Security

| Concern              | Implementation                                      |
|----------------------|-----------------------------------------------------|
| Password Storage     | bcrypt hashing                                      |
| SQL Injection        | PDO prepared statements (exclusively)               |
| XSS Prevention       | `htmlspecialchars()` on all output                  |
| Session Hijacking    | Session ID regeneration on privilege escalation     |
| Input Validation     | Both client-side and server-side validation         |

---

## 📖 Key Pages

| Page                    | Description                                              |
|-------------------------|----------------------------------------------------------|
| `login.php`             | Login and account creation                              |
| `dashboard.php`         | Overview: member count, PT members, revenue summary     |
| `members.php`           | View and manage member payments                         |
| `registered-members.php`| Record new memberships or service payments              |
| `ptmembers.php`         | View and manage PT client payments                      |
| `potentials.php`        | Track and follow up on leads                            |
| `classes.php`           | View and delete scheduled classes                       |
| `add-class.php`         | Schedule a new class and assign a trainer               |
| `aiplanner/index.php`   | AI workout generator                                    |
| `financial-reports.php` | Revenue reports with filters and Google Charts visuals  |
| `admin.php`             | Admin-only user role management                         |

---

## 🗂️ Use Cases

| ID    | Use Case               | Actor | Description                                              |
|-------|------------------------|-------|----------------------------------------------------------|
| UC-1  | Register Member        | Staff | Record a member's payment and profile into the system    |
| UC-2  | Schedule Class         | Staff | Create a class session and assign a trainer              |
| UC-3  | Generate Workout Plan  | Staff | Use the AI planner to generate a custom workout          |
| UC-4  | View Financial Report  | Staff | Access revenue summaries filtered by various criteria    |

---

## 📅 Project Timeline

| Phase                              | Timeline              |
|------------------------------------|-----------------------|
| Storyboard                         | Week 1 — Mar 3, 2025  |
| Tools & Tech Setup                 | Week 2 — Mar 10, 2025 |
| Business Process, ERD & Use Cases  | Week 3 — Mar 17, 2025 |
| Backend Development (PHP, MySQL)   | Weeks 4–6             |
| Frontend Development (HTML, CSS)   | Weeks 7–8             |
| AI Planner Integration             | Week 9 — Apr 28, 2025 |
| Functionality Testing              | Week 10 — May 5, 2025 |
| Cloud Hosting & Final Testing      | Weeks 10–11           |
| GitHub & Final Report              | Weeks 11–12           |
| Presentation                       | Week 13 — May 26, 2025|

---

## 🔭 Future Improvements

- Real-time notification system for members and staff
- Full automation for recurring class scheduling
- Online payment gateway integration
- AI workout generator that adapts to member feedback and performance history

---

## 👤 Author

**Mohammad Walid Al Masri**
Senior Project — B.Sc. Computer Science & Information Technology
Lebanese International University, Lebanon — Spring 2025–2026
Supervised by **Dr. Mohamad Jaafar Nehme**

---

*"So be patient, with gracious patience. They see it distant, but we see it near." — Quran 70:5–7*
