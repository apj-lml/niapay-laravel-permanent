<div>
  <div wire:loading.delay.short>
    <div id="spinner" class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
        <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
            <span class="sr-only">Loading... It may take a while!</span>
        </div>
        <p class="p-0 m-0 ps-3">Generating payslips... It may take a while!</p>
    </div>
  </div>
    <div class="row wow fadeInUp" data-wow-delay="0.1s">

    <div class="col-md-6">
        <div class="form-floating mb-3">
          <input type="date" class="form-control @error('payrollDateFrom') is-invalid @enderror" id="dateFromPayroll" placeholder="start date" wire:model="payrollDateFrom" max="2099-12-31">
          <label for="dateFromPayroll">Date From</label>
          @error('payrollDateFrom')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
          @enderror
        </div>
      </div>
      <div class="col-md-6">
        <div class="form-floating mb-3 wow fadeInUp" data-wow-delay="0.5s">
          <input type="date" class="form-control @error('payrollDateTo') is-invalid @enderror" id="dateToPayroll" placeholder="end date" wire:model="payrollDateTo" max="2099-12-31">
          <label for="dateToPayroll">Date To</label>
          @error('payrollDateTo')
          <span class="invalid-feedback" role="alert">
              <strong>{{ $message }}</strong>
          </span>
        @enderror
        </div>
      </div>
    </div>
    <div class="col-md-12">
      <div class="form-floating mb-3 wow fadeInUp">
          <select class="form-select @error('isLessFifteen') is-invalid @enderror" id="isLessFifteen" aria-label="isLessFifteen" wire:model="isLessFifteen">
              {{-- <option value="all" >All</option> --}}
              <option value="full_month" selected>1 month</option>
              <option value="less_fifteen_first_half">Less than 15 (First Half)</option>
              <option value="less_fifteen_second_half">Less than 15 (Second Half)</option>
          </select>
          <label for="floatingSelect">Select Period</label>
          @error('isLessFifteen')
              <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
              </span>
          @enderror
      </div>
  </div>
    <div class="col-md-12">
      <div class="mb-3 wow fadeInUp" data-wow-delay="0.5s">
        {{-- <h4 class="mb-1">Casual</h4> --}}
        <div class="alert alert-warning text-start" role="alert">
          <h4 class="alert-heading"><i class="bi bi-info-circle"></i> Read me</h4>
          <hr>
          Reprocessing will override the previously generated payslips on the specific Payroll Period you have provided above.
        </div>
        <div class="d-flex align-items-center">
            <div class="ps-3">
                {{-- <button class="btn btn-sm btn-primary" wire:click="processPayroll('Casual')"><i class="fas fa-file-invoice"></i> Process</button> --}}
            </div>
        </div>

      </div>

      {{-- @if($loadingState) --}}
        {{-- <div class="col-sm-12" wire:poll.10s="updateLoadingProgress">
          <p class="p-0 m-0 font-monospace" style="font-size: 9px;">{{ $loadingTxt }}</p>
          <div class="progress">
            <div class="progress-bar progress-bar-striped progress-bar-animated" 
              role="progressbar" 
              aria-valuenow="{{ $loadingProgress }}" 
              aria-valuemin="0" 
              aria-valuemax="{{ $loadingProgressMax }}" 
              style="width: {{ $loadingProgress }}%">
              {{ $loadingProgress }}%
            </div>
          </div>
        </div> --}}
        {{-- @endif --}}
     

    </div>
    <div class="text-center mt-3">
      <button class="btn btn-primary" wire:click="generatePayslip()">Generate Payslips</button>
    </div>
</div>

</div>
@push('scripts')
<script type="text/javascript">

    // window.addEventListener('openProcessPayrollJobOrderTab', event => {
    //   window.open("{{ route('process-payroll-jo') }}", '_blank');
    // });

    document.addEventListener("DOMContentLoaded", function(event) {
      const currentDate = dayjs();
      const fifteenthDay = dayjs().date(15);
      // console.log(date.format('YYYY-MM-DD'));
      setDates(currentDate, fifteenthDay)
      
    });
function setDates(currentDate, fifteenthDay){
  if (currentDate.isBefore(fifteenthDay, 'date')) {
        console.log('Current date is less than the 15th day of the month.');
        document.getElementById('dateFromPayroll').value = dayjs().date(16).subtract(1, 'month').format('YYYY-MM-DD')
        document.getElementById('dateToPayroll').value = dayjs().subtract(1, 'month').endOf('month').format('YYYY-MM-DD')
        
        // document.getElementById('payrollFrequency').value = 2;
        
      } else {
        console.log('Current date is greater than or equal to the 15th day of the month.');
        document.getElementById('dateFromPayroll').value = dayjs().date(1).format('YYYY-MM-DD')
        document.getElementById('dateToPayroll').value = dayjs().date(15).format('YYYY-MM-DD')

        // document.getElementById('payrollFrequency').value = 1;
      }

      @this.set('payrollDateFrom', document.getElementById('dateFromPayroll').value);
      @this.set('payrollDateTo', document.getElementById('dateToPayroll').value);
      // @this.set('payrollFrequency', document.getElementById('payrollFrequency').value);
    
}
    
</script>
@endpush