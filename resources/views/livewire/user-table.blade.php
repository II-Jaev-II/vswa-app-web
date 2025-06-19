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
        <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search User"
            class="w-full md:w-1/2 px-4 py-2 border rounded shadow-sm focus:outline-none focus:ring" />
    </div>
    <div class="relative overflow-x-auto">
        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-300">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-zinc-700 dark:text-gray-200">
                <tr>
                    <th class="px-6 py-3">Name</th>
                    <th class="px-6 py-3">Assigned Subproject</th>
                    <th class="px-6 py-3">Contractor Name <span class="italic">(if applicable)</span></th>
                    <th class="px-6 py-3">Role</th>
                    <th class="px-6 py-3">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                <tr
                    class="bg-white border-b dark:bg-zinc-900 dark:border-gray-700">
                    <th class="px-6 py-4">{{ $user->name }}</th>
                    <td class="px-6 py-4">{{ $options[$user->subproject_assigned] ?? 'None' }}</td>
                    <td class="px-6 py-4">{{ $user->contractor_name }}</td>
                    <td class="px-6 py-4">{{ $user->role }}</td>
                    <td class="px-6 py-4">
                        <flux:button.group>
                            <flux:modal.trigger wire:key="trigger-edit-{{ $user->id }}"
                                name="edit-user-{{ $user->id }}"
                                wire:click="edit({{ $user->id }})">
                                <flux:button icon="pencil" variant="primary" color="sky" size="sm">Edit
                                </flux:button>
                            </flux:modal.trigger>

                            <flux:modal.trigger wire:key="trigger-delete-{{ $user->id }}"
                                name="delete-user-{{ $user->id }}"
                                wire:click="delete({{ $user->id }})">
                                <flux:button icon="trash" variant="primary" color="red" size="sm">Delete
                                </flux:button>
                            </flux:modal.trigger>
                        </flux:button.group>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-4 text-center text-gray-300">
                        No users found.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @foreach ($users as $user)
    {{-- Edit User --}}
    <flux:modal wire:key="modal-edit-{{ $user->id }}" name="edit-user-{{ $user->id }}"
        class="w-full max-w-full sm:max-w-lg">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">Edit User</flux:heading>
                <flux:text class="mt-2">Update the user information.</flux:text>
            </div>

            <flux:input wire:model="name" label="Name" />

            <flux:radio.group wire:model="role" label="Role" variant="segmented">
                <flux:radio label="ADMIN" value="ADMIN" />
                <flux:radio label="LGU/PG" value="LGU/PG" />
                <flux:radio label="CONTRACTOR" value="CONTRACTOR" />
            </flux:radio.group>

            <flux:input wire:model="contractor_name" label="Contractor Name (if applicable)" />

            {{-- Subproject select with search --}}
            <div
                x-data='{
                    open: false,
                    search: "",
                    options: @json($options),
                    selected: $wire.entangle("subproject_assigned")
                }'
                class="relative mt-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">
                    Assigned Subproject
                </label>

                <div
                    @click="open = !open"
                    class="mt-1 w-full bg-white dark:bg-zinc-800 border border-gray-300 dark:border-zinc-700 rounded p-2 flex justify-between items-center cursor-pointer">
                    <span x-text="options[selected] ?? 'Select a subproject'"></span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.06 1.06l-4.24 4.24a.75.75 0 01-1.06 0L5.25 8.27a.75.75 0 01-.02-1.06z"
                            clip-rule="evenodd" />
                    </svg>
                </div>

                <div
                    x-show="open"
                    @click.away="open = false"
                    class="absolute z-10 mt-1 w-full bg-white dark:bg-zinc-800 border border-gray-300 dark:border-zinc-700 rounded shadow-lg max-h-60 overflow-auto">
                    <div class="p-2">
                        <input
                            x-model="search"
                            type="text"
                            placeholder="Search…"
                            class="w-full px-2 py-1 border rounded focus:outline-none focus:ring" />
                    </div>

                    <template x-for="[id, label] in Object.entries(options)" :key="id">
                        <div
                            x-show="!search || label.toLowerCase().includes(search.toLowerCase())"
                            @click="selected = id; open = false"
                            class="px-4 py-2 cursor-pointer hover:bg-gray-200 dark:hover:bg-zinc-700">
                            <span x-text="label"></span>
                        </div>
                    </template>
                </div>
            </div>

            <div class="flex justify-end gap-2">
                <flux:modal.close>
                    <flux:button variant="ghost">Cancel</flux:button>
                </flux:modal.close>
                <flux:button wire:click="update" type="button" variant="primary">Save</flux:button>
            </div>
        </div>
    </flux:modal>

    {{-- Delete User --}}
    <flux:modal wire:key="modal-delete-{{ $user->id }}" name="delete-user-{{ $user->id }}"
        class="min-w-[22rem]">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">Delete User?</flux:heading>
                <flux:text class="mt-2">
                    <p>You're about to delete this User.</p>
                    <p>This action cannot be reversed.</p>
                </flux:text>
            </div>
            <div class="flex gap-2">
                <flux:spacer />
                <flux:modal.close>
                    <flux:button variant="ghost">Cancel</flux:button>
                </flux:modal.close>
                <flux:button wire:click="destroy({{ $user->id }})" type="submit" variant="danger">Delete
                    User</flux:button>
            </div>
        </div>
    </flux:modal>
    @endforeach

    <div class="mt-4">
        {{ $users->links() }}
    </div>
</div>