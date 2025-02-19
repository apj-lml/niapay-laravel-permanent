<div>
    <table class="table table-striped">
        <thead>
          <tr>
            <th scope="col">Name</th>
            <th scope="col">Position Title</th>
            <th scope="col">Employment Status</th>
            <th scope="col">Controls</th>
          </tr>
        </thead>
        <tbody>
            @foreach ($adminUsers as $adminUser)
                <tr>
                    <td>{{ $adminUser->full_name }}</td>
                    <td>{{ $adminUser->position }}</td>
                    <td>{{ $adminUser->employment_status }}</td>
                    <td>
                        <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#employeeProfileModal" wire:click="showEmployeeProfile({{ $adminUser->id }})">
                            <i class="bi bi-eye-fill"></i> Profile
                      </button>
                    </td>
                </tr>
            @endforeach
        </tbody>
      </table>
</div>
