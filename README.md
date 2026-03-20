# QingLi Peptide - Clean Project Structure

## 📁 Project Structure

```
qingli-peptide/
├── 📄 index.html              # Main website homepage
├── 🎨 styles.css              # Main stylesheet  
├── ⚡ script.js               # JavaScript functionality
├── 📄 product.html            # Product detail page
├── 📄 products.html           # Products catalog page
├── 📄 submit_review.php       # Review submission form
├── 📄 thank_you.php           # Thank you page
├── 📄 list_names.php          # Name listing utility
├── 🗄️ admin/                  # Admin dashboard
│   └── 📄 index.php           # Admin panel
├── 🔌 api/                    # Backend API endpoints
│   ├── 📄 auth.php            # Authentication
│   ├── 📄 products.php        # Product management
│   ├── 📄 cart.php            # Shopping cart
│   ├── 📄 reviews.php         # Review system
│   ├── 📄 admin_*.php         # Admin functions
│   ├── 📄 db.php              # Database connection
│   └── 📄 settings.php        # Site settings
├── 📁 COA/                    # Certificate of Analysis files
├── 📁 uploads/                # File upload directory
├── 🗄️ database.sql            # Database schema
└── 📋 PRD.md                  # Product Requirements Doc
```

## 🚀 Local Development

### Requirements
- XAMPP/WAMP/MAMP (Apache + MySQL + PHP)
- PHP 8.0+
- MySQL/MariaDB

### Setup Steps
1. **Start Apache & MySQL** in XAMPP
2. **Import Database**: 
   - Create database `qingli_db`
   - Import `database.sql`
3. **Visit Website**: `http://localhost/Peptide/`
4. **Admin Panel**: `http://localhost/Peptide/admin/`

### Default Admin Login
- **Email**: `admin@qinglipeptide.com`
- **Password**: `admin123`

## 🌐 Deployment

This project is configured for **standard PHP hosting**:

- **Frontend**: Static HTML/CSS/JS files
- **Backend**: PHP API endpoints in `/api/`
- **Database**: MySQL/MariaDB
- **Uploads**: `/uploads/` directory

### Server Requirements
- Apache/Nginx web server
- PHP 8.0+ with PDO MySQL
- MySQL/MariaDB database
- File upload permissions for `/uploads/`

## 🔧 Configuration

### Database Settings
Edit `api/db.php`:
```php
$host = 'localhost';
$dbname = 'qingli_db';
$user = 'root';
$password = '';
```

### File Permissions
Ensure these directories are writable:
- `/uploads/` (777)
- `/COA/` (755)

## 📱 Features

- ✅ **Product Catalog** with COA downloads
- ✅ **Peptide Calculator** for dosing
- ✅ **Customer Reviews** system
- ✅ **Shopping Cart** & quote requests
- ✅ **Admin Dashboard** for management
- ✅ **Authentication** & user accounts
- ✅ **Mobile Responsive** design
- ✅ **Age Verification** system

## 🛡️ Security

- ✅ Session-based authentication
- ✅ SQL injection protection (PDO)
- ✅ CSRF protection
- ✅ File upload validation
- ✅ Admin role verification

---

**🎉 Your QingLi Peptide platform is clean, secure, and ready for deployment!**
