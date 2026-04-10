# Settings Manager

A full CRUD Livewire component for managing application settings, built on Beartropy Tables.

## Basic Usage

```blade
@livewire('beartropy-settings-manager')
```

## Features

- Searchable and sortable settings table
- Modal form for creating/editing settings
- 7 field types: `text`, `boolean`, `number`, `textarea`, `select`, `json`, `toggle` (deprecated)
- Group-based organization with dot notation keys
- System settings protection (cannot be deleted via UI)

## Setting Fields

| Field | Type | Description |
|-------|------|-------------|
| `group` | `string` | Setting group (default: `general`) |
| `key` | `string` | Setting key — lowercase alphanumeric, dots, underscores only |
| `label` | `string` | Human-readable label |
| `value` | `mixed` | Setting value — rendered based on type |
| `type` | `string` | Field type: `text`, `boolean`, `number`, `textarea`, `select`, `json` |
| `description` | `string` | Description shown in the UI |
| `options` | `JSON` | Options for `select` type fields |
| `is_system` | `bool` | System settings cannot be deleted |

## Validation

- Key format: `/^[a-z0-9_.]+$/`
- Key must be unique within its group
- Group and key are required

## Dynamic Form Rendering

The modal form adapts based on the selected type:
- **text/number**: Standard input field
- **boolean/toggle**: Toggle switch
- **textarea/json**: Textarea
- **select**: Dropdown with options from JSON field
