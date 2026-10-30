# TMT Parcel Service Website

Starter full-stack IWP project using HTML, CSS, JavaScript, PHP and MySQL.

## Requirements
- PHP 8+
- MySQL / MariaDB
- XAMPP (recommended for Windows)

## Run locally
1. Copy the `tmt_parcel_service` folder into `C:\xampp\htdocs\`.
2. Start Apache and MySQL in XAMPP.
3. Open phpMyAdmin at `http://localhost/phpmyadmin`.
4. Import `database.sql`.
5. Open `http://localhost/tmt_parcel_service/`.

## Admin setup
For a classroom prototype, use the setup page once to create the first admin:
`http://localhost/tmt_parcel_service/setup_admin.php`
Delete `setup_admin.php` after creating the admin account.

## Important
- Replace the sample business details only after the client approves them.
- The address and phone displayed are the details supplied by the client; verify the PIN code/address before publishing.
- This starter is for development/demo use. Before real public deployment, enable HTTPS, use a strong admin password, configure backups, add rate limiting/CSRF protection, and review privacy/security.
- Booking submissions are requests, not confirmed pickups. Admins must confirm and update real shipment statuses.
- The site does not calculate prices or guarantee delivery dates.


## My Bookings feature
New booking requests require the customer to choose a 6-digit My Bookings PIN. Customers can visit `my-bookings.php` and retrieve their own booking list using the sender phone number and PIN. The PIN is stored as a password hash, not plain text. This is a PIN-based retrieval method, not SMS OTP verification.

### Upgrade an existing database
If you already imported the original `database.sql`, open phpMyAdmin, click the `tmt_parcel_db` database, choose Import, and import `migration_my_bookings.sql` once. This preserves existing booking records. Existing bookings have no PIN; an administrator must retrieve their reference from the dashboard/database. New bookings can use My Bookings.
