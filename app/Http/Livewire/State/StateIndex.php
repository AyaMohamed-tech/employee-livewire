<?php

namespace App\Http\Livewire\State;

use App\Models\State;
use Livewire\Component;
use Livewire\WithPagination;


class StateIndex extends Component
{
    use WithPagination;

    public $search = '';
    public $country_id;
    public $name;
    public $stateId;
    public $editMode = false;

    protected $rules = [
        'country_id' => 'required',
        'name' => 'required',
    ];
    
    public function storeState(){
        $this->validate();

        State::create([
            'country_id' => $this->country_id,
            'name'         => $this->name
        ]);

        $this->reset();
        $this->dispatchBrowserEvent('modal',['modalId'=>'#stateModal','modalAction'=>'hide']);
        session()->flash('state-message','State created Successfully');
    }

    public function showEditModal($id){

        $this->reset();
        $this->editMode = true;
        $this->stateId = $id;
        $this->loadState();
        $this->dispatchBrowserEvent('modal',['modalId'=>'#stateModal','modalAction'=>'show']);
        
    } 

    public function loadState(){
        $state = State::find($this->stateId);

        $this->country_id = $state->country_id;
        $this->name = $state->name;
   }

   public function updateState(){

    $validated = $this->validate([
        'country_id' => 'required',
        'name'        => 'required',
    ]);

    $state = State::find($this->stateId);
    $state->update($validated);

    $this->reset();
    $this->dispatchBrowserEvent('modal',['modalId'=>'#stateModal','modalAction'=>'hide']);
    session()->flash('state-message','State updated Successfully');

  }
public function deleteState($id){
    $state = State::find($id);
    $state->delete();
    session()->flash('state-message','State deleted successfully');

}

    public function closeModal(){

        $this->dispatchBrowserEvent('modal',['modalId'=>'#stateModal','modalAction'=>'hide']);
        $this->reset();
    }
    public function showStateModal(){
        $this->reset();
        $this->dispatchBrowserEvent('modal',['modalId'=>'#stateModal','modalAction'=>'show']);
    }

    public function render()
    {
        $states = State::paginate(5);

        if(strlen($this->search>2)){
            $states = State::where('name','like',"%{$this->search}%")->paginate(5);  
        }
        return view('livewire.state.state-index',[
            'states' => $states
        ])->layout('layouts.main');
    }
}
