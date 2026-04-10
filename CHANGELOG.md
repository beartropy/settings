# Changelog

All notable changes to this project will be documented in this file.

## [1.1.4] - 2026-04-10

### Added
- **Docs**: AI assistant documentation (`docs/components/`, `docs/llms/`, `docs/ai-assistants/`) with API reference, usage examples, AI reference, cursor rules, and code examples.
- **Skills**: Claude Code skills (`bt-settings-setup`, `bt-settings-component`) and `skills.json` manifest for the `beartropy:skills` installer command.

## [1.1.3] - 2026-04-07

### Changed
- Added Laravel 13 support (`illuminate/support: ^13.0`)

## [1.1.2] - 2026-02-01
### Changed
- Updated `livewire/livewire` dependency to allow `^4.0`.

## [1.1.1] - 2026-01-18
### Changed
- Improved sort order in SettingsManager (now sorted by group by default).
- Small UI fixes in settings table actions.

## [1.1.0] - 2025-12-31
### Added
- Standard PHP DocBlocks to all functions and classes for better IDE support and code clarity.

## [1.0.5] - 2025-12-31
### Changed
- displayed 'True'/'False' for boolean and toggle setting types in the manager.

## [1.0.4] - 2025-12-31
### Changed
- Improved searchability for setting values (handles both select and input types).
- Code cleanup in SettingsManager service.

## [1.0.3] - 2025-12-30
### Added
- Initial changelog creation.

### Changed
- Settings manager UI refactor.
- Model updates for validation.
