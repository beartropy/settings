<div class="p-6 space-y-8 bg-white dark:bg-zinc-900 rounded-lg shadow">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-bold text-zinc-900 dark:text-zinc-100">Application Settings</h2>
            <p class="text-sm text-zinc-500 dark:text-zinc-400">Manage global application configuration.</p>
        </div>

        <x-beartropy-ui::button wire:click="create" outline emerald icon-start="plus" label="Add Setting" />
    </div>

    <div class="space-y-8">
        @forelse ($this->groups as $groupName => $groupSettings)
            <div class="space-y-4">
                <h3
                    class="text-lg font-semibold capitalize text-zinc-800 dark:text-zinc-200 border-b border-zinc-200 dark:border-zinc-800 pb-2">
                    {{ $groupName }}
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @foreach ($groupSettings as $setting)
                        <div wire:key="setting-{{ $setting->id }}" x-data="{ showSaved: false }"
                            x-on:setting-saved.window="if ($event.detail.id == {{ $setting->id }}) { showSaved = true; setTimeout(() => showSaved = false, 2000); }"
                            class="relative p-4 border border-zinc-200 dark:border-zinc-800 rounded-lg bg-zinc-50 dark:bg-zinc-800/50 group hover:border-zinc-300 dark:hover:border-zinc-700 transition-colors">
                            <div class="flex justify-between items-start mb-2">
                                <div class="flex items-center gap-2">
                                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300"
                                        for="setting_{{ $setting->id }}">
                                        {{ $setting->label ?? $setting->key }}
                                    </label>
                                    <span x-show="showSaved" x-transition.opacity.duration.500ms
                                        class="text-xs font-medium text-emerald-500 flex items-center">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        Saved
                                    </span>
                                </div>

                                <div
                                    class="flex items-center space-x-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                    <button wire:click="edit({{ $setting->id }})"
                                        class="p-1 text-zinc-400 hover:text-blue-500 rounded">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z">
                                            </path>
                                        </svg>
                                    </button>
                                    @if (!$setting->is_system)
                                        <button wire:click="delete({{ $setting->id }})"
                                            wire:confirm="Are you sure you want to delete this setting?"
                                            class="p-1 text-zinc-400 hover:text-red-500 rounded">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                </path>
                                            </svg>
                                        </button>
                                    @endif
                                </div>
                            </div>

                            @if ($setting->description)
                                <p class="text-xs text-zinc-500 mb-3">{{ $setting->description }}</p>
                            @endif

                            <div class="mt-1">
                                @switch($setting->type)
                                    @case('toggle')
                                    @case('boolean')
                                        <x-beartropy-ui::toggle wire:model.live="settingValues.{{ $setting->id }}"
                                            :checked="$settingValues[$setting->id]" label="" />
                                    @break

                                    @case('select')
                                        <x-beartropy-ui::select fill wire:model.live="settingValues.{{ $setting->id }}"
                                            :options="is_string($setting->options)
                                                ? json_decode($setting->options, true)
                                                : $setting->options ?? []" />
                                    @break

                                    @case('textarea')
                                        <x-beartropy-ui::textarea fill wire:model.blur="settingValues.{{ $setting->id }}" />
                                    @break

                                    @case('number')
                                        <x-beartropy-ui::input fill type="number"
                                            wire:model.blur="settingValues.{{ $setting->id }}" />
                                    @break

                                    @default
                                        <x-beartropy-ui::input fill type="text"
                                            wire:model.blur="settingValues.{{ $setting->id }}" />
                                @endswitch
                            </div>
                            <div class="mt-1 text-xs text-zinc-400 font-mono select-all">{{ $setting->key }}</div>
                        </div>
                    @endforeach
                </div>
            </div>
            @empty
                <div class="text-center py-12">
                    <p class="text-zinc-500">No settings defined yet.</p>
                    <button wire:click="create" class="mt-2 text-blue-600 hover:underline cursor-pointer">Create your first
                        setting</button>
                </div>
            @endforelse
        </div>

        {{-- Modal --}}
        <x-beartropy-ui::modal wire:model="showModal" styled>
            <x-slot name="title">
                {{ $isEditing ? 'Edit Setting' : 'New Setting' }}
            </x-slot>
            <div class="space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div class="col-span-1">
                        <x-beartropy-ui::lookup fill label="Group" wire:model="group" :options="$this->groups->keys()->toArray()" />
                    </div>
                    <div class="col-span-1">
                        <x-beartropy-ui::input fill label="Key" type="text" wire:model="key" placeholder="site.name"
                            :disabled="$isEditing" />
                    </div>
                </div>

                <div>
                    <x-beartropy-ui::input fill label="Label" type="text" wire:model="label" placeholder="Site Name" />
                </div>

                <div>
                    <x-beartropy-ui::select fill label="Type" wire:model.live="type" :clearable="false" :searchable="false"
                        :options="[
                            'text' => 'Text',
                            'textarea' => 'Long Text',
                            'boolean' => 'Boolean (Toggle)',
                            'number' => 'Number',
                            'select' => 'Select / Dropdown',
                            'json' => 'JSON',
                        ]" />
                </div>

                @if ($type === 'select')
                    <div>
                        <x-beartropy-ui::textarea fill label="Options (JSON)" wire:model="options"
                            placeholder='{"key": "Label", "key2": "Label2"}' />
                        <p class="text-xs text-zinc-500">Provide a JSON object for options.</p>
                    </div>
                @endif

                <div>
                    <x-beartropy-ui::textarea fill label="Description" wire:model="description" rows="2" />
                </div>

                @if (!$isEditing)
                    <div>
                        <x-beartropy-ui::input fill label="Initial Value" type="text" wire:model="value" />
                    </div>
                @endif
            </div>

            <x-slot:footer class="flex gap-2 justify-end">
                <x-beartropy-ui::button slate outline wire:click="$set('showModal', false)" label="Cancel" />
                <x-beartropy-ui::button wire:click="save" color="{{ $isEditing ? 'blue' : 'emerald' }}" outline>
                    {{ $isEditing ? 'Update Setting' : 'Create Setting' }}
                </x-beartropy-ui::button>
            </x-slot:footer>
        </x-beartropy-ui::modal>
    </div>
