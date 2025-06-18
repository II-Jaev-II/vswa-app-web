<?php

namespace App\Livewire;

use App\Models\Subproject;
use Flux\Flux;
use Livewire\Component;

class Subprojects extends Component
{
    public $projectName, $projectLocation, $projectId, $contractor, $projectType;

    public function submit()
    {
        $this->validate([
            "projectName" => "required",
            "projectLocation" => "required",
            "projectId" => "required",
            "contractor" => "required",
            "projectType" => "required",
        ]);

        Subproject::create([
            "project_name" => $this->projectName,
            "project_location" => $this->projectLocation,
            "project_id" => $this->projectId,
            "contractor" => $this->contractor,
            "project_type" => $this->projectType
        ]);

        $this->resetForm();

        Flux::modal("create-subproject")->close();
        session()->flash('message', 'Subproject added successfully.');
        $this->dispatch('subprojectAdded')
            ->to('subproject-table');
    }

    public function resetForm()
    {
        $this->projectName = "";
        $this->projectLocation = "";
        $this->projectId = "";
        $this->contractor = "";
        $this->projectType = "";
    }

    public function render()
    {
        return view('livewire.subprojects');
    }
}
