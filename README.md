# CineCove: Movie Review Website

A movie review site built from scratch in PHP and MySQL. Members create an account, add movies with a poster, genre, year, director and description, and manage their entries. A members dashboard lists every user.

## Features

- **Accounts:** registration with a profile picture and age, login and logout, with sessions keeping members signed in
- **Movie reviews:** add, edit and delete movies, including poster image uploads
- **Reviews page:** a "Reel Reviews" feed showing each movie with its details
- **Members dashboard:** a table of all members with profile pictures, age and join date, visible only when logged in
- **Validation:** required fields, username format and email format checks
- **Responsive design:** custom CSS with a branded header and footer

## Built with

- PHP (object-oriented, with `Database`, `User`, `Content` and validation classes)
- MySQL through PDO with prepared statements
- HTML and CSS

## How it handles security

- All database queries use **PDO prepared statements**, which protects against SQL injection.
- Output is escaped with `htmlspecialchars()`, which protects against cross-site scripting (XSS).
- The dashboard and editing pages redirect to login when there is no active session.
- Usernames and emails are unique at the database level.

## Project structure

```text
classes/     Database connection, User, Content and validation classes
inc/         Shared header and footer
sql/         Table definitions (usersDetails, movies)
css/         Styles
images/      Logos
*.php        Pages: home, login, register, dashboard, movie create/edit/delete
```

## Run it locally

1. Create a MySQL database and run `sql/userDetails.sql` to create the tables.
2. Set your own host, database name, username and password in `classes/database.php`.
3. Serve the folder with PHP, for example `php -S localhost:8000`, and open `http://localhost:8000`.

## What I'd improve next

- Switch password hashing to PHP's built-in `password_hash()` and `password_verify()`, which add a salt automatically.
- Move database settings into environment variables instead of code.
- Finish the member edit and delete pages that the dashboard links to.
- Add star ratings and comments so members can review each other's picks.
