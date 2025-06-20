<x-layouts.app :title="__('Users')">
    <div class="relative mb-6 w-full">
        <flux:heading size="xl" level="1">{{ __('Users') }}</flux:heading>
        <flux:subheading size="lg" class="mb-6">{{ __('List of Users') }}</flux:subheading>
        <flux:separator variant="subtle" />
    </div>

    <livewire:user-table />
</x-layouts.app>