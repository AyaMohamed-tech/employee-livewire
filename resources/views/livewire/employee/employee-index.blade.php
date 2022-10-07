<div>
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Employees</h1>
    </div>
    <div class="row">
        <div class="card  mx-auto">
            <div>
                @if (session()->has('employee-message'))
                    <div class="alert alert-success">
                        {{ session('employee-message') }}
                    </div>
                @endif
            </div>
            <div class="card-header">
                <div class="row">
                    <div class="col">
                        <form method="GET">
                            <div class="form-row align-items-center">
                                <div class="col">
                                    <input type="search" wire:model="search" class="form-control mb-2"
                                        id="inlineFormInput" placeholder="Jane Doe">
                                </div>
                                <div class="col" wire:loading>
                                    <div class="spinner-border" role="status">
                                        <span class="sr-only">Loading...</span>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div>
                        <!-- Button trigger modal -->
                        <button wire:click="showEmployeeModal()" class="btn btn-primary">
                            New Employee
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <table class="table" wire:loading.remove>
                    <thead>
                        <tr>
                            <th scope="col">#Id</th>
                            <th scope="col">First Name</th>
                            <th scope="col">Last Name</th>
                            <th scope="col">Middle Name</th>
                            <th scope="col">Address</th>
                            <th scope="col">Department</th>
                            <th scope="col">Country</th>
                            <th scope="col">Manage</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($employees as $employee)
                            <tr>
                                <th scope="row">{{ $employee->id }}</th>
                                <td>{{ $employee->first_name }}</td>
                                <td>{{ $employee->last_name }}</td>
                                <td>{{ $employee->middle_name }}</td>
                                <td>{{ $employee->address }}</td>
                                <td>{{ $employee->department->name }}</td>
                                <td>{{ $employee->country->name }}</td>
                                 
                                <td>
                                    <button wire:click="showEditModal({{ $employee->id }})" class="btn btn-success">Edit</button>
                                    <button wire:click="deleteEmployee({{ $employee->id }})" class="btn btn-danger" onclick="confirm('Are you sure?');">Delete</button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <th>No Result</th>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div>
                {{ $employees->links('pagination::bootstrap-4') }}
            </div>
        </div>
        <!-- Modal -->
        <div class="modal fade" id="employeeModal" tabindex="-1" aria-labelledby="employeeModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="employeeModalLabel">Create Employee</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form method="POST" action="">

                            <div class="form-group row">
                                <label for="name"
                                    class="col-md-4 col-form-label text-md-right">{{ __('First Name') }}</label>

                                <div class="col-md-6">
                                    <input id="first_name" type="text"
                                        class="form-control @error('first_name') is-invalid @enderror" wire:model.defer="first_name">

                                    @error('first_name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="last_name"
                                    class="col-md-4 col-form-label text-md-right">{{ __('Last Name') }}</label>

                                <div class="col-md-6">
                                    <input id="last_name" type="text"
                                        class="form-control @error('last_name') is-invalid @enderror" wire:model.defer="last_name">

                                    @error('last_name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="middle_name"
                                    class="col-md-4 col-form-label text-md-right">{{ __('Middle Name') }}</label>

                                <div class="col-md-6">
                                    <input id="middle_name" type="text"
                                        class="form-control @error('middle_name') is-invalid @enderror" wire:model.defer="middle_name">

                                    @error('middle_name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div> 

                            <div class="form-group row">
                                <label for="address"
                                    class="col-md-4 col-form-label text-md-right">{{ __('Address') }}</label>

                                <div class="col-md-6">
                                    <input id="address" type="text"
                                        class="form-control @error('middle_name') is-invalid @enderror" wire:model.defer="address">

                                    @error('address')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div> 

                            <div class="form-group row">
                                <label for="country_id"
                                    class="col-md-4 col-form-label text-md-right">{{ __('Department') }}</label>
                                   
                                 <div class="col-md-6">
 
                                    <select wire:model.defer="department_id" class="custom-select">
                                        <option selected>Choose</option>  
                                     @foreach (App\Models\Department::all() as $department)
                                        <option value="{{ $department->id }}">{{ $department->name }}</option>   
                                     @endforeach
                                    </select>
                                   
                                    @error('department_id')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="country_id"
                                    class="col-md-4 col-form-label text-md-right">{{ __('Country') }}</label>
                                   
                                 <div class="col-md-6">
 
                                    <select wire:model.defer="country_id" class="custom-select">
                                        <option selected>Choose</option>  
                                     @foreach (App\Models\Country::all() as $country)
                                        <option value="{{ $country->id }}">{{ $country->name }}</option>   
                                     @endforeach
                                    </select>
                                   
                                    @error('country_id')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="form-group row">
                                <label for="country_id"
                                    class="col-md-4 col-form-label text-md-right">{{ __('State') }}</label>
                                   
                                 <div class="col-md-6">
 
                                    <select wire:model.defer="state_id" class="custom-select">
                                        <option selected>Choose</option>  
                                     @foreach (App\Models\State::all() as $state)
                                        <option value="{{ $state->id }}">{{ $state->name }}</option>   
                                     @endforeach
                                    </select>
                                   
                                    @error('state_id')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="country_id"
                                    class="col-md-4 col-form-label text-md-right">{{ __('City') }}</label>
                                   
                                 <div class="col-md-6">
 
                                    <select wire:model.defer="city_id" class="custom-select">
                                        <option selected>Choose</option>  
                                     @foreach (App\Models\City::all() as $city)
                                        <option value="{{ $city->id }}">{{ $city->name }}</option>   
                                     @endforeach
                                    </select>
                                   
                                    @error('city_id')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="zip_code"
                                    class="col-md-4 col-form-label text-md-right">{{ __('Zip Code') }}</label>

                                <div class="col-md-6">
                                    <input id="zip_code" type="text"
                                        class="form-control @error('zip_code') is-invalid @enderror" wire:model.defer="zip_code">

                                    @error('zip_code')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="zip_code"
                                    class="col-md-4 col-form-label text-md-right">{{ __('Birthdate') }}</label>

                                <div class="col-md-6">
                                    <input id="birthdate" type="date"
                                        class="form-control @error('birthdate') is-invalid @enderror" wire:model.defer="birthdate">

                                    @error('birthdate')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="zip_code"
                                    class="col-md-4 col-form-label text-md-right">{{ __('Date Hire') }}</label>

                                <div class="col-md-6">
                                    <input id="date_hire" type="date"
                                        class="form-control @error('date_hire') is-invalid @enderror" wire:model.defer="date_hire">

                                    @error('date_hire')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" wire:click="closeModal()">Close</button>
                        @if($editMode)
                        <button type="button" class="btn btn-primary" wire:click="updateEmployee()">Update Employee</button>
                        @else 
                        <button type="button" class="btn btn-primary" wire:click="storeEmployee()">Store Employee</button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>




