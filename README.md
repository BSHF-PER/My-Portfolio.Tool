 
<p align="center">
  <img src="https://img.shields.io/badge/PHP-7.4%2B-777BB4?logo=php&logoColor=white" alt="PHP">
  <img src="https://img.shields.io/badge/MySQL-5.7%2B-4479A1?logo=mysql&logoColor=white" alt="MySQL">
  <img src="https://img.shields.io/badge/License-MIT-green.svg" alt="License">
  <img src="https://img.shields.io/badge/Version-1.0.0-blue" alt="Version">
  <img src="https://img.shields.io/badge/Status-Stable-success" alt="Status">
</p>

<h1 align="center">🚀 My-Portfolio.Tool</h1>

<p align="center">
  <strong>A Lightweight, Hassle-Free Portfolio Website Builder — No WordPress. No Bloat. Just You.</strong>
  <br>
  <sub>Built for developers, designers, and freelancers who want to ship a stunning portfolio in minutes.</sub>
</p>

<p align="center">
  <a href="#-quick-start">📦 Quick Start</a> •
  <a href="#-features">✨ Features</a> •
  <a href="#-installation">🛠 Installation</a> •
  <a href="#-admin-panel">🎛 Admin Panel</a> •
  <a href="#-troubleshooting">❓ FAQ</a>
</p>

<p align="center">
  <a href="#-screenshots">📸 View Screenshots</a>
</p>

---

## 🎯 Overview

**My-Portfolio.Tool** is a self-hosted, ultra-lightweight web application designed to help you build a professional portfolio website in under 5 minutes — without the complexity of heavy CMS platforms like WordPress, the clutter of plugins, or the learning curve of modern frontend frameworks.

Everything you need is in one clean package: **Pure PHP**, **MySQL**, and **multiple premium templates**. Upload the files, run a single installer, and you're live.

> 💡 **Perfect for:** Developers, UI/UX designers, photographers, freelancers, students, and anyone who wants a personal site without the overhead.

---

## ✨ Features

| Category | Capabilities |
|---|---|
| 🎨 **Design** | 7+ premium responsive templates: Modern, Classic, Minimal, Creative, Dark, Glassmorphism, Zenith |
| ⚡ **Performance** | Built with pure PHP — no frameworks, no dependencies, blazing-fast load times |
| 🔐 **Security** | Bcrypt-hashed passwords, session-based auth, prepared SQL statements (PDO) |
| 🖼️ **Portfolio** | Projects with images, videos, tags, GitHub links, live demo URLs, and categories |
| ⚙️ **Admin Panel** | Full dashboard, site settings, contact manager, template switcher, logo upload |
| 🌐 **SEO Ready** | Built-in meta tags, semantic HTML, and clean URLs |
| 📱 **Responsive** | Mobile-first design — looks perfect on every device |

---

## 📸 Screenshots

<details>
<summary><strong>Click to expand preview images</strong></summary>
<br>

> *Soon...*

</details>

---

## 📦 Quick Start

For those who want to get running immediately:

```bash
# 1. Clone the repository
git clone https://github.com/BSHF-PER/My-Portfolio.Tool.git
cd My-Portfolio.Tool

# 2. Create the database `my_portfolio` in phpMyAdmin (see Installation section)
# 3. Copy contents of `www/` directly to XAMPP's `htdocs/` (or WAMP's `www/`)
# 4. Visit: http://localhost/install.php
# 5. Login with: admin / admin123
# 6. DELETE install.php after setup
```

---

## 🛠 Installation

Follow these steps carefully. The installation process has **three stages**: Database Setup, File Deployment, and Running the Installer.

### 📌 Prerequisites

Before you begin, make sure you have one of these environments ready:

| Tool | Purpose |
|---|---|
| **XAMPP** or **WAMP** (or MAMP/LAMP) | Local Apache + MySQL server |
| **phpMyAdmin** | Database management (comes bundled with XAMPP/WAMP) |
| **PHP 7.4+** | Required PHP version |
| **MySQL 5.7+** | Required database version |

Required PHP extensions: `pdo`, `pdo_mysql`, `fileinfo` (usually enabled by default).

---

### 🔹 Stage 1: Create the Database via phpMyAdmin

Before running the installer, you must manually create an **empty database** named `my_portfolio`. The installer will connect to this existing database and populate it with tables.

**Step-by-step:**

