<?php

namespace Beartropy\Settings\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

/**
 * Class Setting.
 *
 * Represents a setting in the database.
 *
 * @property int $id
 * @property string $group
 * @property string $key
 * @property string $value
 * @property string $type
 * @property string $label
 * @property string $description
 * @property array $options
 * @property bool $is_system
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
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

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::saved(function ($setting) {
            Cache::forget('beartropy_settings.setting.' . $setting->group . '.' . $setting->key);
            Cache::forget('beartropy_settings.setting.' . $setting->key);
            Cache::forget('beartropy_settings.settings.all');
        });

        static::deleted(function ($setting) {
            Cache::forget('beartropy_settings.setting.' . $setting->group . '.' . $setting->key);
            Cache::forget('beartropy_settings.setting.' . $setting->key);
            Cache::forget('beartropy_settings.settings.all');
        });
    }

    /**
     * Get the value attribute.
     *
     * @param  mixed  $value
     * @return mixed
     */
    public function getValueAttribute($value)
    {
        return match ($this->type) {
            'boolean', 'toggle' => filter_var($value, FILTER_VALIDATE_BOOLEAN),
            'integer', 'number' => (int) $value,
            'array', 'json' => json_decode($value, true),
            default => $value,
        };
    }

    /**
     * Set the value attribute.
     *
     * @param  mixed  $value
     * @return void
     */
    public function setValueAttribute($value)
    {
        $this->attributes['value'] = match ($this->type) {
            'array', 'json' => json_encode($value),
            'boolean', 'toggle' => $value ? '1' : '0',
            default => (string) $value,
        };
    }
}
