<div>
      <div class="row wow fadeInUp" data-wow-delay="0.1s">
  
        <div class="col-md-4">
          <div class="form-floating">
            <select class="form-select @error('employeeProfile.agency_unit_id') is-invalid @enderror"  aria-label="unit" wire:model="npiadDescription">
                @foreach ($listOfDeductions as $deduction)
                    <option value="{{ $deduction->description }}">[{{ $deduction->deduction_group }}] - {{ $deduction->description }}</option>
                @endforeach
            </select>
            <label for="floatingSelect">Deduction</label>
        </div>
        </div>
        <div class="col-md-4">
            <div class="form-floating mb-3">
              <input type="date" class="form-control @error('payrollDateFrom') is-invalid @enderror" id="dateFromWithoutDed" placeholder="start date" wire:model="payrollDateFrom" max="2099-12-31">
              <label for="dateFromWithoutDed">Date From</label>
              @error('payrollDateFrom')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
              @enderror
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-floating mb-3 wow fadeInUp" data-wow-delay="0.5s">
              <input type="date" class="form-control @error('payrollDateTo') is-invalid @enderror" id="dateToWithoutDed" placeholder="end date" wire:model="payrollDateTo" max="2099-12-31">
              <label for="dateToWithoutDed">Date To</label>
              @error('payrollDateTo')
              <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
              </span>
            @enderror
            </div>
          </div>
      </div>
      <div class="col-md-12">
        <ul class="row">
          @foreach ($listOfUsers as $listOfUser)
            <li class="col-sm-4">
                {{ $listOfUser->name }}
                <ul class="mb-3">
                  @foreach ($listOfUser->newPayrollIndexAllDed as $empDed)
                      <li>{{ $empDed->npiad_description }} - {{ $empDed->npiad_amount }}</li>
                  @endforeach
                </ul>
            </li>
          @endforeach
        </ul>

        {{ $listOfUsers->links() }}
       
  
      </div>
      <div class="text-center mt-3">
        <button class="btn btn-primary" wire:click="searchEmployeesWithoutDeduction()">Search Employees</button>
      </div>
  </div>
  

  @push('scripts')
  <script type="text/javascript">
  
      document.addEventListener("DOMContentLoaded", function(event) {
        const currentDate = dayjs();
        const fifteenthDay = dayjs().date(15);
        setDatesForWithoutDed(currentDate, fifteenthDay)
        
      });
  function setDatesForWithoutDed(currentDate, fifteenthDay){
    if (currentDate.isBefore(fifteenthDay, 'date')) {
          console.log('Current date is less than the 15th day of the month.');
          document.getElementById('dateFromWithoutDed').value = dayjs().date(16).subtract(1, 'month').format('YYYY-MM-DD')
          document.getElementById('dateToWithoutDed').value = dayjs().subtract(1, 'month').endOf('month').format('YYYY-MM-DD')
          
          
        } else {
          console.log('Current date is greater than or equal to the 15th day of the month.');
          document.getElementById('dateFromWithoutDed').value = dayjs().date(1).format('YYYY-MM-DD')
          document.getElementById('dateToWithoutDed').value = dayjs().date(15).format('YYYY-MM-DD')
        }
  
        @this.set('payrollDateFrom', document.getElementById('dateFromWithoutDed').value);
        @this.set('payrollDateTo', document.getElementById('dateToWithoutDed').value);
      
  }
      
  </script>
  @endpush
