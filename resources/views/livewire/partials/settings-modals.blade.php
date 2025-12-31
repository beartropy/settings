<x-beartropy-ui::slider wire:model="showModal" max-width="max-w-lg">
    <x-slot name="title">{{ $isEditing ? 'Edit Setting' : 'Create Setting' }}</x-slot>
    <div class="space-y-4">
        {{-- Group --}}
        <x-beartropy-ui::input fill label="Group" wire:model="group" />

        {{-- Key --}}
        <x-beartropy-ui::input fill label="Key" wire:model="key" />

        {{-- Label --}}
        <x-beartropy-ui::input fill label="Label" wire:model="label" />

        {{-- Type --}}
        <x-beartropy-ui::select fill label="Type" wire:model.live="type" :searchable="false" :clearable="false"
            :options="[
                'text' => 'Text',
                'boolean' => 'Boolean',
                'number' => 'Number',
                'textarea' => 'Long Text',
                'select' => 'Select',
                'json' => 'JSON',
            ]" />

        {{-- Options (For Select) --}}
        @if ($type === 'select')
            <x-beartropy-ui::textarea fill label="Options (JSON)" wire:model="settingOptions" rows="3"
                placeholder='{"opt1": "Label 1", "opt2": "Label 2"}' />
        @endif

        {{-- Value --}}
        <div>
            @if ($type === 'boolean' || $type === 'toggle')
                <div class="pt-6">
                    <x-beartropy-ui::toggle fill label="Value" wire:model="value" />
                </div>
            @elseif($type === 'textarea' || $type === 'json')
                <x-beartropy-ui::textarea fill label="Value" wire:model="value" rows="8" />
            @elseif($type === 'select')
                <x-beartropy-ui::select fill label="Value" wire:model="value" :options="json_decode($settingOptions, true) ?? []" />
            @else
                <x-beartropy-ui::input fill label="Value" wire:model="value" />
            @endif
        </div>

        {{-- Description --}}
        <x-beartropy-ui::textarea fill label="Description" wire:model="description" rows="2" />

    </div>

    <x-slot name="footer">
        <div class="flex justify-end gap-3 w-full">
            <x-beartropy-ui::button zinc outline wire:click="$set('showModal', false)">
                Cancel
            </x-beartropy-ui::button>

            <x-beartropy-ui::button emerald soft wire:click="save">
                Save
            </x-beartropy-ui::button>
        </div>
    </x-slot>
</x-beartropy-ui::slider>
