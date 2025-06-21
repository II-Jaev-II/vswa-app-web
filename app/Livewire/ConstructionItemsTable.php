<?php

namespace App\Livewire;

use App\Models\ProgramOfWork;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;

class ConstructionItemsTable extends Component
{
    use WithFileUploads;

    public $search = '';

    public $beforeImage, $duringImage, $afterImage;

    public function showUploadModal() {}

    public function render()
    {
        $subprojectId = Auth::user()->subproject_assigned;

        $itemLists = ProgramOfWork::query()
            ->when(
                $this->search,
                fn($q) => $q->where(function ($q) {
                    $q->where('item_no', 'like', "%{$this->search}%")
                        ->orWhere('scope_of_work', 'like', "%{$this->search}%")
                        ->orWhere('quantity', 'like', "%{$this->search}%")
                        ->orWhere('unit', 'like', "%{$this->search}%");
                }),
            )
            ->where('subproject_id', $subprojectId)
            ->orderBy('scope_of_work')
            ->paginate(10);

        return view('livewire.construction-items-table', [
            'itemLists' => $itemLists,
        ]);
    }
}
