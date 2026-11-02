# Opportunitedimigration — local PHP/MySQL starter

## Included
- `index.php`: bilingual (French/English) interface. The main page displays the welcome screen only; the ☰ menu reveals the options. Clicking an option displays only that section.
- `config.php`: PDO connection to MySQL/MariaDB.
- `database.sql`: database and enquiry table.
- `SRS_Opportunitedimigration.docx`: handout-ready Software Requirements Specification and setup guide.

## Run with XAMPP (Windows)
1. Install/start XAMPP. Open XAMPP Control Panel and start **Apache** and **MySQL**.
2. Open `C:\xampp\htdocs\` and create folder `opportunitedimigration`.
3. Copy `index.php`, `config.php`, and `database.sql` into that folder.
4. Open `http://localhost/phpmyadmin/`.
5. Select **Import**, choose `database.sql`, then click **Import/Go**. Or open the SQL tab and run the contents of the file.
6. Check `config.php`: the example uses host `127.0.0.1`, database `opportunitedimigration`, username `root`, empty password. If your local MySQL account uses a password, update `$dbPass`.
7. Visit `http://localhost/opportunitedimigration/`.
8. Click ☰ → Make an enquiry, submit a test enquiry, and confirm the success message.
9. In phpMyAdmin, open `opportunitedimigration` → `enquiries` → **Browse**. Confirm the test row exists.

## Important
- Do not open `index.php` by double-clicking it; PHP needs Apache. Use `http://localhost/...`.
- Do not use this empty-password root configuration on a public server.
- This local starter stores enquiries in MySQL. Email notification is not configured because production email needs SMTP credentials/provider setup. Never place email passwords or API keys in public code.
- The page avoids collecting passport scans or other sensitive documents. Before publishing, provide a clear privacy notice, define retention/deletion rules, enable HTTPS, and restrict database access.
- The included menu behaviour is intentionally simple: click the menu button to reveal options; choose an option to display that content area; choose the menu again to switch sections.
