
# 📝 TaskBuddy – A To-Do List Web App

**TaskBuddy** is a collaborative open-source task management web application built with PHP, MySQL, and Bootstrap. Designed to help users manage their daily tasks with ease, it supports features like registration, login, task creation, editing, search/filter, and completion tracking.

---

## 📌 Project Overview

- 👥 Developed collaboratively using **GitHub SCM best practices**
- ✅ User-based task dashboard with completion tracking
- 🔍 Smart filters by keyword, category, and status
- 🌐 Responsive and mobile-friendly design
- ⚙️ Includes GitHub Actions, project management, and CI setup

---

## 👨‍💻 Team Members

| Role                  | Name (Example)        | GitHub Handle    |
|-----------------------|-----------------------|------------------|
| Project Manager       | W.S.M.Fernando        | @Shehan524       |
| Developer             | A.A.G.M.Monarawila    | @Gothami98       |
| Reviewer              | A.M.N.N.Bandara       | @Nethmina01      |
| DevOps/CI Engineer    | D.H.G.I.S.Jayathissa  | @Isurika99       |

---

## 🛠️ Tech Stack

- **Frontend**: HTML, CSS, Bootstrap 5
- **Backend**: PHP 8.x
- **Database**: MySQL
- **Tools**: GitHub, GitHub Actions, VS Code

---

## 🚀 Setup Instructions

### 1. Clone the Repository
```bash
git clone https://github.com/yourusername/taskbuddy.git
cd taskbuddy
```

### 2. Set Up the Database
- Import the SQL file into phpMyAdmin:
  - File: `taskbuddy_db.sql`
- Database Name: `taskbuddy_db`

### 3. Configure `db/config.php`
```php
$host = 'localhost';
$user = 'root';
$password = '';
$dbname = 'taskbuddy_db';
```

### 4. Run Locally (with XAMPP/WAMP)
- Place the project folder inside `htdocs/` (XAMPP) or `www/` (WAMP)
- Start Apache & MySQL
- Visit: `http://localhost/taskbuddy/index.html`

---

## 📷 Screenshots

| Login Page | Dashboard | Task Form |
|------------|-----------|-----------|
| ![Login](![image](https://github.com/user-attachments/assets/4bfb1901-4c19-44ee-87ff-52435643a8f4)
) | ![Dashboard](![image](https://github.com/user-attachments/assets/2f037d4e-71a6-4916-85f3-bacf63a89aba)
) | ![Form](![image](https://github.com/user-attachments/assets/0cef7b70-0b39-4f11-a58e-294aad76d5d6)
) |

---

## 🧪 Features

- [x] User Registration & Login (secure hashing)
- [x] Add/Edit/Delete Tasks
- [x] Toggle task completion
- [x] Filter by keyword, category, and status
- [x] GitHub Actions for HTML/PHP validation
- [x] GitHub Projects & Issues
- [x] Changelog and release tagging

---

## 📂 Project Structure

```
TaskBuddy/
├── index.html
├── login.php
├── register.php
├── dashboard.php
├── add_task.php
├── edit_task.php
├── logout.php
├── css/style.css
├── js/script.js
├── db/config.php
├── docs/UserGuide.md
├── docs/DevGuide.md
├── .github/workflows/ci.yml
├── .gitignore
├── LICENSE
├── README.md
└── CHANGELOG.md
```

---

## 📄 License

This project is licensed under the **MIT License** – see [LICENSE](LICENSE) file for details.

---

## 🤝 Contributing

We welcome pull requests and issues! Please fork the repository and follow GitHub's best practices for collaboration.

---

## ⭐ Star Us!

If you like this project, give it a ⭐ on GitHub!
