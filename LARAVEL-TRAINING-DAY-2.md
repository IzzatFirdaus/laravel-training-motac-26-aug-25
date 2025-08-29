# Laravel Training — Day 2

Prepared by TARSOFT SDN BHD

## Table of contents

- Introduction
- Installation
- Application startup
- Application development
- Laravel models
- Data handling

## Introduction

### What is Laravel?

Laravel is a PHP framework for web development. It follows the MVC (Model-View-Controller) architectural pattern and simplifies common tasks like routing, authentication, database management, and templating.

### Benefits of using Laravel

- Rapid development: Built-in tools for routing, ORM, and templating.
- Security: Helps prevent common web vulnerabilities (SQL injection, CSRF).
- Scalable & maintainable: The MVC structure separates concerns.
- Community & resources: Large community and many packages.
- Modern features: Artisan CLI, Eloquent ORM, API support, and testing tools.

## Laravel architecture (MVC)

- Actor (User): Interacts with the app via a web browser.
- Route: Directs traffic to the appropriate controller based on the URL.
- Controller: Processes the request, interacting with the model and view.
- Model: Handles data and business logic, communicating with the database.
- Database: Stores application data, accessed via the Eloquent ORM.
- View: Displays data to the user in a structured interface.

## Installation & application startup

### System requirements

- PHP >= 8.2
- Composer (PHP package manager)
- MySQL / MariaDB / SQLite
- Node.js & NPM
- An IDE (VS Code, PhpStorm)

### Initial setup

1. Start local server: open Laragon and click "Start All".
1. Create project: use the "Quick app" feature to create a new Laravel project.
1. Configure environment (.env):

   - Open the `.env` file in your project root.
   - Uncomment the `DB_*` variables.
   - Set `DB_CONNECTION` to `mysql`.
   - Set `DB_DATABASE` to your project's database name.
   - Ensure `DB_USERNAME` and `DB_PASSWORD` match your local database credentials.

1. Install dependencies:

```bash
npm install
```

1. Run migrations (creates initial database tables):

```bash
php artisan migrate
```

### Set up authentication

1. Install UI package:

```bash
composer require laravel/ui
```

1. Generate auth scaffolding:

```bash
php artisan ui bootstrap --auth
```

1. Install NPM dependencies and compile assets:

```bash
npm install
npm run dev
```

1. Run the application:

```bash
php artisan serve
```

You should now see "Login" and "Register" links on your application's homepage.

## Application development (CRUD example)

### 1) Create migration and model

Create migration (defines schema for `inventories` table):

```bash
php artisan make:migration create_inventories_table
```

Inside the generated migration file, define the table columns:

```php
Schema::create('inventories', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained('users');
    $table->string('name');
    $table->integer('qty');
    $table->decimal('price');
    $table->text('description');
    $table->timestamps();
});
```

Run migration:

```bash
php artisan migrate
```

Create model:

```bash
php artisan make:model Inventory
```

Inside `app/Models/Inventory.php`, define the fillable attributes:

```php
protected $fillable = [
    'user_id',
    'name',
    'qty',
    'price',
    'description',
];
```

Define model relationships:

In `app/Models/Inventory.php`:

```php
// An inventory belongs to a user.
public function user()
{
    return $this->belongsTo(User::class);
}
```

In `app/Models/User.php`:

```php
// A user has many inventories.
public function inventories()
{
    return $this->hasMany(Inventory::class);
}
```

### 2) Create controller

Generate the controller to handle inventory logic:

```bash
php artisan make:controller InventoryController
```

### 3) Define routes (`routes/web.php`)

Add routes for CRUD operations:

```php
use App\\Http\\Controllers\\InventoryController;

// List all inventories
Route::get('/inventories', [InventoryController::class, 'index'])->name('inventory.index');
// Show create form
Route::get('/inventories/create', [InventoryController::class, 'create'])->name('inventory.create');
// Store new inventory
Route::post('/inventories/create', [InventoryController::class, 'store'])->name('inventory.store');
// Show a single inventory
Route::get('/inventories/{inventory}', [InventoryController::class, 'show'])->name('inventory.show');
// Show edit form
Route::get('/inventories/{inventory}/edit', [InventoryController::class, 'edit'])->name('inventory.edit');
// Update an inventory
Route::post('/inventories/{inventory}/edit', [InventoryController::class, 'update'])->name('inventory.update');
// Delete an inventory
Route::get('/inventories/{inventory}/delete', [InventoryController::class, 'destroy'])->name('inventories.destroy');
```

### 4) Controller methods (overview)

- `index()`: Fetch and display all inventories.
- `create()`: Show the form for creating a new inventory.
- `store()`: Validate and save the new inventory to the database.
- `show()`: Display the details of a single inventory.
- `edit()`: Show the form for editing an existing inventory.
- `update()`: Validate and update the inventory in the database.
- `destroy()`: Delete the inventory from the database.

### 5) Create views (`resources/views/inventory/`)

- `index.blade.php`: A table to list all inventories with links to show, edit, and delete.
- `create.blade.php`: A form to create a new inventory.
- `show.blade.php`: A read-only view of an inventory's details.
- `edit.blade.php`: A form, pre-filled with data, to update an inventory.

## Data handling

### Migrations, seeders, and factories

- **Migrations**: Act as version control for your database schema.
- **Factories**: Generate fake but realistic data for testing and development.
- **Seeders**: Populate database tables with initial or test data, often using factories.

Create a factory:

```bash
php artisan make:factory InventoryFactory
```

Define the model's default state in `database/factories/InventoryFactory.php`:

```php
use App\\Models\\User;

public function definition(): array
{
    return [
        'user_id' => User::factory(),
        'name' => $this->faker->word(),
        'qty' => $this->faker->numberBetween(1, 100),
        'price' => $this->faker->randomFloat(2, 1, 500),
        'description' => $this->faker->sentence(),
    ];
}
```

Using seeders

Call the factory in `database/seeders/DatabaseSeeder.php`:

```php
use App\\Models\\Inventory;

public function run(): void
{
    Inventory::factory()->count(50)->create();
}
```

Run the seeder to populate the database:

```bash
php artisan db:seed
```

To refresh the database and then seed:

```bash
php artisan migrate:fresh --seed
```

### Pagination

Controller: use the `paginate()` method instead of `all()` or `get()`:

```php
$inventories = Inventory::paginate(5); // Show 5 items per page
```

View: render the pagination links:

```blade
<div class="mt-6">
    {{ $inventories->links() }}
</div>
```

AppServiceProvider: configure pagination to use Bootstrap styling:

```php
use Illuminate\\Pagination\\Paginator;

public function boot(): void
{
    Paginator::useBootstrap();
}
```

### Validation

In your `store` and `update` methods, use the `validate` method to enforce rules:

```php
public function store(Request $request)
{
    $validated = $request->validate([
        'name' => 'required|string|min:5|max:255',
        'qty' => 'required|integer|min:1',
        'price' => 'required|numeric|min:0',
        'description' => 'required|string|min:5',
    ]);

    // Add user_id and create the inventory
    $validated['user_id'] = Auth::id();
    Inventory::create($validated);

    return redirect()->route('inventory.index');
}
```

Displaying errors in views: use the `@error` directive in Blade files:

```blade
<input type="text" name="name">
@error('name')
    <span class="text-danger">{{ $message }}</span>
@enderror
```
