<div x-data>

    <flux:button @click="$refs.realFileInput.click()">Upload POW</flux:button>

    <input type="file" x-ref="realFileInput" wire:model="excelFile" accept=".xls,.xlsx" style="display: none;" />

    <div wire:loading wire:target="excelFile" class="mt-2 text-blue-600">
        Reading Excel file…
    </div>

    @error('excelFile')
        <div class="text-red-600 mt-1">{{ $message }}</div>
    @enderror

    <div class="relative overflow-x-auto mt-4">
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
                @forelse($items as $item)
                    <tr wire:key="item-{{ $item->id }}"
                        class="bg-white border-b dark:bg-zinc-900 dark:border-gray-700">
                        <td class="px-6 py-4">{{ $item['item_no'] }}</td>
                        <td class="px-6 py-4">{{ $item['scope_of_work'] }}</td>
                        <td class="px-6 py-4">{{ $item['quantity'] }}</td>
                        <td class="px-6 py-4">{{ $item['unit'] }}</td>
                        <td class="px-6 py-4">
                            <flux:button.group>
                                <flux:modal.trigger wire:key="trigger-delete-{{ $item->id }}"
                                    name="delete-item-{{ $item->id }}">
                                    <flux:button icon="trash" variant="primary" color="red" size="sm">
                                        Delete
                                    </flux:button>
                                </flux:modal.trigger>
                            </flux:button.group>
                        </td>
                    </tr>
                    <flux:modal wire:key="modal-delete-{{ $item->id }}" name="delete-item-{{ $item->id }}"
                        class="min-w-[22rem]">
                        <div class="space-y-6">
                            <div>
                                <flux:heading size="lg">Delete Item?</flux:heading>
                                <flux:text class="mt-2">
                                    <p>You're about to delete this Item.</p>
                                    <p>This action cannot be reversed.</p>
                                </flux:text>
                            </div>
                            <div class="flex gap-2">
                                <flux:spacer />
                                <flux:modal.close>
                                    <flux:button variant="ghost">Cancel</flux:button>
                                </flux:modal.close>
                                <flux:button wire:click="destroy({{ $item->id }})" type="submit" variant="danger">
                                    Delete
                                    Item</flux:button>
                            </div>
                        </div>
                    </flux:modal>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-gray-300">
                            No program of works found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
