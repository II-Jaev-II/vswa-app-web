<x-layouts.app :title="__('Contractor Dashboard')">

    <div class="mb-4">
        <livewire:project-information />
    </div>

    <div class="flex items-center my-6">
        <span class="flex-grow border-t border-gray-300"></span>

        <span class="mx-4 text-zinc-200 text-lg font-medium">List of Construction Items</span>

        <span class="flex-grow border-t border-gray-300"></span>
    </div>


    <livewire:construction-items-table />

</x-layouts.app>
