<?php

namespace Beartropy\Settings\Livewire;

use Beartropy\Tables\YATBaseTable;
use Beartropy\Tables\Classes\Columns\Column;
use Beartropy\Settings\Models\Setting;
use Livewire\Attributes\On;

/**
 * Class SettingsManager.
 *
 * Livewire component for managing application settings.
 */
class SettingsManager extends YATBaseTable
{
    /**
     * The model class used by this component.
     *
     * @var string
     */
    public $model = Setting::class;

    // Form fields
    /** @var bool Whether to show the modal. */
    public $showModal = false;

    /** @var bool Whether we are in editing mode. */
    public $isEditing = false;

    /** @var int|null The ID of the setting being edited. */
    public $editingId = null;

    /** @var string The setting group. */
    public $group = 'general';

    /** @var string The setting key. */
    public $key = '';

    /** @var string The setting label. */
    public $label = '';

    /** @var string The setting value. */
    public $value = '';

    /** @var string The setting type. */
    public $type = 'text';

    /** @var string The setting description. */
    public $description = '';

    /** @var string The setting options (JSON). */
    public $settingOptions = '';

    protected $rules = [
        'group' => 'required|string',
        'key' => 'required|string|regex:/^[a-z0-9_.]+$/',
        'label' => 'required|string',
        'type' => 'required|in:text,boolean,toggle,number,textarea,select,json',
        'value' => 'nullable',
        'description' => 'nullable|string',
        'settingOptions' => 'nullable',
    ];

    /**
     * Configure the table settings.
     *
     * @return void
     */
    public function settings()
    {
        $this->setTitle('Settings Manager');
        $this->setModalsView('beartropy-settings::livewire.partials.settings-modals');
        $this->showColumnToggle(false);
        $this->showCardsOnMobile(true);
        $this->addButtons([
            [
                'label' => 'Create Setting',
                'action' => 'create',
                'icon' => 'plus',
                'color' => 'emerald',
            ]
        ]);
    }

    /**
     * Define the table columns.
     *
     * @return array
     */
    public function columns()
    {
        return [
            Column::make('group')
                ->sortable()
                ->searchable()
                ->showOnCard(),

            Column::make('key')
                ->sortable()
                ->searchable()
                ->cardTitle(),

            Column::make('value')
                ->customData(function ($row) {
                    if ($row->type == "select") {
                        return $row->options[$row->value] ?? $row->value;
                    } elseif ($row->type == "boolean" || $row->type == "toggle") {
                        return $row->value ? 'True' : 'False';
                    }
                    return $row->value;
                })
                ->searchable(function ($query, $searchTerm) {
                    $query->orWhere(function ($q) use ($searchTerm) {
                        $q->where('value', 'like', "%{$searchTerm}%");

                        if (\config('database.default') === 'sqlite') {
                            $q->orWhereRaw("json_extract(options, '$.' || \"value\") LIKE ?", ["%{$searchTerm}%"]);
                        } else {
                            $q->orWhereRaw("LOWER(JSON_UNQUOTE(JSON_EXTRACT(options, CONCAT('$.', `value`)))) LIKE ?", ["%" . strtolower($searchTerm) . "%"]);
                        }
                    });
                })
                ->sortable(function ($query, $direction) {
                    if (\config('database.default') === 'sqlite') {
                        $sql = "COALESCE(json_extract(options, '$.' || \"value\"), value)";
                    } else {
                        $sql = "COALESCE(JSON_UNQUOTE(JSON_EXTRACT(options, CONCAT('$.', `value`))), `value`)";
                    }
                    $query->orderByRaw("$sql $direction");
                })
                ->showOnCard(),

            Column::make('type')
                ->sortable()
                ->showOnCard(),

            Column::make('is_system')
                ->hideWhen(true)
                ->showOnCard(),

            Column::make('Actions')
                ->view('beartropy-settings::partials.settings-actions')->pushRight(),
        ];
    }

    /**
     * Show the modal to create a new setting.
     *
     * @return void
     */
    public function create()
    {
        $this->reset(['group', 'key', 'label', 'value', 'type', 'description', 'settingOptions', 'editingId']);
        $this->isEditing = false;
        $this->showModal = true;
    }

    /**
     * Show the modal to edit an existing setting.
     *
     * @param  int  $id
     * @return void
     */
    public function edit($id)
    {
        $setting = Setting::findOrFail($id);

        $this->editingId = $setting->id;
        $this->group = $setting->group;
        $this->key = $setting->key;
        $this->label = $setting->label;
        $this->value = $setting->value;
        $this->type = $setting->type;
        $this->description = $setting->description;
        $this->settingOptions = $setting->options ? json_encode($setting->options, JSON_PRETTY_PRINT) : '';

        $this->isEditing = true;
        $this->showModal = true;
    }

    /**
     * Save the setting.
     *
     * @return void
     */
    public function save()
    {
        $this->validate();

        $data = [
            'group' => $this->group,
            'key' => $this->key,
            'label' => $this->label,
            'value' => $this->value,
            'type' => $this->type,
            'description' => $this->description,
            'options' => $this->settingOptions ? json_decode($this->settingOptions, true) : null,
        ];

        if ($this->isEditing) {
            $setting = Setting::findOrFail($this->editingId);
            $setting->update($data);
            $this->dispatch('beartropy-toast', type: 'success', message: 'Setting updated successfully.');
        } else {
            Setting::create($data);
            $this->dispatch('beartropy-toast', type: 'success', message: 'Setting created successfully.');
        }

        $this->showModal = false;

        // Refresh table data
        $this->refresh();
    }

    /**
     * Delete a setting.
     *
     * @param  int  $id
     * @return void
     */
    public function delete($id)
    {
        $setting = Setting::findOrFail($id);

        if ($setting->is_system) {
            $this->dispatch('beartropy-toast', type: 'error', message: 'Cannot delete system settings.');
            return;
        }

        $setting->delete();
        $this->dispatch('beartropy-toast', type: 'success', message: 'Setting deleted.');
        $this->refresh();
    }
}
