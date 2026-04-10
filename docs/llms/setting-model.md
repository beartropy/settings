# Setting Model — AI Reference

## Model
```php
Beartropy\Settings\Models\Setting
```

## Table
`beartropy_settings`

## Fillable
`key`, `value`, `group`, `type`, `label`, `description`, `options`, `is_system`

## Schema

| Column | Type | Default | Index |
|--------|------|---------|-------|
| `id` | bigint | PK | — |
| `group` | string | `'general'` | indexed |
| `key` | string | — | indexed |
| `value` | text | nullable | — |
| `label` | string | nullable | — |
| `description` | text | nullable | — |
| `type` | string | `'string'` | — |
| `options` | JSON | nullable | — |
| `is_system` | bool | `false` | — |
| `created_at` | timestamp | — | — |
| `updated_at` | timestamp | — | — |

**Unique Index**: `(group, key)`

## Value Accessors/Mutators
- Boolean type: cast to `true`/`false` PHP booleans
- Integer type: cast to `int`
- Array/JSON type: auto `json_decode`/`json_encode`
- String type: stored as-is

## Cache Invalidation (on save/delete)
Clears three cache keys:
1. `beartropy_settings.setting.{key}`
2. `beartropy_settings.settings.all`
3. Full tag flush: `beartropy_settings`

## Common Pitfalls
- The composite unique index on `(group, key)` means the same key can exist in different groups
- `is_system` is a UI-only protection — it does not prevent programmatic deletion
- `options` column is JSON — used only for `select` type fields
