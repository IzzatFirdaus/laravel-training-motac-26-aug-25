# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Development Commands

### Laravel Commands
- `php artisan serve` - Start development server
- `php artisan migrate` - Run database migrations
- `php artisan migrate:fresh --seed` - Reset database with seeders
- `php artisan queue:work` - Process background jobs
- `php artisan tinker` - Interactive PHP shell

### Testing
- `composer run test` - Run PHPUnit tests (shortcut for `php artisan test`)
- `vendor/bin/phpunit` - Run PHPUnit directly
- `vendor/bin/phpunit tests/Feature/PolicyVisibilityTest.php` - Run specific test file

### Code Quality
- `vendor/bin/phpcs` - Check PHP code style (PSR-12)
- `vendor/bin/pint` - Fix PHP code style using Laravel Pint
- `npm run lint:myds` - Check for legacy Bootstrap button classes

### Build and Asset Management
- `npm run build` - Build production assets
- `npm run dev` - Start Vite development server
- `composer run dev` - Start all services concurrently (server, queue, logs, vite)

### Excel Operations
- Excel import/export functionality available at `/excel/inventories/export` and `/excel/inventories/import`
- Template file: `inventories-template.xlsx`

## Architecture Overview

### Core Models and Relationships
- **User**: Central entity with role-based permissions (`hasRole('admin')`)
- **Inventory**: Belongs to User, supports soft deletes, has many-to-many with Vehicle
- **Vehicle**: Belongs to User, many-to-many with Inventory
- **Application**: Links User to Inventory with additional metadata

### Authorization System
- Uses Laravel Policies for authorization (`InventoryPolicy`, `UserPolicy`, etc.)
- Role-based access: admins have broader permissions than regular users
- Ownership-based permissions: users can only modify their own resources
- Policy structure: `viewAny`, `view`, `create`, `update`, `delete`, `restore`, `forceDelete`

### Key Features
- **Soft Deletes**: Inventory items are soft-deleted and can be restored
- **Excel Integration**: Import/export via Maatwebsite/Excel package
- **Job Queue**: Background job processing for inventory creation emails
- **Auditing**: Laravel Auditing package tracks model changes
- **MYDS Design System**: Custom button styling (`myds-btn-*` classes)

### Database Structure
- Uses conventional Laravel migrations in `database/migrations/`
- Factories and seeders available for all main models
- Pivot table `inventory_vehicle` for many-to-many relationships
- SQLite in-memory database for testing

### Routing Conventions
- Uses POST for updates instead of PATCH/PUT
- Destroy actions use POST to `/resource/{id}/destroy` endpoints
- Backwards compatibility routes for legacy URLs
- Multi-language support with locale switcher (`/locale/{locale}`)

### Frontend Stack
- Laravel Blade templates with Bootstrap 5 and Tailwind CSS
- Vite for asset bundling
- SweetAlert2 for notifications
- Custom MYDS design system with strict linting

### Email and Jobs
- Mail templates in `resources/views/emails/`
- Job classes in `app/Jobs/` for background processing
- Queue configuration supports multiple drivers

### Testing Strategy
- Feature tests for policy enforcement and middleware
- Unit tests for model behavior
- SQLite in-memory database for fast test execution
- Specific test for role middleware and policy visibility

## Project-Specific Notes

### MYDS Design System
- Custom design system with strict enforcement via `npm run lint:myds`
- Use `myds-btn-*` classes instead of Bootstrap `.btn` classes
- Color reference and design guidelines in `MYDS-*-Overview.md` files

### Code Style Enforcement
- PSR-12 standard via PHP_CodeSniffer
- Blade templates excluded from PHPCS checks
- Laravel Pint for automatic code formatting

### User Search API
- `/users/search` endpoint for autocomplete functionality
- Used in application forms for user selection

### Excel Import/Export
- Template-based Excel import with preview functionality
- Export functionality for inventory data
- Import validation and error handling