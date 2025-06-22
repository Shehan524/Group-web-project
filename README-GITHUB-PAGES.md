# TaskBuddy - GitHub Pages Deployment

This repository contains a static version of TaskBuddy deployed to GitHub Pages.

## Important Note ⚠️

TaskBuddy is a PHP/MySQL application that cannot run with full functionality on GitHub Pages because:

1. GitHub Pages only supports static content (HTML, CSS, JavaScript)
2. GitHub Pages does not support server-side languages like PHP
3. GitHub Pages cannot connect to databases (MySQL)

## What This Deployment Contains

This GitHub Pages deployment provides a **static demonstration** of TaskBuddy with:

- Static HTML pages showcasing the UI design
- Visual representation of the dashboard and task cards
- Non-functional forms and buttons
- Informational alerts explaining the limitations

## How to Use the Full Application

To use TaskBuddy with all its features:

1. Clone the repository from GitHub
2. Set up a web server with PHP support (e.g., XAMPP, WAMP)
3. Import the database schema
4. Configure the database connection in `db/config.php`
5. Access the application through your local web server

## GitHub Actions CI/CD

This repository uses GitHub Actions for:

1. Automatically building the static site for GitHub Pages
2. Converting PHP files to static HTML for demonstration purposes
3. Deploying the static content to GitHub Pages

You can view the workflow configuration in `.github/workflows/github-pages.yml`

## Links

- [GitHub Pages Demo](https://yourusername.github.io/taskbuddy/)
- [Source Code Repository](https://github.com/yourusername/taskbuddy)

## Project Overview

TaskBuddy is a collaborative task management web application built with:

- **Frontend**: HTML, CSS, Bootstrap 5
- **Backend**: PHP 8.x (not available in this GitHub Pages demo)
- **Database**: MySQL (not available in this GitHub Pages demo) 