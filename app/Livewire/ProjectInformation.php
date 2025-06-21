<?php

namespace App\Livewire;

use App\Models\Subproject;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ProjectInformation extends Component
{
    public $projectInfo;

    public function mount()
    {
        $this->projectInfo = Subproject::find(Auth::user()->subproject_assigned);
    }

    public function render()
    {
        return view('livewire.project-information');
    }
}
