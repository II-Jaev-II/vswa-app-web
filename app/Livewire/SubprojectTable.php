<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Subproject;
use Flux\Flux;
use Livewire\Attributes\On;

class SubprojectTable extends Component
{
    public $projectName, $projectLocation, $projectId, $contractor, $projectType, $subprojectId;

    use WithPagination;

    public $search = '';

    #[On('subprojectAdded')]
    public function handleSubprojectAdded()
    {
        $this->resetPage();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function edit($id)
    {
        $sub = Subproject::findOrFail($id);

        $this->subprojectId = $id;
        $this->projectName = $sub->project_name;
        $this->projectLocation = $sub->project_location;
        $this->projectId = $sub->project_id;
        $this->contractor = $sub->contractor;
        $this->projectType = $sub->project_type;
    }

    public function update()
    {
        $this->validate([
            'projectName' => 'required',
            'projectLocation' => 'required',
            'projectId' => 'required',
            'contractor' => 'required',
            'projectType' => 'required',
        ]);

        $sub = Subproject::findOrFail($this->subprojectId);

        $sub->update([
            'project_name' => $this->projectName,
            'project_location' => $this->projectLocation,
            'project_id' => $this->projectId,
            'contractor' => $this->contractor,
            'project_type' => $this->projectType,
        ]);

        Flux::modal('edit-subproject-' . $this->subprojectId)->close();
        session()->flash('message', 'Project information updated successfully.');
        $this->dispatch('subprojectUpdated')->to('subproject-table');
    }

    public function delete($id)
    {
        $this->subprojectId = $id;
    }

    public function destroy($id)
    {
        Subproject::findOrFail($id)->delete();
        Flux::modal('delete-subproject-' . $id)->close();
        session()->flash('message', 'Subproject deleted successfully.');
        $this->dispatch('subprojectDeleted')->to('subproject-table');
    }

    public function render()
    {
        $subprojects = Subproject::query()
            ->when(
                $this->search,
                fn($q) => $q->where(function ($q) {
                    $q->where('project_name', 'like', "%{$this->search}%")
                        ->orWhere('project_location', 'like', "%{$this->search}%")
                        ->orWhere('project_id', 'like', "%{$this->search}%")
                        ->orWhere('contractor', 'like', "%{$this->search}%")
                        ->orWhere('project_type', 'like', "%{$this->search}%");
                }),
            )
            ->orderBy('project_name')
            ->paginate(10);

        return view('livewire.subproject-table', [
            'subprojects' => $subprojects,
        ]);
    }
}
