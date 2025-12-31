<div class="flex items-center justify-end gap-2 pr-3">
    <div wire:click="edit({{ $row['id'] }})">
        <x-beartropy-ui::icon name="edit" set="beartropy"
            class="w-5 h-5 cursor-pointer text-beartropy-600 hover:text-beartropy-500 dark:text-beartropy-400 dark:hover:text-beartropy-300" />
    </div>

    @unless ($row['is_system'] ?? false)
        <div wire:click="delete({{ $row['id'] }})" wire:confirm="Are you sure you want to delete this setting?">
            <x-beartropy-ui::icon name="trash" set="beartropy"
                class="w-5 h-5 cursor-pointer text-red-600 hover:text-red-500 dark:text-red-400 dark:hover:text-red-300" />
        </div>
    @endunless
</div>
