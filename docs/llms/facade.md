# BeartropySettings Facade — AI Reference

## Facade Access
```php
use Beartropy\Settings\Facades\BeartropySettings;
BeartropySettings::get('key', 'default');
```

## Architecture
- Facade accessor: `'beartropy-settings'`
- Resolves to: `SettingsManager` service (singleton)
- Implements: `SettingsStorage` contract
- Cache: Laravel Cache with tag `beartropy_settings`, forever duration

## Service: SettingsManager

| Method | Signature | Description |
|--------|-----------|-------------|
| `get` | `(string $key, $default = null): mixed` | Retrieves cached setting, falls back to DB |
| `set` | `(string $key, $value = null): void` | Sets value with auto type inference |
| `has` | `(string $key): bool` | Checks existence in DB |
| `all` | `(): array` | Returns all settings as dot-notation key-value pairs |

## Dot Notation Resolution
- `'app.name'` → group: `'app'`, key: `'name'`
- `'general.theme'` → group: `'general'`, key: `'theme'`
- Keys without dots → group: `'general'`

## Type Inference (`infer_type`)
- `true`/`false` → `'boolean'`
- `is_numeric()` → `'integer'`
- `is_array()` → `'array'`
- Default → `'string'`

## Cache Keys
- Individual: `beartropy_settings.setting.{group}.{key}`
- All: `beartropy_settings.settings.all`
- Tag: `beartropy_settings`

## Common Pitfalls
- Cache uses `rememberForever` — invalidated only on model save/delete
- `databaseHasTable()` check prevents errors during migrations
- Service is registered as singleton — same instance across the request
