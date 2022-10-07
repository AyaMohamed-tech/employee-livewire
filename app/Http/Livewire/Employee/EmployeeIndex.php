<?php

namespace App\Http\Livewire\Employee;

use App\Models\Employee;
use Livewire\Component;
use Livewire\WithPagination;

class EmployeeIndex extends Component
{
    use WithPagination;

    public $search = '';
    public $first_name;
    public $last_name;
    public $middle_name;
    public $address;
    public $department_id;
    public $country_id;
    public $state_id;
    public $city_id;
    public $zip_code;
    public $birthdate;
    public $date_hire;
    public $employeeId;
    public $editMode = false;

    protected $rules = [
        'first_name'    => 'required',
        'last_name'     => 'required',
        'middle_name'   => 'required',
        'address'       => 'required',
        'department_id' => 'required',
        'country_id'    => 'required',
        'state_id'      => 'required',
        'city_id'       => 'required',
        'zip_code'      => 'required',
        'birthdate'     => 'required',
        'date_hire'     => 'required',
    ];
    
    public function storeEmployee(){
        $this->validate();

        Employee::create([
            'first_name'    => $this->first_name,
            'last_name'     => $this->last_name,
            'middle_name'   => $this->middle_name,
            'address'       => $this->address,
            'department_id' => $this->department_id,
            'country_id'    => $this->country_id,
            'state_id'      => $this->state_id,
            'city_id'       => $this->city_id,
            'zip_code'      => $this->zip_code,
            'birthdate'     => $this->birthdate,
            'date_hire'     => $this->date_hire,
        ]);

        $this->reset();
        $this->dispatchBrowserEvent('modal',['modalId'=>'#employeeModal','modalAction'=>'hide']);
        session()->flash('employee-message','Employee created Successfully');
    }

    public function showEditModal($id){

        $this->reset();
        $this->editMode = true;
        $this->employeeId = $id;
        $this->loadEmployee();
        $this->dispatchBrowserEvent('modal',['modalId'=>'#employeeModal','modalAction'=>'show']);
        
    } 

    public function loadEmployee(){
        $employee = Employee::find($this->employeeId);

         $this->first_name      = $employee->first_name;
         $this->last_name       = $employee->last_name;
         $this->middle_name     = $employee->middle_name;
         $this->address         = $employee->address;
         $this->department_id   = $employee->department_id;
         $this->country_id      = $employee->country_id;
         $this->state_id        = $employee->state_id;
         $this->city_id         = $employee->city_id;
         $this->zip_code        = $employee->zip_code;
         $this->birthdate       = $employee->birthdate;
         $this->date_hire       = $employee->date_hire;
   }

   public function updateEmployee(){

    $validated = $this->validate([
        'first_name'    => 'required',
        'last_name'     => 'required',
        'middle_name'   => 'required',
        'address'       => 'required',
        'department_id' => 'required',
        'country_id'    => 'required',
        'state_id'      => 'required',
        'city_id'       => 'required',
        'zip_code'      => 'required',
        'birthdate'     => 'required',
        'date_hire'     => 'required',
    ]);

    $employee = Employee::find($this->employeeId);
    $employee->update($validated);

    $this->reset();
    $this->dispatchBrowserEvent('modal',['modalId'=>'#employeeModal','modalAction'=>'hide']);
    session()->flash('employee-message','Employee updated Successfully');

  }
   public function deleteEmployee($id){
    $employee = Employee::find($id);
    $employee->delete();
    session()->flash('employee-message','Employee deleted successfully');

}

    public function closeModal(){

        $this->dispatchBrowserEvent('modal',['modalId'=>'#employeeModal','modalAction'=>'hide']);
        $this->reset();
    }
    public function showEmployeeModal(){
        $this->reset();
        $this->dispatchBrowserEvent('modal',['modalId'=>'#employeeModal','modalAction'=>'show']);
    }
    public function render()
    {
        $employees = Employee::paginate(5);

        if(strlen($this->search>2)){
            $employees = Employee::where('first_name','like',"%{$this->search}%")->paginate(5);
        }
        return view('livewire.employee.employee-index',[
            'employees' => $employees
        ])->layout('layouts.main');
    }
}
