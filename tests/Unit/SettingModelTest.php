<?php

use Beartropy\Settings\Models\Setting;

it('casts boolean values correctly', function () {
    $setting = new Setting();
    $setting->type = 'boolean';

    $setting->value = true;
    expect($setting->value)->toBeTrue();
    expect($setting->getAttributes()['value'])->toBe('1');

    $setting->value = false;
    expect($setting->value)->toBeFalse();
    expect($setting->getAttributes()['value'])->toBe('0');
});

it('casts integer values correctly', function () {
    $setting = new Setting();
    $setting->type = 'integer';

    $setting->value = 123;
    expect($setting->value)->toBe(123);

    $setting->value = '456';
    expect($setting->value)->toBe(456);
});

it('casts array values correctly', function () {
    $setting = new Setting();
    $setting->type = 'array';

    $data = ['foo' => 'bar'];
    $setting->value = $data;

    expect($setting->value)->toBe($data);
    expect($setting->getAttributes()['value'])->toBe(json_encode($data));
});

it('casts json values correctly', function () {
    $setting = new Setting();
    $setting->type = 'json';

    $data = ['foo' => 'bar'];
    $setting->value = $data;

    expect($setting->value)->toBe($data);
    expect($setting->getAttributes()['value'])->toBe(json_encode($data));
});

it('returns raw value for unknown types', function () {
    $setting = new Setting();
    $setting->type = 'unknown';

    $setting->value = 'some value';
    expect($setting->value)->toBe('some value');
});
