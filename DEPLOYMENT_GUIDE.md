# Qingli Peptide Deployment Guide 🚀

This guide explains how to take your website from your local computer (XAMPP) to a live server so the world can see it.

---

## 1. Get a Domain Name & Hosting
To be online, you need two things:
1.  **Domain Name**: Your website address (e.g., `qinglipeptide.com`).
2.  **Web Hosting**: A computer (server) that stays on 24/7 and stores your files.

**Recommended Providers (for PHP/MySQL sites):**
-   **Namecheap** (Good price, easy to use)
-   **HostGator** or **Bluehost** (Very common for beginners)
-   **SiteGround** (Great support and speed)

*Tip: Choose a "Shared Hosting" or "cPanel Hosting" plan. It's the easiest for PHP websites.*

---

## 2. Export Your Local Database
Since your products, users, and quotes are stored in XAMPP, you need to move them.
1.  Open your browser and go to `http://localhost/phpmyadmin`.
2.  Click on your database: `qingli_db`.
3.  Click the **Export** tab at the top.
4.  Keep the settings as "Quick" and click **Go**. This will download a `.sql` file to your computer.

---

## 3. Upload Your Files
Once you have hosting, you'll receive login details for **FTP** (File Transfer Protocol).
1.  Download a free tool called **FileZilla**.
2.  Connect to your server using the details provided by your host.
3.  Find the `public_html` folder on the server (this is the "live" folder).
4.  Drag and drop ALL files from `c:\xampp\htdocs\Peptide\` into that folder.

---

## 4. Setup the Online Database
1.  Log in to your hosting **cPanel** (usually `yourdomain.com/cpanel`).
2.  Find **MySQL® Databases**.
3.  Create a new database (e.g., `qingli_live`).
4.  Create a database **User** and **Password**.
5.  **Important:** Add the User to the Database with "All Privileges".
6.  Open **phpMyAdmin** from cPanel, click your new database, and use the **Import** tab to upload the `.sql` file you exported in Step 2.

---

## 5. Update Your Configuration
Now you must tell the website how to connect to the *new* database.
1.  Open [api/db.php](file:///c:/xampp/htdocs/Peptide/api/db.php) on your server.
2.  Update these lines with your NEW details:
    ```php
    $host = 'localhost'; // Usually stays localhost
    $dbname = 'your_new_database_name';
    $user = 'your_new_user_name';
    $password = 'your_new_password';
    ```

---

## 6. Secure Your Site (SSL)
Professional sites must have a padlock icon (HTTPS).
1.  Most hosts provide a free **Let's Encrypt SSL**.
2.  In your cPanel, look for "SSL/TLS Status" and click "Run AutoSSL".

---

## Deployment Checklist ✅
- [ ] Purchased Domain Name
- [ ] Purchased Hosting (PHP/MySQL support)
- [ ] Exported `qingli_db.sql` from local XAMPP
- [ ] Uploaded all files via FTP to `public_html`
- [ ] Created live Database, User, and Password in cPanel
- [ ] Imported `.sql` file to live Database
- [ ] Updated [api/db.php](file:///c:/xampp/htdocs/Peptide/api/db.php) with live credentials
- [ ] Verified SSL (Padlock icon is visible)

---

**Need Help?**
If you get stuck on any of these steps, just ask! I can explain specific parts in more detail.
