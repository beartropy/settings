# Settings Examples - Beartropy Settings

## Basic Read/Write

```php
use Beartropy\Settings\Facades\BeartropySettings;

// Store application settings
BeartropySettings::set('app.name', 'My Application');
BeartropySettings::set('app.timezone', 'America/New_York');
BeartropySettings::set('app.maintenance', false);

// Read with defaults
$name = BeartropySettings::get('app.name', 'Default App');
$tz = BeartropySettings::get('app.timezone', 'UTC');
```

## In Blade Templates

```blade
<title>{{ get_setting('app.name', config('app.name')) }}</title>

@if(get_setting('features.dark_mode', true))
    <x-bt-toggle-theme />
@endif

<footer>
    <p>{{ get_setting('app.footer_text', '© ' . date('Y')) }}</p>
</footer>
```

## In Livewire Components

```php
class Dashboard extends Component
{
    public function render()
    {
        return view('livewire.dashboard', [
            'perPage' => (int) get_setting('dashboard.per_page', 10),
            'showChart' => (bool) get_setting('dashboard.show_chart', true),
        ]);
    }
}
```

## Seeding Settings

```php
// database/seeders/SettingsSeeder.php
use Beartropy\Settings\Models\Setting;

class SettingsSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            ['group' => 'app', 'key' => 'name', 'value' => 'My App', 'type' => 'text', 'label' => 'Application Name', 'is_system' => true],
            ['group' => 'app', 'key' => 'maintenance', 'value' => 'false', 'type' => 'boolean', 'label' => 'Maintenance Mode'],
            ['group' => 'mail', 'key' => 'from_address', 'value' => 'noreply@example.com', 'type' => 'text', 'label' => 'From Address'],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(
                ['group' => $setting['group'], 'key' => $setting['key']],
                $setting
            );
        }
    }
}
```

## Management UI

```blade
{{-- In your admin layout --}}
<div class="max-w-7xl mx-auto py-6">
    @livewire('beartropy-settings-manager')
</div>
```
