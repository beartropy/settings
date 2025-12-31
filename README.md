# Beartropy Settings

Beartropy Settings is a robust Laravel package designed to manage dynamic application configuration stored in the database. It provides a fluent API for retrieving and storing settings, intelligent caching to minimize database queries, and a ready-to-use Livewire interface built with Tailwind CSS and `beartropy/ui`.

## Features

-   **Dynamic Storage**: Store settings in the database with support for various data types (string, boolean, integer, array, json).
-   **Intelligent Caching**: Automatically caches settings to ensure high performance. Cache is invalidated on updates.
-   **Type Casting**: Automatic type conversion when retrieving settings.
-   **Dot Notation**: access settings using dot notation (e.g., `site.name`) which automatically categorizes them into groups.
-   **Livewire UI**: Includes a drop-in Livewire component to manage settings via a user-friendly interface.
-   **Beartropy UI Integration**: Built using `beartropy/ui` components for a consistent look and feel.

## Installation

1.  **Require via Composer**:

    ```bash
    composer require beartropy/settings
    ```

2.  **Run Migrations**:

    The package includes a migration for the `beartropy_settings` table.

    ```bash
    php artisan migrate
    ```

## Configuration
 
Unlike traditional packages that use a config file, **Beartropy Settings** is fully database-driven. This means both the *values* and the *definitions* (labels, types, options) of your settings are stored in the database.
 
This allows you to add, edit, or remove settings dynamically at runtime without deploying code changes.
 
### Defining Settings
 
You can define settings in two ways:
 
1.  **Via the UI**: Use the provided Livewire component to create new settings visually.
2.  **Via Seeding**: For initial setup, you can seed the `beartropy_settings` table.
 
```php
\Beartropy\Settings\Models\Setting::create([
    'group' => 'general',
    'key' => 'site.name',
    'label' => 'Application Name',
    'value' => 'My App',
    'type' => 'text',
    'is_system' => true, // Prevents deletion from UI
]);
```
 
## Usage
 
### Helper Functions
 
The global `get_setting()` and `set_setting()` helpers are the easiest way to interact with settings.
 
```php
// Get a value (returns default if not set)
$name = get_setting('site.name', 'Default Name');
 
// Set a value
set_setting('site.name', 'New App Name');
```
 
### Facade
 
You can also use the `BeartropySettings` facade.
 
```php
use Beartropy\Settings\Facades\BeartropySettings;
 
// Check if a key exists
if (BeartropySettings::has('site.name')) {
    // ...
}
 
// Get all settings
$all = BeartropySettings::all();
 
// Set a value
BeartropySettings::set('site.name', 'New Name');
```
 
### Caching behavior
 
Settings are cached forever using the `beartropy_settings` cache tag (if your cache driver supports tags, like Redis or Memcached). The cache is automatically cleared whenever a setting is created, updated, or deleted via the Model or Service.
 
## Usage Limitations

### Configuration Files
You **cannot** use the `get_setting()` helper inside files in the `/config` directory (e.g., `config/services.php`). Laravel loads configuration files before the database connection is established. Attempting to use database-driven settings here will result in an error.

**Correct Approach**:
Use `config()` for static values, or set configuration values dynamically in a Service Provider's `boot` method:

```php
// app/Providers/AppServiceProvider.php

public function boot()
{
    // Override config values with database settings safely
    if (Schema::hasTable('beartropy_settings')) {
        config(['app.name' => get_setting('site.name', config('app.name'))]);
    }
}
```

### Service Providers
- **`register()` method**: Avoid using `get_setting()` here, as the database connection might not be fully initialized or other services might not be ready.
- **`boot()` method**: This is the **recommended** place to use `get_setting()` if you need to configure services dynamically based on database settings.

## User Interface
 
To display the settings manager in your application, simply include the Livewire component in any Blade view. Ensure you have Livewire installed and configured.
 
```blade
<livewire:beartropy-settings-manager />
```
 
### Managing Settings
 
The UI provides a full management interface:
-   **Edit Values**: Toggle switches, inputs, and dropdowns update in real-time or via save.
-   **Structure Management**: You can Add new settings, or Edit existing definitions (change label, type, options, description) directly from the dashboard.
 
### Customizing the View
 
If you need to modify the appearance, you can publish the views:
 
```bash
php artisan vendor:publish --tag=beartropy-settings-views
```
 
The file will be located at `resources/views/vendor/beartropy-settings/livewire/settings-manager.blade.php`.

## Requirements

-   PHP ^8.2
-   Laravel 10.x or 11.x or 12.x
-   Livewire 3.x
-   `beartropy/ui` package
