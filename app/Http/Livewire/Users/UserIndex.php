<?php

namespace App\Http\Livewire\Users;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\WithPagination;
use Livewire\Component;

class UserIndex extends Component
{
    use WithPagination;

    public $search = '';
    public $username,$firstName,$lastName,$email,$password;
    public $userId;
    public $editMode = false;
    protected $rules = [
        'username' => 'required',
        'firstName' => 'required',
        'lastName' => 'required',
        'password' => 'required',
        'email' => 'required|email',
    ];

    public function storeUser(){

        $this->validate();

       User::create([
        'username' => $this->username,
        'first_name' => $this->firstName,
        'last_name' => $this->lastName,
        'email' => $this->email,
        'password' => Hash::make($this->password),
       ]);

       $this->reset();

       $this->dispatchBrowserEvent('modal',['modalId'=>'#userModal','modalAction'=>'hide']);
       session()->flash('user-message','User created successfully');
    }

    public function showEditModal($id){
          $this->reset();
          $this->editMode = true;
          //find user
          $this->userId = $id;
          //load user
          $this->loadUser();
          //show modal
       $this->dispatchBrowserEvent('modal',['modalId'=>'#userModal','modalAction'=>'show']);
         
    }

    public function showUserModal(){
        $this->reset();
       $this->dispatchBrowserEvent('modal',['modalId'=>'#userModal','modalAction'=>'show']);
    }

    public function loadUser(){
        $user = User::find($this->userId);

        $this->username = $user->username;
        $this->firstName = $user->first_name;
        $this->lastName = $user->last_name;
        $this->email = $user->email;
        $this->password = $user->password;
    }

    public function updateUser(){
        $validated = $this->validate([
            'username' => 'required',
            'firstName' => 'required',
            'lastName' => 'required',
            'password' => 'required',
            'email' => 'required|email',
        ]);
        $user = User::find($this->userId);
        $user->update($validated);
        $this->reset();
        $this->dispatchBrowserEvent('modal',['modalId'=>'#userModal','modalAction'=>'hide']);
        session()->flash('user-message','User updated successfully');
    }

    public function closeModal(){
        $this->dispatchBrowserEvent('modal',['modalId'=>'#userModal','modalAction'=>'hide']);
        $this->reset();
    }

    public function deleteUser($id){

         $user = User::find($id);
         $user->delete();

         session()->flash('user-message','User deleted successfully');
    }

    public function render()
    {
        $users = User::paginate(5);

        if(strlen($this->search)>2){
            $users = User::where('username','like',"%{$this->search}%")->paginate(5);
        }
        return view('livewire.users.user-index',[
            'users' => $users
        ])
                ->layout('layouts.main');
    }
}
