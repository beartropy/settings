---
name: bt-settings-component
description: Get detailed information and examples for Beartropy Settings components and API
version: 1.0.0
author: Beartropy
tags: [beartropy, settings, components, documentation, examples]
---

# Beartropy Settings Component Helper

You are an expert in Beartropy Settings. Use this guide to help users with the settings API, management UI, and model.

---

## Quick Reference

| Task | Method |
|---|---|
| Get a setting | `BeartropySettings::get('key', 'default')` or `get_setting('key', 'default')` |
| Set a setting | `BeartropySettings::set('key', 'value')` or `set_setting('key', 'value')` |
| Check existence | `BeartropySettings::has('key')` |
| Get all | `BeartropySettings::all()` |
| Management UI | `@livewire('beartropy-settings-manager')` |
