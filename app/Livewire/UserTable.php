<?php

namespace App\Livewire;

use App\Models\Subproject;
use App\Models\User;
use Flux\Flux;
use Livewire\Component;
use Livewire\WithPagination;

class UserTable extends Component
{

    use WithPagination;

    public $name, $role, $subproject_assigned, $contractor_name, $userId, $search = '', $options = [];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function mount()
    {
        $this->options = Subproject::pluck('project_name', 'id')->toArray();
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);

        $this->userId = $id;
        $this->name = $user->name;
        $this->role = $user->role;
        $this->subproject_assigned = $user->subproject_assigned;
        $this->contractor_name = $user->contractor_name;
    }

    public function update()
    {
        $this->validate([
            'name' => 'required',
            'role' => 'required',
        ]);

        $user = User::findOrFail($this->userId);

        $user->update([
            'name' => $this->name,
            'role' => $this->role,
            'subproject_assigned' => $this->subproject_assigned,
            'contractor_name' => $this->contractor_name,
        ]);

        Flux::modal('edit-user-' . $this->userId)->close();
        session()->flash('message', 'User information updated successfully.');
        $this->dispatch('userUpdated')->to('user-table');
    }

    public function delete($id)
    {
        $this->userId = $id;
    }

    public function destroy($id)
    {
        User::findOrFail($id)->delete();
        Flux::modal('delete-user-' . $id)->close();
        session()->flash('message', 'User deleted successfully.');
        $this->dispatch('userDeleted')->to('user-table');
    }

    public function render()
    {
        $users = User::query()
            ->where('role', '!=', 'ADMIN')
            ->when(
                $this->search,
                fn($q) => $q->where(function ($q) {
                    $q->where('name', 'like', "%{$this->search}%")
                        ->orWhere('role', 'like', "%{$this->search}%")
                        ->orWhere('subproject_assigned', 'like', "%{$this->search}%")
                        ->orWhere('contractor_name', 'like', "%{$this->search}%");
                }),
            )
            ->orderBy('name')
            ->paginate(10);

        return view('livewire.user-table', [
            'users' => $users,
        ]);
    }
}
