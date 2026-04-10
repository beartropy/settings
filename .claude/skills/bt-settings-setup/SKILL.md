---
name: bt-settings-setup
description: Help users install and configure Beartropy Settings in their Laravel projects
version: 1.0.0
author: Beartropy
tags: [beartropy, settings, installation, setup, configuration]
---

# Beartropy Settings Setup Guide

You are an expert in helping users install and configure Beartropy Settings in their Laravel applications.

---

## Requirements

- PHP >= 8.2
- Laravel >= 10.x
- Livewire >= 3.x
- beartropy/ui (installed automatically)
- beartropy/tables (installed automatically)

---

## Installation

### Step 1: Install via Composer

```bash
composer require beartropy/settings
```

### Step 2: Run Migrations

```bash
php artisan migrate
```

This creates the `beartropy_settings` table.

### Step 3: Publish Config (optional)

```bash
php artisan vendor:publish --tag=beartropy-settings-config
```

---

## Usage

### Facade API

```php
use Beartropy\Settings\Facades\BeartropySettings;

// Get a setting with default
$value = BeartropySettings::get('app.name', 'Default');

// Set a setting
BeartropySettings::set('app.name', 'My App');

// Check existence
BeartropySettings::has('app.name');

// Get all settings
$all = BeartropySettings::all();
```

### Helper Functions

```php
$value = get_setting('app.name', 'Default');
set_setting('app.name', 'My App');
```

### In Blade Templates

```blade
<h1>{{ get_setting('app.name', config('app.name')) }}</h1>
```

---

## Management UI

Add the Livewire component to your admin layout:

```blade
@livewire('beartropy-settings-manager')
```

This provides a full CRUD interface with:
- Searchable/sortable settings table
- Modal form for creating/editing settings
- 7 field types: text, boolean, number, textarea, select, json, toggle
- Group-based organization with dot notation keys
- System settings protection (cannot be deleted via UI)

---

## Dot Notation

Settings use dot notation for grouping:

```php
// Stored as group: "mail", key: "from_address"
BeartropySettings::set('mail.from_address', 'hello@example.com');

// Keys without dots use "general" group
BeartropySettings::set('theme', 'dark'); // group: "general", key: "theme"
```

---

## Caching

Settings are cached forever with automatic invalidation on save/delete. Requires a cache driver that supports tags (Redis, Memcached).

---

## Troubleshooting

### Settings not persisting
- Ensure the `beartropy_settings` table exists: `php artisan migrate`
- Check that your cache driver supports tags (not `file` or `array`)

### Management UI not showing
- Ensure Beartropy UI assets are included: `@BeartropyAssets` in your layout
- Component is registered as `beartropy-settings-manager`
