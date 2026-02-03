<div>
  <div class="row mb-3">
      <div class="col-sm-12">
          <div class="position-relative w-100">
              <input class="form-control border-1 rounded-pill w-100 ps-4 pe-5" type="text" placeholder="Search..." wire:model="searchVal" style="height: 48px;">
              <button type="button" class="btn shadow-none position-absolute top-0 end-0 mt-1 me-2"><i class="bi bi-search"></i></button>
          </div>
      </div>
  </div>

  <ul class="nav nav-tabs">
    <li class="nav-item">
      <button class="nav-link {{ $tab === 'active' ? 'active' : '' }}" 
              wire:click="setTab('active')">ACTIVE</button>
    </li>
    <li class="nav-item">
      <button class="nav-link {{ $tab === 'inactive' ? 'active' : '' }}" 
              wire:click="setTab('inactive')">INACTIVE</button>
    </li>
  </ul>
  
  <div class="mt-3">
    <div class="table-responsive">
      <table class="table table-striped table-bordered">
        <thead>
          <tr>
            <th>Status</th>
            <th>Name</th>
            <th>Employment Status</th>
            <th>SG/JG</th>
            <th>Monthly Rate</th>
            <th>Controls</th>
          </tr>
        </thead>
        <tbody>
          @forelse ($users as $user)
            <tr>
              <td class="text-center">
                <div class="form-switch">
                  <input class="form-check-input" type="checkbox" 
                         wire:click="updateIsActive({{ $user->id }})"
                         {{ $user->include_to_payroll ? 'checked' : '' }}>
                </div>
              </td>
              <td>
                <a href="#" data-bs-toggle="modal" data-bs-target="#employeeProfileModal"
                   wire:click="showEmployeeProfile({{ $user->id }})">
                  {{ $user->full_name }}
                </a>
              </td>
              <td>{{ $user->employment_status }}</td>
              <td>{{ $user->sg_jg }}</td>
              <td>{{ number_format($user->monthly_rate, 2) }}</td>
              <td>
                <a href="{{ url('payslip', ['userId' => $user->id]) }}" class="btn btn-sm btn-outline-primary">
                  <i class="bi bi-wallet"></i> Payslip
                </a>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="6" class="text-center">No employees found.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
  
      {{ $users->links() }} 
      <div>
        @if ($tab === 'active')
            <span class="fw-semibold text-success">Total Active Employees: {{ $activeCount }}</span>
        @else
            <span class="fw-semibold text-danger">Total Inactive Employees: {{ $inactiveCount }}</span>
        @endif
    </div>
    </div>
  </div>
  



</div>
