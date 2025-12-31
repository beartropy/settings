<?php

namespace Beartropy\Settings\Livewire;

use Beartropy\Tables\YATBaseTable;
use Beartropy\Tables\Classes\Columns\Column;
use Beartropy\Settings\Models\Setting;
use Livewire\Attributes\On;

class SettingsManager extends YATBaseTable
{
    public $model = Setting::class;

    // Form fields
    public $showModal = false;
    public $isEditing = false;
    public $editingId = null;

    public $group = 'general';
    public $key = '';
    public $label = '';
    public $value = '';
    public $type = 'text';
    public $description = '';
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
                ->editable()
                ->cardTitle(),

            Column::make('value')
                ->searchable()
                ->showOnCard()
                ->editable(),

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

    public function create()
    {
        $this->reset(['group', 'key', 'label', 'value', 'type', 'description', 'settingOptions', 'editingId']);
        $this->isEditing = false;
        $this->showModal = true;
    }

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
