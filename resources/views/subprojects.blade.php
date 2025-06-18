<x-layouts.app :title="__('Subprojects')">
    <div class="relative mb-6 w-full">
        <flux:heading size="xl" level="1">{{ __('Subprojects') }}</flux:heading>
        <flux:subheading size="lg" class="mb-6">{{ __('List of Subprojects') }}</flux:subheading>
        <flux:separator variant="subtle" />
    </div>

    <livewire:subprojects />
</x-layouts.app>