# BeartropySettings Facade

A simple facade for reading and writing application settings with automatic caching.

## Basic Usage

```php
use Beartropy\Settings\Facades\BeartropySettings;

// Get a setting
$appName = BeartropySettings::get('app.name', 'Default');

// Set a setting
BeartropySettings::set('app.name', 'My App');

// Check if a setting exists
if (BeartropySettings::has('app.name')) {
    // ...
}

// Get all settings
$all = BeartropySettings::all(); // ['app.name' => 'My App', ...]
```

## Methods

| Method | Return | Description |
|--------|--------|-------------|
| `get(string $key, $default = null)` | `mixed` | Get setting value with optional default |
| `set(string $key, $value = null)` | `void` | Set a setting value |
| `has(string $key)` | `bool` | Check if a setting exists |
| `all()` | `array` | Get all settings as key-value pairs |

## Dot Notation

Settings support dot notation for grouping:

```php
// Stored as group: "mail", key: "from_address"
BeartropySettings::set('mail.from_address', 'hello@example.com');
BeartropySettings::get('mail.from_address');
```

## Type Inference

Values are automatically type-inferred when stored:
- `true`/`false` → boolean
- Numeric strings → integer
- Arrays → JSON-encoded string
- Everything else → string