1. Start **Apache** and **MySQL** from your XAMPP/WAMP control panel.
2. Open your browser and navigate to:
   ```
   http://localhost/phpmyadmin
   ```
3. In the left sidebar, click **New** (or "Databases" tab at the top).
4. In the **Database name** field, type exactly:
   ```
   my_portfolio
   ```
5. In the **Collation** dropdown, select:
   ```
   utf8mb4_persian_ci
   ```
   > This ensures full UTF-8 support for Persian/Arabic characters and emojis.
6. Click **Create**.
7. You should see a confirmation: `Database my_portfolio has been created.`

✅ That's it! Your empty database is ready. Leave phpMyAdmin open — you'll verify the installation here later.

---

### 🔹 Stage 2: Deploy Files to Your Server Root

> ⚠️ **CRITICAL**: Unlike most PHP projects, the contents of the `www/` folder must be placed **directly in the server root** — NOT inside a subfolder.

**For XAMPP users:**
1. Open the cloned `My-Portfolio.Tool` project folder.
2. Inside, you'll find a `www/` folder.
3. **Select all contents inside `www/`** (files like `index.php`, `install.php`, folders like `admin/`, `includes/`, `templates/`, `assets/`).
4. Paste them **directly into**:
   ```
   C:\xampp\htdocs\
   ```
   
✅ **Correct structure in `htdocs`:**
```
C:\xampp\htdocs\
├── index.php
├── install.php
├── admin/
├── includes/
├── templates/
└── assets/
```

❌ **WRONG — do NOT do this:**
```
C:\xampp\htdocs\My-Portfolio.Tool\install.php
C:\xampp\htdocs\www\install.php
```

**For WAMP users:**
- Paste the contents of `www/` directly into:
  ```
  C:\wamp64\www\
  ```

> ⚠️ **Warning**: Since files go directly to the server root, any existing files in `htdocs/` or `www/` (like default XAMPP welcome pages) will be overwritten or mixed with your portfolio. **Only do this on a clean local server**, or consider using a subfolder for production environments.

---

### 🔹 Stage 3: Run the Installer

1. Open your browser and navigate to:
   ```
   http://localhost/install.php
   ```
   > Notice: **no folder name** in the URL — because files are in the server root.

2. The installer will automatically:
   - Connect to the `my_portfolio` database you just created
   - Create all required tables (`admins`, `portfolios`, `settings`, `contacts`, `templates`)
   - Insert default data (site settings, templates, admin account)
   - Create the upload directory at `assets/uploads/`

3. You'll see a **success screen** with your default credentials:
   ```
   📍 Portfolio Site:  http://localhost/
   📍 Admin Panel:     http://localhost/admin/login.php
   
   👤 Username: admin
   🔑 Password: admin123
   ```

4. **🚨 MANDATORY SECURITY STEP**:
   Immediately delete `install.php` from your `htdocs/` folder to prevent unauthorized re-installation.
   ```
   DELETE → C:\xampp\htdocs\install.php
   ```

5. Visit your live portfolio at `http://localhost/` and log in to the admin panel to customize it.

---

## 🎛 Admin Panel

Once logged in at `http://localhost/admin/login.php`, you'll have full control:

| Section | What You Can Do |
|---|---|
| 📊 **Dashboard** | Overview of total projects, active templates, and site statistics |
| 💼 **Portfolio Manager** | Add/edit/delete projects with images, videos, tags, GitHub links, live demos |
| ⚙️ **Site Settings** | Update site name, meta description, "About Me", footer text, and upload a custom logo |
| 🎨 **Template Switcher** | Instantly switch between 7+ premium templates with one click |
| 📞 **Contact Manager** | Add unlimited contact methods (Email, Phone, LinkedIn, GitHub, Telegram) with custom icons and display ordering |

---

## 📂 Project Structure

