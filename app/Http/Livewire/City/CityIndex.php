<?php

namespace App\Http\Livewire\City;

use App\Models\City;
use Livewire\Component;
use Livewire\WithPagination;

class CityIndex extends Component
{
    use WithPagination;

    public $search = '';
    public $state_id;
    public $name;
    public $cityId;
    public $editMode = false;

    protected $rules = [
        'state_id' => 'required',
        'name' => 'required',
    ];
    
    public function storeCity(){
        $this->validate();

        City::create([
            'state_id' => $this->state_id,
            'name'         => $this->name
        ]);

        $this->reset();
        $this->dispatchBrowserEvent('modal',['modalId'=>'#cityModal','modalAction'=>'hide']);
        session()->flash('city-message','City created Successfully');
    }

    public function showEditModal($id){

        $this->reset();
        $this->editMode = true;
        $this->cityId = $id;
        $this->loadCity();
        $this->dispatchBrowserEvent('modal',['modalId'=>'#cityModal','modalAction'=>'show']);
        
    } 

    public function loadCity(){
        $city = City::find($this->cityId);

        $this->state_id = $city->state_id;
        $this->name = $city->name;
   }

   public function updateCity(){

    $validated = $this->validate([
        'state_id' => 'required',
        'name'        => 'required',
    ]);

    $city = City::find($this->cityId);
    $city->update($validated);

    $this->reset();
    $this->dispatchBrowserEvent('modal',['modalId'=>'#cityModal','modalAction'=>'hide']);
    session()->flash('city-message','City updated Successfully');

  }
   public function deleteCity($id){
    $city = City::find($id);
    $city->delete();
    session()->flash('city-message','City deleted successfully');

}

    public function closeModal(){

        $this->dispatchBrowserEvent('modal',['modalId'=>'#cityModal','modalAction'=>'hide']);
        $this->reset();
    }
    public function showCityModal(){
        $this->reset();
        $this->dispatchBrowserEvent('modal',['modalId'=>'#cityModal','modalAction'=>'show']);
    }
    public function render()
    {
        $cities = City::paginate(5);

        if(strlen($this->search>2)){
            $cities = City::where('name','like',"%{$this->search}%")->paginate(5);
        }

        return view('livewire.city.city-index',[
            'cities'  => $cities,
        ])->layout('layouts.main');
    }
}
