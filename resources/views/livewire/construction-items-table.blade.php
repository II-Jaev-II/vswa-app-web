<div>
    <div class="mb-4 mt-4">
        <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search Construction Item"
            class="w-full md:w-1/2 px-4 py-2 border rounded-sm shadow-sm focus:outline-none focus:ring" />
    </div>
    <div class="relative overflow-x-auto">
        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-300">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-zinc-700 dark:text-gray-200">
                <tr>
                    <th class="px-6 py-3">Item No.</th>
                    <th class="px-6 py-3">Scope of Work</th>
                    <th class="px-6 py-3">Quantity</th>
                    <th class="px-6 py-3">Unit</th>
                    <th class="px-6 py-3">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($itemLists as $item)
                    <tr wire:key="item-{{ $item->id }}"
                        class="bg-white border-b dark:bg-zinc-900 dark:border-gray-700">
                        <th class="px-6 py-4">{{ $item->item_no }}</th>
                        <td class="px-6 py-4">{{ $item->scope_of_work }}</td>
                        <td class="px-6 py-4">{{ $item->quantity }}</td>
                        <td class="px-6 py-4">{{ $item->unit }}</td>
                        <td class="px-6 py-4">
                            <flux:button.group>
                                <flux:modal.trigger wire:key="trigger-upload-{{ $item->id }}"
                                    name="item-upload-{{ $item->id }}"
                                    wire:click="showUploadModal({{ $item->id }})">
                                    <flux:button icon="arrow-up-on-square" variant="primary" color="sky"
                                        size="sm">Upload
                                        Geotag Photos
                                    </flux:button>
                                </flux:modal.trigger>
                            </flux:button.group>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-4 text-center text-gray-300">
                            No construction items found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @foreach ($itemLists as $item)
        <flux:modal wire:key="modal-upload-{{ $item->id }}" name="item-upload-{{ $item->id }}"
            class="w-screen h-screen max-w-none max-h-none m-0 p-0 rounded-none">
            <div class="p-6">
                <flux:heading size="lg" class="mb-6">{{ $item->scope_of_work }}</flux:heading>

                <div class="grid grid-cols-4 gap-4 mb-6">
                    <!-- BEFORE column -->
                    <div class="flex flex-col gap-2">
                        <span class="text-center font-semibold">Before</span>
                        <div class="relative w-full h-64 border border-gray-300 rounded-lg overflow-hidden mt-2">
                            @if ($beforeImage)
                                <img src="{{ $beforeImage->temporaryUrl() }}"
                                    class="absolute inset-0 w-full h-full object-cover" alt="Before preview" />
                            @endif
                        </div>
                        <flux:input type="file" wire:model="beforeImage" accept="image/jpeg, image/png" />
                    </div>

                    <!-- DURING column -->
                    <div class="flex flex-col gap-2">
                        <span class="text-center font-semibold">During</span>
                        <div class="relative w-full h-64 border border-gray-300 rounded-lg overflow-hidden mt-2">
                            @if ($duringImage)
                                <img src="{{ $duringImage->temporaryUrl() }}"
                                    class="absolute inset-0 w-full h-full object-cover" alt="During preview" />
                            @endif
                        </div>
                        <flux:input type="file" wire:model="duringImage" accept="image/jpeg, image/png" />
                    </div>

                    <!-- AFTER column -->
                    <div class="flex flex-col gap-2">
                        <span class="text-center font-semibold">After</span>
                        <div class="relative w-full h-64 border border-gray-300 rounded-lg overflow-hidden mt-2">
                            @if ($afterImage)
                                <img src="{{ $afterImage->temporaryUrl() }}"
                                    class="absolute inset-0 w-full h-full object-cover" alt="After preview" />
                            @endif
                        </div>
                        <flux:input type="file" wire:model="afterImage" accept="image/jpeg, image/png" />
                    </div>

                    <!-- Station Limit/Grid -->
                    <div class="flex flex-col">
                        <span class="text-center font-semibold">Station Limit/Grid</span>
                        <flux:input />
                    </div>
                </div>
            </div>
        </flux:modal>
    @endforeach

    <div class="mt-4">
        {{ $itemLists->links() }}
    </div>
</div>
