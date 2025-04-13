# Morning Briefing

The objective of this application is to gather news articles, filter and group them, and to create morning briefing.

## Project Setup

### Prerequisites

- PHP 8.2 or higher
- Composer
- Git

### Installation

1. Clone the repository:
   ```bash
   git clone https://github.com:CiderAndWhisky/morning-briefing.git
   cd morning-briefing
   ```

2. Install dependencies:
   ```bash
   composer install
   ```

3. Copy the environment file:
   ```bash
   cp .env.example .env
   ```

4. Configure your environment variables in the `.env` file.

5. Run the setup command:
   ```bash
   php bin/console app:setup
   ```

## Cursor Configuration

This project includes Cursor configuration files to help you work more efficiently:

- `.cursor/settings.json`: Cursor settings for PHP development
- `.cursorignore`: Files and directories to ignore by Cursor
- `.cursorrules`: Project rules for code style, architecture, testing, documentation, and Git
- `.cursorlint`: Linting rules for PHP code
- `.cursorformat`: Formatting rules for PHP code
- `.cursorcommands`: Custom commands for development, application, database, and Git
- `.cursorsnippets`: Custom code snippets for PHP classes, interfaces, traits, use cases, repositories, services, and commands

## Project Structure

The project follows Onion Architecture principles:

- `src/API`: Application Programming Interface layer (controllers, commands)
- `src/Business`: Business logic layer (use cases, services)
- `src/Infrastructure`: Infrastructure layer (repositories, entities, clients)

## Development

### Running Tests

```bash
php bin/phpunit
```

### Code Style

This project follows PSR-12 coding standards. You can check and fix code style issues with:

```bash
# Check code style
php bin/phpcs src tests

# Fix code style issues
php bin/phpcbf src tests
```

### Git Workflow

- Use feature branches for new features: `git checkout -b feature/your-feature`
- Use bugfix branches for bug fixes: `git checkout -b bugfix/your-bugfix`
- Use hotfix branches for urgent fixes: `git checkout -b hotfix/your-hotfix`
- Write descriptive commit messages
- Squash commits before merging to main branch

## License

This project is proprietary

# Architecture Rules
- Follow Onion Architecture principles
- Keep controllers thin, move business logic to use cases
- Use dependency injection for all dependencies
- Use interfaces for all external services
- Use value objects for domain concepts
- Use repositories for data access
- Use DTOs for data transfer between layers
- Separate domain logic from infrastructure concerns
- Keep domain models pure and free of infrastructure dependencies
- Use command/query separation where appropriate
- Keep Business layer free from Infrastructure dependencies
  - Entities belong in Infrastructure/Persistence
  - Business layer should use its own domain models
  - Domain models should be built from entities but structured for business needs
  - Use repositories to transform between entities and domain models
  - No direct use of ORM annotations in Business layer
