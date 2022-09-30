<?php

namespace App\Http\Livewire\Country;

use App\Models\Country;
use Livewire\WithPagination;
use Livewire\Component;

class CountryIndex extends Component
{
    use WithPagination;

    public $search = '';
    public $country_code;
    public $name;
    public $countryId;
    public $editMode = false;

    protected $rules = [
        'country_code' => 'required',
        'name' => 'required',
    ];
    
    public function storeCountry(){
        $this->validate();

        Country::create([
            'country_code' => $this->country_code,
            'name'         => $this->name
        ]);

        $this->reset();
       $this->dispatchBrowserEvent('modal',['modalId'=>'#countryModal','modalAction'=>'hide']);
        session()->flash('country-message','Country created Successfully');
    }

    public function showEditModal($id){

        $this->reset();
        $this->editMode = true;
        $this->countryId = $id;
        $this->loadCountry();
        $this->dispatchBrowserEvent('modal',['modalId'=>'#countryModal','modalAction'=>'show']);
        
    } 

    public function loadCountry(){
        $country = Country::find($this->countryId);

        $this->country_code = $country->country_code;
        $this->name = $country->name;
   }

   public function updateCountry(){

    $validated = $this->validate([
        'country_code' => 'required',
        'name'        => 'required',
    ]);

    $country = Country::find($this->countryId);
    $country->update($validated);

    $this->reset();
    $this->dispatchBrowserEvent('modal',['modalId'=>'#countryModal','modalAction'=>'hide']);
    session()->flash('country-message','Country updated Successfully');

  }
public function deleteCountry($id){
    $country = Country::find($id);
    $country->delete();
    session()->flash('country-message','Country deleted successfully');

}

    public function closeModal(){

        $this->dispatchBrowserEvent('modal',['modalId'=>'#countryModal','modalAction'=>'hide']);
        $this->reset();
    }
    public function showCountryModal(){
        $this->reset();
        $this->dispatchBrowserEvent('modal',['modalId'=>'#countryModal','modalAction'=>'show']);
    }

    public function render()
    {
        $countries = Country::paginate(5);

        if(strlen($this->search>2)){
            $countries = Country::where('name','like',"%{$this->search}%")->paginate(5);  
        }
        return view('livewire.country.country-index',[
            'countries' => $countries
        ])->layout('layouts.main');
    }
}
