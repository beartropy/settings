# Helper Functions

Global helper functions for quick access to settings.

## Usage

```php
// Get a setting
$value = get_setting('app.name', 'Default');

// Set a setting
set_setting('app.name', 'My App');
```

## Functions

| Function | Return | Description |
|----------|--------|-------------|
| `get_setting(string $key, $default = null)` | `mixed` | Shortcut for `BeartropySettings::get()` |
| `set_setting(string $key, $value = null)` | `void` | Shortcut for `BeartropySettings::set()` |

## Blade Usage

```blade
<h1>{{ get_setting('app.name', config('app.name')) }}</h1>
```
