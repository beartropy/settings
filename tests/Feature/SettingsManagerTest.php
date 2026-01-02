<?php

use Beartropy\Settings\Models\Setting;
use Illuminate\Support\Facades\Cache;
use Beartropy\Settings\Facades\BeartropySettings;

it('can store and retrieve a setting', function () {
    BeartropySettings::set('site_name', 'My Site');

    expect(BeartropySettings::get('site_name'))->toBe('My Site');
});

it('can store and retrieve a group setting', function () {
    BeartropySettings::set('general.site_name', 'My Site');

    expect(BeartropySettings::get('general.site_name'))->toBe('My Site');

    $setting = Setting::where('group', 'general')->where('key', 'site_name')->first();
    expect($setting)->not->toBeNull();
    expect($setting->value)->toBe('My Site');
});

it('returns default value if setting does not exist', function () {
    expect(BeartropySettings::get('non_existent', 'default_value'))->toBe('default_value');
});

it('checks if a setting exists', function () {
    expect(BeartropySettings::has('site_name'))->toBeFalse();

    BeartropySettings::set('site_name', 'My Site');

    expect(BeartropySettings::has('site_name'))->toBeTrue();
});

it('caches the setting value', function () {
    BeartropySettings::set('site_name', 'My Site');

    // First call to populate cache
    BeartropySettings::get('site_name');

    // Modify database directly to verify cache usage
    Setting::where('key', 'site_name')->update(['value' => 'Hacked Site']);

    // Should still return cached value
    expect(BeartropySettings::get('site_name'))->toBe('My Site');

    // Clear cache
    Cache::flush();

    // Should now return new value
    expect(BeartropySettings::get('site_name'))->toBe('Hacked Site'); // Note: cache logic in manager might use specific tags, check implementation
});

it('infers type correctly when setting value', function () {
    BeartropySettings::set('is_active', true);
    $setting = Setting::where('key', 'is_active')->first();
    expect($setting->type)->toBe('boolean');

    BeartropySettings::set('items', ['a', 'b']);
    $setting = Setting::where('key', 'items')->first();
    expect($setting->type)->toBe('array');
});

it('can retrieve all settings', function () {
    BeartropySettings::set('group1.key1', 'value1');
    BeartropySettings::set('group2.key2', 'value2');

    $all = BeartropySettings::all();

    expect($all)->toBeArray();
    expect($all)->toHaveKey('group1.key1');
    expect($all)->toHaveKey('group2.key2');
    expect($all['group1.key1'])->toBe('value1');
});
