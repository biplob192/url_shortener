<?php

namespace App\Livewire\Admin\User;

use App\Models\User;
use Livewire\Component;
use App\Traits\WithSorting;
use App\Traits\WithCachedRows;
use App\Livewire\BaseComponent;
use App\Traits\WithBulkActions;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;
use App\Traits\WithPerPagePagination;
use Illuminate\Http\Request;

class ManageUser extends BaseComponent
{
    use WithPerPagePagination;
    use WithCachedRows;
    use WithSorting;
    use WithBulkActions;

    protected $listeners = ['deleteConfirm' => 'userDelete', 'deleteCancel' => 'userDeleteCancel'];

    public $userIdBeingRemoved = null;

    public $user_id;

    public $name;

    public $email;

    public $password;

    public $role;

    public $active = true;
    public $ic = null;

    protected $userRepository;

    public $filter = [
        'name'     => null,
        'email'    => null
    ];


    public function render()
    {
        $data['users'] = $this->rows;
        $data['roles'] = Role::query()->orderBy('name','ASC')->get();

        return view('livewire.admin.user.manage-user', $data);
    }


    public function getRowsQueryProperty()
    {
        $query = User::query()
            ->with('roles')
            ->when($this->filter['name'], fn ($q, $name)  => $q->where('name', 'like', "%{$name}%"))
            ->when($this->filter['email'], fn ($q, $email)  => $q->where('email', 'like', "%{$email}%"));

        return $this->applySorting($query);
    }


    public function getRowsProperty()
    {
        // return $this->applyPagination($this->rowsQuery);
        return $this->cache(function () {
            return $this->applyPagination($this->rowsQuery);
        });
    }


    public function openNewUserModal()
    {
        $this->resetErrorBag();
        $this->reset();
        // $this->dispatchBrowserEvent('openNewUserModal');
        $this->dispatch('openNewUserModal');
    }


    public function openEditUserModal($user_id)
    {
        $this->resetErrorBag();
        $this->resetValidation();
        $this->user_id = $user_id;

        $user           = User::query()->findOrFail($user_id);

        $this->name     = $user->name;
        $this->email    = $user->email;
        $this->role     = $user->roles->isNotEmpty() ? $user->roles[0]->name : null;
        // $this->active   = $user->is_active;
        $this->active   = $user->is_active ? true : false;
        // dump($user, $this->active);


        $this->dispatch('openNewUserModal');
    }


    public function submit(Request $request)
    {
        // dd('OK', $request->all());

        $rules = [
            'name'       => 'required',
            'role'       => 'required',

            'email'      => [
                'required',
                'email',
                Rule::unique('users', 'email')->ignore($this->user_id),
            ],
        ];

        // New user
        if (! $this->user_id) {
            $rules['password'] = 'required';
        }

        $messages = [
            'name.required'         => 'First Name required',
            'email.required'        => 'Email required',
            'password.required'     => 'Password required',
            'role.required'         => 'Role required',
        ];

        $this->validate($rules, $messages);

        if (! $this->user_id) {
            $this->save();
        } else {
            $this->update();
        }

        $this->reset();
        $this->hideModal();

        // $this->dispatch('notify', [
        //     'type' => 'success',
        //     'title' => 'Active',
        //     'message' => 'User ' . ($this->user_id ? 'updated' : 'created') . ' successfully'
        // ]);
    }


    public function save()
    {
        $data['name']       = $this->name;
        $data['email']      = $this->email;
        $data['password']   = bcrypt($this->password);
        $data['is_active']  = $this->active;

        $user = User::create($data);
        $user->assignRole($this->role);

        $this->dispatch('notify', ['type' => 'success', 'title' => 'Active', 'message' => 'User created successfully']);
    }


    public function update()
    {
        try {
            $user = User::find($this->user_id);

            $user->name         = $this->name;
            $user->is_active    = $this->active;
            // $user->password     = bcrypt($this->password);

            $user->save();
            $user->syncRoles($this->role);

            $this->dispatch('notify', ['type' => 'success', 'title' => 'Active', 'message' => 'User updated successfully']);
        } catch (\Exception $ex) {
            $this->dispatch('notify', ['type' => 'error', 'title' => 'Active', 'message' => 'Unable to update']);
        }
    }


    public function UserconfirmDelete($contact_id)
    {
        // dd('Delete clicked from Controller!!');

        $this->userIdBeingRemoved = $contact_id;
        // $this->dispatchBrowserEvent('show-delete-notification');
        $this->dispatch('show-delete-notification');
    }


    public function userDeleteCancel()
    {
        // dd('userDeleteCancel from Controller!!');
        $this->userIdBeingRemoved = null;
    }


    public function userDelete()
    {
        // dd('userDelete from Controller!!');

        if ($this->userIdBeingRemoved != null) {
            $user  = User::findorFail($this->userIdBeingRemoved);
            $user->delete();
            // $this->dispatchBrowserEvent('deleted', ['message' => 'User deleted successfully']);
        }

        $this->dispatch('deleted', ['message' => 'User deleted successfully']);
        // $this->dispatch('notify', ['type' => 'success', 'title' => 'Success', 'message' => 'User deleted successfully']);
        // $this->alertMessage('User deleted successfully');
        return redirect()->back();
    }


    public function search()
    {
        $this->hideOffCanvas();
        $this->resetPage();

        return $this->rows;
    }


    public function resetSearch()
    {
        $this->reset('filter');
        $this->hideOffCanvas();
    }
}
