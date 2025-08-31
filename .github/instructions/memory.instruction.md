---
applyTo: '**'
---
# Memory Instructions

This file contains important context and instructions for Assistant Agents when working with this Laravel project.

## Project Context

- **Framework**: Laravel 11 application with CRUD functionality
- **Purpose**: Inventory management system with user authentication and role-based permissions
- **Design System**: Uses MYDS (Malaysian Government Design System) tokens and components

## Key Architecture Decisions

- Uses soft deletes for inventory items
- Role-based authorization with Laravel Policies
- Excel import/export functionality via Maatwebsite/Excel
- Background job processing with Laravel Queue
- Audit trail using Laravel Auditing package
- Multi-language support (English/Malay)

## Development Workflow

- Follow PSR-12 coding standards (enforced via `vendor/bin/phpcs`)
- Use Laravel Pint for code formatting (`vendor/bin/pint`)
- Run tests with `composer run test`
- Use `npm run lint:myds` to check MYDS compliance
- Development server: `php artisan serve`
- Asset building: `npm run dev` or `npm run build`

## Important Conventions

- Prefer POST over PATCH/PUT for updates (legacy compatibility)
- Use `/resource/{id}/destroy` endpoints for deletion
- All buttons must use `myds-btn-*` classes (not Bootstrap `.btn`)
- Controllers follow Resource controller pattern
- Policies enforce ownership-based permissions

## Database

- SQLite for testing (in-memory)
- Soft deletes enabled on Inventory model
- Many-to-many relationship between Inventory and Vehicle
- Application model links User to Inventory with metadata

## Testing Strategy

- Feature tests for policies and middleware
- Unit tests for model behavior
- Policy visibility tests ensure proper authorization
- Use factories and seeders for test data

## Files to Remember

- `CLAUDE.md` - Contains detailed development commands and architecture
- `composer.json` - Has custom scripts for common tasks
- Policy files in `app/Policies/` - Authorization logic
- `routes/web.php` - Custom routing patterns
- `resources/views/emails/` - Email templates
- `app/Jobs/` - Background job classes

---

## Assistant Agents

- **GitHub Copilot**: primary in-editor coding assistant. Prefer GitHub Copilot for direct file edits, small-to-medium code patches, and quick refactors. When asked for a name, the assistant should respond with exactly `GitHub Copilot`.

- **Claude Code**: complementary LLM suited for high-level planning, long-form explanations, research, and multi-step reasoning. Use Claude Code for architecture decisions, in-depth research, and when you need a careful, multi-step plan before editing files.

Guidelines for using both together:

- Do not overwrite instructions from the other agent. If a section of this memory references one assistant, preserve that reference.
- Use GitHub Copilot for actionable edits and patches. Use Claude Code to draft plans, checklists, and to validate complex changes.
- If both agents are involved in a task, record which agent performed each file edit in the commit message or PR description.
- Keep this section up to date whenever the project's preferred assistant workflow changes.
