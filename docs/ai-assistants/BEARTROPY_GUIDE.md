# Beartropy Settings - Universal AI Assistant Guide

> This guide helps AI assistants generate correct code using Beartropy Settings for Laravel applications.

## Overview

**Beartropy Settings** is a database-backed settings management package with caching, dot-notation access, and a Livewire CRUD UI.

## API

### Facade

```php
use Beartropy\Settings\Facades\BeartropySettings;

BeartropySettings::get('app.name', 'Default');
BeartropySettings::set('app.name', 'My App');
BeartropySettings::has('app.name');
BeartropySettings::all(); // ['app.name' => 'My App', ...]
```

### Helper Functions

```php
get_setting('app.name', 'Default');
set_setting('app.name', 'My App');
```

### In Blade

```blade
<h1>{{ get_setting('app.name', config('app.name')) }}</h1>
```

## Livewire Management UI

```blade
@livewire('beartropy-settings-manager')
```

Provides a full CRUD interface with:
- Searchable/sortable settings table
- Modal form for creating/editing
- 7 field types: text, boolean, number, textarea, select, json, toggle
- Group-based organization
- System settings protection

## Setting Model

**Table**: `beartropy_settings`

| Field | Type | Description |
|-------|------|-------------|
| `group` | string | Setting group (default: 'general') |
| `key` | string | Setting key (unique within group) |
| `value` | text | Setting value |
| `type` | string | Field type for UI |
| `label` | string | Human-readable label |
| `description` | text | Description |
| `options` | JSON | Options for select type |
| `is_system` | bool | Prevents deletion via UI |

## Dot Notation

```php
// 'mail.from' → group: 'mail', key: 'from'
BeartropySettings::set('mail.from', 'hello@example.com');

// 'theme' → group: 'general', key: 'theme'
BeartropySettings::set('theme', 'dark');
```

## Caching

- Forever cache with automatic invalidation on save/delete
- Cache tag: `beartropy_settings`
- Requires tag-supporting cache driver (Redis, Memcached)

## Type Inference

Values are auto-typed when stored:
- `true`/`false` → boolean
- Numeric → integer
- Arrays → JSON
- Default → string
