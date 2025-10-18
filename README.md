# Human Resource Employee Information System (HREIS)

The **Human Resource Employee Information System (HREIS)** is a web-based application designed to help HR departments efficiently manage employee records, personal data sheets, leave applications, and other HR-related processes.

It provides role-based access for **Admins** and **Employees**, ensuring secure handling of sensitive HR data.

---

## 🚀 Features

- Secure login/logout with session management
- Admin & Employee dashboards
- Employee information management
- Personal Data Sheet (PDS) preview & download
- Leave application & history tracking
- Department management
- Responsive design (desktop & mobile friendly)

---

## 📂 Project Structure

```
/hreis
 ├── admin/           # Admin-side pages (Dashboard, Employee, Department, Leave Reports, etc.)
 ├── employee/        # Employee-side pages (Dashboard, PDS, Leave history, etc.)
 ├── assets/          # Static files (CSS, JS, Images)
 ├── database/        # database folder (hreis.sql)
 ├── db.php           # Database connection
 ├── index.php        # Landing page route to admin and employee login
 └── README.md        # Project documentation
```

---

## 🔑 Default Credentials

### **Admin Account**

- **Email:** `admin@hreis.com`
- **Password:** `admin123`

### **Employee Account**

- **Email:** `johndoe@example.com`
- **Password:** `password123`

---

## ⚙️ Installation Guide

1. **Clone or copy** this project into your server root directory (e.g., `htdocs` for XAMPP).

   ```bash
   git clone https://github.com/Human-Resource-Employee-Information-System/hreis.git
   ```

2. **Database Setup**

   - Import the provided SQL file (`hreis.sql`) into your MySQL database.
   - Update `db.php` with your database credentials.

3. **Run the Application**
   - Start Apache & MySQL using XAMPP/WAMP.
   - Open in browser:
     ```
     http://localhost/hreis
     ```

---

## 🔒 Security Notes

- Change default passwords immediately after first login.
- Store database credentials securely (consider using `.env` files).
- Always validate and sanitize user input to prevent SQL injection and XSS.

---

## 👨‍💻 Author

Developed for **Human Resource Management** to streamline employee data tracking, leave management, and HR operations.
