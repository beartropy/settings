<?php

namespace Beartropy\Settings\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $table = 'beartropy_settings';

    protected $fillable = [
        'key',
        'value',
        'group',
        'type',
        'label',
        'description',
        'options',
        'is_system',
    ];

    protected $casts = [
        'is_system' => 'boolean',
        'options' => 'array',
    ];

    protected static function booted()
    {
        static::saved(function ($setting) {
            Cache::forget('beartropy_settings.setting.' . $setting->key);
            Cache::forget('beartropy_settings.settings.all');
        });

        static::deleted(function ($setting) {
            Cache::forget('beartropy_settings.setting.' . $setting->key);
            Cache::forget('beartropy_settings.settings.all');
        });
    }

    public function getValueAttribute($value)
    {
        return match ($this->type) {
            'boolean', 'toggle' => filter_var($value, FILTER_VALIDATE_BOOLEAN),
            'integer', 'number' => (int) $value,
            'array', 'json' => json_decode($value, true),
            default => $value,
        };
    }

    public function setValueAttribute($value)
    {
        $this->attributes['value'] = match ($this->type) {
            'array', 'json' => json_encode($value),
            'boolean', 'toggle' => $value ? '1' : '0',
            default => (string) $value,
        };
    }
}
