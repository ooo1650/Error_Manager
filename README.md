# ErrorLog System

A simple web application to log, track, and manage coding errors and their solutions.

## Login Credentials

Since this is a fresh installation, you can create your own account:

1.  Go to `register.php`
2.  Create a username and password
3.  Login!

**Demo Account** (Only available if you haven't registered anyone yet):
-   **Username:** `demo_user`
-   **Password:** `user123`

## Setup Instructions

1.  **Upload Files:**
    -   Upload the entire `PROJECT` folder to your server's `public_html` (or `htdocs`) directory.

2.  **Database Configuration:**
    -   Open `config/db.php`.
    -   The system is already configured to work with the college server credentials.
    -   It will **automatically** create the necessary tables (`users`, `errors`) when you visit the site for the first time.

3.  **That's it!**
    -   Just open `https://student.heraldcollege.edu.np/~YOUR_ID/PROJECT/public/` in your browser.

## Features Implemented

-   **User System:** Secure Registration and Login with Bcrypt password hashing.
-   **CRUD Operations:** Create, Read, Update, and Delete error logs.
-   **Security:**
    -   CSRF Protection on all forms.
    -   Input sanitization to prevent XSS.
    -   Prepared Statements to prevent SQL Injection.
    -   Access Control (Users can only edit/delete their own logs).
-   **Search:** Live search functionality to filter errors by title.
-   **Responsive UI:** Clean, modern interface with a dark/black theme.

## Known Issues

-   None currently. The previous "500 Internal Server Error" during setup has been resolved by making the database connection logic more robust.