```
My-Portfolio.Tool/
│
├── www/                          # 📁 All files inside here go directly to htdocs/www
│   ├── admin/                    # 🔒 Admin dashboard, auth, portfolio & settings management
│   │   ├── login.php
│   │   ├── dashboard.php
│   │   ├── portfolios.php
│   │   ├── settings.php
│   │   └── contacts.php
│   │
│   ├── includes/                 # ⚙️ Core engine files
│   │   ├── db.php                # Database connection (PDO)
│   │   ├── auth.php              # Session & authentication logic
│   │   ├── config.php            # Global configuration
│   │   └── functions.php         # Helper functions
│   │
│   ├── templates/                # 🎨 All 7+ premium frontend templates
│   │   ├── modern.php
│   │   ├── classic.php
│   │   ├── minimal.php
│   │   ├── creative.php
│   │   ├── dark.php
│   │   ├── glass.php
│   │   └── zenith.php
│   │
│   ├── assets/                   # 🖼️ Static files & user uploads (auto-created)
│   │   ├── css/
│   │   ├── js/
│   │   ├── fonts/
│   │   └── uploads/
│   │
│   ├── index.php                 # 🏠 Public-facing homepage
│   └── install.php               # 🚀 One-time installer (DELETE AFTER USE)
│
├── docs/                         # 📖 Documentation (screenshots, guides)
├── .gitignore
└── LICENSE                       # MIT License
```

---

## ❓ Troubleshooting

<details>
<summary><strong>❌ "Database 'my_portfolio' not found" during installation</strong></summary>

Go back to **Stage 1** and verify:
- Database name is exactly `my_portfolio` (lowercase, underscore)
- MySQL service is running in XAMPP/WAMP control panel
- Refresh phpMyAdmin to confirm the database appears in the left sidebar
</details>

<details>
<summary><strong>❌ "Access denied for user 'root'@'localhost'"</strong></summary>

The installer uses default XAMPP credentials (`root` with no password). If you've set a MySQL password:
1. Open `install.php` in a code editor
2. Find the connection variables near the top (usually lines 5-7)
3. Update `$db_user` and `$db_pass` to match your credentials
4. Save and re-run the installer
</details>

<details>
<summary><strong>❌ "Permission denied" when uploading images</strong></summary>

The `assets/uploads/` folder needs write permissions:

- **Windows (XAMPP/WAMP)**: Right-click `uploads/` → Properties → Security → Give "Full Control" to Everyone/Users
- **Linux/macOS**: Run `chmod -R 777 assets/uploads/`
</details>

<details>
<summary><strong>❌ "Page Not Found" when visiting http://localhost/</strong></summary>

- Make sure Apache is running in the control panel
- Verify files were copied **directly to `htdocs/`**, not in a subfolder
- Check if `index.php` exists at `C:\xampp\htdocs\index.php`
- If you see the XAMPP welcome page instead, your files were placed in a subfolder — move them up one level
</details>

<details>
<summary><strong>❌ How do I change the default admin password?</strong></summary>

After login, you can update your credentials from the admin panel. If you're locked out:
1. go to admin Panel > Profile > Change Password
2. change password
</details>

---

## 🚀 Deploying to Production (Live Hosting)

You can deploy this project to any standard PHP hosting (cPanel, DirectAdmin, Plesk, etc.):

1. **On your hosting control panel**, use phpMyAdmin to create a database named `my_portfolio` (or any name — just update `install.php` accordingly).
2. Upload all contents of `www/` directly to your `public_html/` folder via FTP or File Manager.
3. Update database credentials in `install.php` (host, username, password).
4. Visit `https://yourdomain.com/install.php` and follow the on-screen setup.
5. **Delete `install.php` immediately after setup.**

---

## 🛣 Roadmap

- [ ] Multi-language support (English, Persian, Arabic)
- [ ] One-click export/import of portfolio data
- [ ] 7+ premium templates

---

## 🤝 Contributing

Contributions, issues, and feature requests are welcome!

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

---

## 📜 License

Distributed under the **MIT License**. See [LICENSE](LICENSE) for more information. You're free to use, modify, and distribute this project for personal or commercial purposes.

---

## 💖 Credits

**Developed with passion by [BSHF-PER](https://github.com/BSHF-PER)**

If this project helped you build your portfolio, please consider:
- ⭐ **Starring** the repository
- 🍴 **Forking** and contributing
- 📢 **Sharing** it with other developers

<p align="center">
  <a href="https://github.com/BSHF-PER">
    <img src="https://img.shields.io/github/followers/BSHF-PER?style=social" alt="Follow">
  </a>
  <a href="https://github.com/BSHF-PER/My-Portfolio.Tool">
    <img src="https://img.shields.io/github/stars/BSHF-PER/My-Portfolio.Tool?style=social" alt="Star">
  </a>
</p>

---

<p align="center">
  <sub>Made with ❤️ for the open-source community</sub>
</p>
