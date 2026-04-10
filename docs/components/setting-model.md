# Setting Model

The Eloquent model for persisted settings.

## Table

`beartropy_settings` with unique composite index on `(group, key)`.

## Properties

| Column | Type | Default | Description |
|--------|------|---------|-------------|
| `group` | `string` | `general` | Setting group |
| `key` | `string` | — | Setting key |
| `value` | `text` | `null` | Setting value |
| `type` | `string` | `string` | Data type: `text`, `boolean`, `number`, `textarea`, `select`, `json`, `toggle` |
| `label` | `string` | `null` | Human-readable label |
| `description` | `text` | `null` | Description |
| `options` | `JSON` | `null` | Options for select type |
| `is_system` | `bool` | `false` | Prevents deletion via UI |

## Type Casting

The model automatically casts values based on their `type`:
- **boolean**: Cast to `true`/`false`
- **integer/number**: Cast to `int`
- **array/json**: Automatically decoded/encoded
- **string/text**: Stored as-is

## Cache Invalidation

Cache is automatically cleared when a setting is saved or deleted. Three cache keys are invalidated:
- `beartropy_settings.setting.{key}` — individual setting
- `beartropy_settings.settings.all` — all settings collection
