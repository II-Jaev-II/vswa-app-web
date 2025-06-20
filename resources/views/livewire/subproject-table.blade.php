<div>
    @if (session()->has('message'))
    <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 3000)" x-show="show"
        x-transition:enter="transition ease-out duration-700" x-transition:enter-start="transform opacity-0 scale-90"
        x-transition:enter-end="transform opacity-100 scale-100" x-transition:leave="transition ease-in duration-500"
        x-transition:leave-start="transform opacity-100 scale-100"
        x-transition:leave-end="transform opacity-0 scale-90"
        class="fixed top-4 left-1/2 -translate-x-1/2 bg-green-500 text-white px-4 py-2 rounded shadow">
        {{ session('message') }}
    </div>
    @endif

    <div class="mb-4 mt-4">
        <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search Subproject"
            class="w-full md:w-1/2 px-4 py-2 border rounded shadow-sm focus:outline-none focus:ring" />
    </div>
    <div class="relative overflow-x-auto">
        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-300">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-zinc-700 dark:text-gray-200">
                <tr>
                    <th class="px-6 py-3">Project Name</th>
                    <th class="px-6 py-3">Location</th>
                    <th class="px-6 py-3">Project ID</th>
                    <th class="px-6 py-3">Contractor</th>
                    <th class="px-6 py-3">Project Type</th>
                    <th class="px-6 py-3">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($subprojects as $subproject)
                <tr wire:key="subproject-{{ $subproject->id }}"
                    class="bg-white border-b dark:bg-zinc-900 dark:border-gray-700">
                    <th class="px-6 py-4">{{ $subproject->project_name }}</th>
                    <td class="px-6 py-4">{{ $subproject->project_location }}</td>
                    <td class="px-6 py-4">{{ $subproject->project_id }}</td>
                    <td class="px-6 py-4">{{ $subproject->contractor }}</td>
                    <td class="px-6 py-4">{{ $subproject->project_type }}</td>
                    <td class="px-6 py-4">
                        <flux:button.group>
                            <flux:modal.trigger wire:key="trigger-edit-{{ $subproject->id }}"
                                name="edit-subproject-{{ $subproject->id }}"
                                wire:click="edit({{ $subproject->id }})">
                                <flux:button icon="pencil" variant="primary" color="sky" size="sm">Edit
                                </flux:button>
                            </flux:modal.trigger>

                            <flux:modal.trigger wire:key="trigger-delete-{{ $subproject->id }}"
                                name="delete-subproject-{{ $subproject->id }}"
                                wire:click="delete({{ $subproject->id }})">
                                <flux:button icon="trash" variant="primary" color="red" size="sm">Delete
                                </flux:button>
                            </flux:modal.trigger>

                            <flux:modal.trigger wire:key="trigger-pow-{{ $subproject->id }}"
                                name="pow-subproject-{{ $subproject->id }}">
                                <flux:button icon="document" variant="primary" color="green" size="sm">POW
                                </flux:button>
                            </flux:modal.trigger>
                        </flux:button.group>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-4 text-center text-gray-300">
                        No subprojects found.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @foreach ($subprojects as $subproject)
    {{-- Edit Subproject --}}
    <flux:modal wire:key="modal-edit-{{ $subproject->id }}" name="edit-subproject-{{ $subproject->id }}"
        class="w-full max-w-full sm:max-w-lg">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">Edit Project Information</flux:heading>
                <flux:text class="mt-2">Update the project information.</flux:text>
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

            <div class="flex justify-end gap-2">
                <flux:modal.close>
                    <flux:button variant="ghost">Cancel</flux:button>
                </flux:modal.close>
                <flux:button wire:click="update" type="button" variant="primary">Save</flux:button>
            </div>
        </div>
    </flux:modal>

    {{-- Delete Subproject --}}
    <flux:modal wire:key="modal-delete-{{ $subproject->id }}" name="delete-subproject-{{ $subproject->id }}"
        class="min-w-[22rem]">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">Delete Subproject?</flux:heading>
                <flux:text class="mt-2">
                    <p>You're about to delete this Subproject.</p>
                    <p>This action cannot be reversed.</p>
                </flux:text>
            </div>
            <div class="flex gap-2">
                <flux:spacer />
                <flux:modal.close>
                    <flux:button variant="ghost">Cancel</flux:button>
                </flux:modal.close>
                <flux:button wire:click="destroy({{ $subproject->id }})" type="submit" variant="danger">Delete
                    Subproject</flux:button>
            </div>
        </div>
    </flux:modal>

    <flux:modal wire:key="modal-pow-{{ $subproject->id }}" name="pow-subproject-{{ $subproject->id }}"
        class="w-screen h-screen max-w-none max-h-none m-0 p-0 rounded-none">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">Program of Works</flux:heading>
                <flux:text class="mt-2">Add or Update the list of program of works.</flux:text>
            </div>

            <livewire:program-of-works-upload
                :subproject_id="$subproject->id"
                wire:key="pow-upload-{{ $subproject->id }}" />
        </div>
    </flux:modal>
    @endforeach

    <div class="mt-4">
        {{ $subprojects->links() }}
    </div>
</div>