# Trongate v2 Tasks Module

A complete **tasks** module for **Trongate v2** that demonstrates a full-featured **CRUD** (Create, Read, Update, Delete) application with best practices.

This repository provides a ready-to-use example of building a task management system using the Trongate PHP framework (version 2). It includes pagination, form validation, secure admin access, checkbox handling, confirmation dialogs, and clean separation of concerns.

## Features

- Paginated task listing with selectable records per page (10, 20, 50, 100)
- Create new tasks
- View detailed task records
- Update existing tasks (with form repopulation on validation errors)
- Safe delete with confirmation page
- Form validation and CSRF protection
- Checkbox handling for task completion status
- Admin security checks on all actions
- Responsive back navigation and flash messages
- Clean, well-commented code following Trongate conventions

## Database Table

The `tasks.sql` file creates a `tasks` table with the following columns:
- `id` (auto-increment primary key)
- `task_title` (varchar 255)
- `task_description` (text)
- `complete` (tinyint 1 - for completion status checkbox)

## Prerequisites

- Trongate v2 framework (latest version recommended)
- PHP 8.0+
- MySQL/MariaDB database
- Web server with URL rewriting enabled

Visit the official site: [trongate.io](https://trongate.io)

## Installation

1. **Install Trongate v2** (if not already done):
   - Use the free Trongate Desktop App: [trongate.io/download](https://trongate.io/download)
   - Or follow the docs: [trongate.io/docs](https://trongate.io/docs)

2. **Add the module**:
   - Clone or download this repo into your project's `modules` directory:
     ```bash
     git clone https://github.com/trongate/Trongate-v2-Tasks-Module.git modules/tasks
     ```
   - Or copy the `tasks` folder directly into `modules/tasks`.

3. **Create the database table**:
   - Import `tasks.sql` into your database (e.g., via phpMyAdmin or command line).

4. **Access the module**:
   - Log in to your Trongate admin panel.
   - Visit: `https://your-domain.com/tasks` or `https://your-domain.com/tasks/manage`

## URL Routes

- List tasks: `/tasks` or `/tasks/manage` (with pagination: `/tasks/manage/{page}`)
- Create task: `/tasks/create`
- View task: `/tasks/show/{id}`
- Edit task: `/tasks/create/{id}`
- Delete confirmation: `/tasks/delete_conf/{id}`
- Set records per page: `/tasks/set_per_page/{option_index}`

## Contributing

Issues, suggestions, and pull requests are welcome! Feel free to fork and improve this example module.

## License

Released under the same open-source license as the Trongate framework (MIT-style - permissive and free to use).

Happy coding with Trongate! 🚀
