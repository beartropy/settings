# beartropy-settings-manager — AI Reference

## Component Registration
```blade
@livewire('beartropy-settings-manager')
```

## Architecture
- `SettingsManager` → extends `YATBaseTable` (Beartropy Tables)
- Namespace: `Beartropy\Settings\Livewire`
- View: `beartropy-settings::livewire.partials.settings-modals`
- Registered as Livewire component `beartropy-settings-manager`

## Public Properties

| Property | PHP Type | Default |
|----------|----------|---------|
| `model` | `string` | `Setting::class` |
| `showModal` | `bool` | `false` |
| `isEditing` | `bool` | `false` |
| `editingId` | `?int` | `null` |
| `group` | `string` | `'general'` |
| `key` | `string` | `''` |
| `label` | `string` | `''` |
| `value` | `mixed` | `''` |
| `type` | `string` | `'text'` |
| `description` | `string` | `''` |
| `settingOptions` | `string` | `''` |

## Key Methods
- `settings(): void` — Configures table (title, buttons, sorting, modal view)
- `columns(): array` — Defines table columns with custom rendering
- `create(): void` — Resets form and opens modal for new setting
- `edit(int $id): void` — Loads setting data into form for editing
- `save(): void` — Validates and creates/updates setting
- `delete(int $id): void` — Deletes setting (blocks system settings)

## Validation Rules
- `group`: required, string, max 255
- `key`: required, string, max 255, regex `/^[a-z0-9_.]+$/`, unique per group
- `label`: required, string, max 255
- `value`: nullable
- `type`: required, in text/boolean/number/textarea/select/json/toggle
- `description`: nullable, string, max 1000

## Common Pitfalls
- Key validation enforces lowercase alphanumeric with dots and underscores only
- System settings (`is_system = true`) cannot be deleted through the UI
- The `toggle` type is deprecated — use `boolean` instead
