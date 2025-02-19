<div>
    <div class="row mb-3">
        <div class="col-sm-12">
            <div class="position-relative w-100">
                <input class="form-control border-1 rounded-pill w-100 ps-4 pe-5" type="text" placeholder="Search..." wire:model="searchVal" style="height: 48px;">
                <button type="button" class="btn shadow-none position-absolute top-0 end-0 mt-1 me-2"><i class="bi bi-search"></i></button>
            </div>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-striped table-hover table-bordered">
            <thead>
              <tr>
                {{-- <th scope="col">ID</th> --}}
                <th scope="col" style="width:auto">Status</th>
                <th scope="col">Name</th>
                {{-- <th scope="col">Section</th>
                <th scope="col">Unit</th> --}}
                {{-- <th scope="col">Position</th> --}}
                <th scope="col">Employment Status</th>
                <th scope="col">SG/JG</th>
                {{-- <th scope="col">Step</th> --}}
                <th scope="col">Daily Rate</th>
                {{-- <th scope="col">Monthly Rate</th> --}}
                <th scope="col">Controls</th>
                {{-- <th scope="col">Role</th> --}}
              </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                <tr>
                    {{-- <td scope="row">{{ $user->id }}</td> --}}
                    {{-- <td scope="row">{{ $user->is_active ? 'Active' : 'Inactive' }}</td> --}}
                    <td class="align-middle" style="width:auto">
                        <div class="form-switch">
                            <input class="form-check-input m-auto" type="checkbox" wire:click="updateIsActive({{ $user->id }})" {{ $user->include_to_payroll ? 'checked' : '' }}>
                        </div>
                    </td>
                    <td scope="row">
                  
                      {{-- <a href="#">{{ $user->name }}</a> --}}
                      <a href="#" data-bs-toggle="modal" data-bs-target="#employeeProfileModal" wire:click="showEmployeeProfile({{ $user->id }})">
                        {{ $user->full_name }}
                      </a>
                    </td>
                    {{-- <td scope="row">{{ $user->section }}</td>
                    <td scope="row">{{ $user->unit }}</td> --}}
                    {{-- <td scope="row">{{ $user->position }}</td> --}}
                    <td scope="row">{{ $user->employment_status }}</td>
                    <td scope="row">{{ $user->sg_jg }}</td>
                    {{-- <td scope="row">{{ $user->step }}</td> --}}
                    <td scope="row">{{ number_format((float)$user->daily_rate, 2) }}</td>
                    {{-- <td scope="row">{{ number_format((float)$user->monthly_rate, 2) }}</td> --}}
                    <td scope="row">
                      <div class="d-flex flex-row justify-content-evenly">
                        <button class="btn btn-sm btn-outline-primary m-0" data-bs-toggle="modal" data-bs-target="#employeeProfileModal" wire:click="showEmployeeProfile({{ $user->id }})">
                          <i class="bi bi-eye-fill"></i> Profile
                        </button>
                        <a href="{{ url('payslip', ['userId'=>$user->id]) }}" class="btn btn-sm btn-outline-primary m-0" wire:click="showPayslipModal({{ $user->id }})">
                          <i class="bi bi-wallet"></i> Payslip
                        </a>
                        {{-- <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#showPayslipModal" wire:click="showPayslipModal({{ $user->id }})">
                          <i class="bi bi-wallet"></i> Payslip
                        </button> --}}
                        {{-- <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#showIndexModal" wire:click="showIndexModal({{ $user->id }})">
                          <i class="bi bi-card-list"></i> Index
                        </button> --}}
                      </div>
   
                    </td>
                    {{-- <td scope="row">{{ $user->role }}</td> --}}
    
                  </tr>
                @endforeach
    
            </tbody>
          </table>
    </div>

      {{ $users->links() }}


</div>
