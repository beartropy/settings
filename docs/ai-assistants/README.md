# AI Assistant Support for Beartropy Settings

Beartropy Settings includes AI assistant integration to help you manage application settings.

## Supported AI Assistants

### Claude Code / Cursor / Other AI Tools
- Universal guide with API reference
- Cursor rules for component suggestions
- Copy-paste ready examples

## Directory Structure

```
beartropy/settings/
└── docs/
    ├── llms/                      # LLM reference docs
    ├── components/                # User reference docs
    └── ai-assistants/
        ├── README.md              # This file
        ├── BEARTROPY_GUIDE.md     # Universal AI guide
        ├── cursor/
        │   └── .cursorrules       # Cursor configuration
        └── examples/
            └── settings.md        # Usage examples
```

## Quick Start

### Using with Cursor

```bash
cp vendor/beartropy/settings/docs/ai-assistants/cursor/.cursorrules .cursorrules
```

### Using with Other AI Tools

Point your AI assistant to:
```
vendor/beartropy/settings/docs/ai-assistants/BEARTROPY_GUIDE.md
```

## License

MIT License.
