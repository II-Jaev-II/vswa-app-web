<div>
    @if (session()->has('message'))
    <div
        x-data="{ show: true }"
        x-init="setTimeout(() => show = false, 3000)"
        x-show="show"
        x-transition:enter="transition ease-out duration-700"
        x-transition:enter-start="transform opacity-0 scale-90"
        x-transition:enter-end="transform opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-500"
        x-transition:leave-start="transform opacity-100 scale-100"
        x-transition:leave-end="transform opacity-0 scale-90"
        class="fixed top-4 left-1/2 -translate-x-1/2 bg-green-500 text-white px-4 py-2 rounded shadow">
        {{ session('message') }}
    </div>
    @endif

    <flux:modal.trigger name="create-subproject">
        <flux:button>Add Subproject</flux:button>
    </flux:modal.trigger>

    <flux:modal name="create-subproject" class="w-full max-w-full sm:max-w-lg">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">Project Information</flux:heading>
                <flux:text class="mt-2">Fill up the project information.</flux:text>
            </div>

            <flux:input wire:model="projectName" label="Project Name" />

            <flux:input wire:model="projectLocation" label="Project Location" />

            <flux:input wire:model="projectId" label="Project ID" />

            <flux:input wire:model="contractor" label="Contractor" />

            <flux:radio.group wire:model="projectType" label="Project Type" variant="segmented">
                <flux:radio label="FMR" value="FMR" />
                <flux:radio label="FMR with Bridge" value="FMR with Bridge" />
                <flux:radio label="VCRI" value="VCRI" />
                <flux:radio label="CIS" value="CIS" />
                <flux:radio label="PWS" value="PWS" />
            </flux:radio.group>

            <div class="flex">
                <flux:spacer />

                <flux:button type="button" variant="primary" wire:click="submit">Save</flux:button>
            </div>
        </div>
    </flux:modal>

    <livewire:subproject-table />
</div>