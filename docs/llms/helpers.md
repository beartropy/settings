# Helper Functions — AI Reference

## Functions

### `get_setting(string $key, $default = null): mixed`
- Resolves `beartropy-settings` from container
- Calls `SettingsManager::get($key, $default)`

### `set_setting(string $key, $value = null): void`
- Resolves `beartropy-settings` from container
- Calls `SettingsManager::set($key, $value)`

## File Location
- `src/helpers.php`
- Auto-loaded via composer.json `autoload.files`

## Common Pitfalls
- These are global functions — available anywhere after package installation
- They resolve the singleton each call, so they always use the same cached instance
