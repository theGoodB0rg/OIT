# OIT — Online Educational Tool

OIT is a PHP-based learning platform designed to help students practice math skills while providing a simple admin interface to manage content. The project bundles several calculators and learning aids inside a Bootstrap-driven UI.

## Features

- **Quadratic Equation Solver** with step-by-step, multi-method explanations.
- **Calculator utilities** including percentage and geometry helpers.
- **Student dashboard** for quick access to available tools.
- **Authentication system** for students and an **admin login** for message management.
- **Responsive frontend** built with Bootstrap 5 and Font Awesome icons.

## Project Structure

```
├── index.php                # Landing page
├── dashboard.php            # Student dashboard
├── tools/                   # Calculator tools (quadratic solver, etc.)
├── login/                   # Student login & registration
├── admin/                   # Admin panel & plugins
├── config/database.php      # Database connection helper
└── css/, js/, images/, fonts/ and assets
```

## Requirements

- PHP 8.x
- MySQL 5.7+ (or compatible MariaDB)
- Composer (optional, not required currently)
- Node.js (optional, for frontend tooling if needed)

## Getting Started

1. **Clone the repository**
   ```bash
   git clone https://github.com/theGoodB0rg/OIT.git
   cd OIT
   ```

2. **Set up the database**
   - Import the schema from `sql/oit.sql` (or `sql/id19550623_oit.sql`).
   - Update credentials in `config/database.php`.

3. **Configure your web server**
   - Point your local Apache/Nginx virtual host to the project root (`OIT/`).
   - Ensure PHP sessions are enabled.

4. **Login**
   - Navigate to `/login/` to create a user account, or use the admin panel via `/admin/login/` with the credentials seeded in your database.

## Development Tips

- The project uses Bootstrap 5 via CDN; adjust styling in `css/style.css`.
- JavaScript utilities live under `js/` and `tools/` pages include inline scripts.
- Keep sensitive configuration (e.g., DB password) out of source control—use environment variables if you extend the app.

## Contributing

1. Fork the repository.
2. Create a feature branch: `git checkout -b feature/my-improvement`.
3. Commit your changes and open a Pull Request against `master`.

## License

This project is currently unlicensed. Contact the repository owner if you plan to reuse or distribute it.

---

Have ideas for new tools or improvements? Open an issue or reach out via the repository discussions!
