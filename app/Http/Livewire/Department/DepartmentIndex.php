<?php

namespace App\Http\Livewire\Department;

use App\Models\Department;
use Livewire\Component;
use Livewire\WithPagination;

class DepartmentIndex extends Component
{
    use WithPagination;

    public $search = '';
    public $departmentId;
    public $editMode = false;
    public $name;

    protected $rules = [
          'name' => 'required',
    ];

    public function showDepartmentModal(){
        $this->reset();
        $this->dispatchBrowserEvent('modal',['modalId'=>'#departmentModal','modalAction'=>'show']);
    }

    public function closeModal(){
        $this->reset();
        $this->dispatchBrowserEvent('modal',['modalId'=>'#departmentModal','modalAction'=>'hide']);
    }

    public function storeDepartment(){
        $this->validate();

        Department::create([
            'name' => $this->name,
        ]);

        $this->reset();
        $this->dispatchBrowserEvent('modal',['modalId'=>'#departmentModal','modalAction'=>'hide']);
        session()->flash('department-message','Department created successfully');

    }

    public function showEditModal($id){
        $this->reset();
        $this->editMode = true;
        $this->departmentId = $id;
        $this->loadDepartment();
        $this->dispatchBrowserEvent('modal',['modalId'=>'#departmentModal','modalAction'=>'show']);
    }

    public function loadDepartment(){

        $department = Department::find($this->departmentId);
        
        $this->name = $department->name;
    }

    public function updateDepartment(){
        $validated = $this->validate([
            'name' => 'required',
        ]);

        $department = Department::find($this->departmentId);
        $department->update($validated);
        $this->reset();
        $this->dispatchBrowserEvent('modal',['modalId'=>'#departmentModal','modalAction'=>'hide']);
        session()->flash('department-message','Department updated successfully');
        
    }

    public function deleteDepartment($id){
        $department = Department::find($id);
        $department->delete();
        session()->flash('department-message','Department deleted successfully');
    }



    public function render()
    {
        $departments = Department::paginate(5);

        if(strlen($this->search > 2)){
            $departments = Department::where('name','like',"%{$this->search}%")->paginate(5); 
        }
        return view('livewire.department.department-index',[
            'departments' => $departments,
        ])->layout('layouts.main');
    }
}
