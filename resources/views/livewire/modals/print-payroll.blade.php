<div>
{{-- <div class="modal fade" id="printPayrollModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" wire:ignore.self> --}}
    {{-- <div class="modal-dialog modal-lg"> --}}
      {{-- <div class="modal-content"> --}}
        {{-- <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Process Payroll</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div> --}}
        {{-- <div class="modal-body"> --}}

            <div class="container">
                <div class="mx-auto text-center" style="max-width: 600px;">
                    {{-- <div class="d-inline-block border rounded-pill text-primary px-4 mb-3">Testimonial</div> --}}
                    <h3 class="">Process Permanent & Coterminous Payroll for the Period:</h3>
                    <div class="row wow fadeInUp" data-wow-delay="0.1s">
                      <div class="col-md-6">
                        <input type="hidden" wire:model="payrollFrequency" id="payrollFrequency">

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
                </div>
            </div>
   
        <!-- Testimonial End -->
        {{-- </div> --}}
        <div class="modal-footer justify-content-center">
          {{-- <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button> --}}
          <button type="button" class="btn btn-primary" wire:click="processPayroll('Permanent')"><i class="fas fa-file-invoice"></i> Process</button>
          @livewire('add-attendance', ['startDate' => $payrollDateFrom, 'endDate' => $payrollDateTo, 'isLessFifteen' => $isLessFifteen])



          {{-- <button type="button" class="btn btn-primary">Save changes</button> --}}
        </div>
      {{-- </div> --}}
    {{-- </div> --}}
  {{-- </div> --}}

    </div>

  @push('scripts')
  <script type="text/javascript">


  document.addEventListener("DOMContentLoaded", function(event) {
      const currentDate = dayjs();
      const firstDayOfMonth = dayjs().startOf('month');
      const lastDayOfMonth = dayjs().endOf('month');

      setDates(currentDate, firstDayOfMonth, lastDayOfMonth);
  });

  function setDates(currentDate, firstDayOfMonth, lastDayOfMonth) {
      // If current date is before the last day of the month
      if (currentDate.isBefore(lastDayOfMonth, 'date')) {
          console.log('Current date is within the month.');
          document.getElementById('dateFromPayroll').value = firstDayOfMonth.format('YYYY-MM-DD');
          document.getElementById('dateToPayroll').value = lastDayOfMonth.format('YYYY-MM-DD');
          
          document.getElementById('payrollFrequency').value = 1;  // Adjust this based on your logic
      } else {
          console.log('Current date is the last day of the month.');
          document.getElementById('dateFromPayroll').value = firstDayOfMonth.format('YYYY-MM-DD');
          document.getElementById('dateToPayroll').value = lastDayOfMonth.format('YYYY-MM-DD');
          
          document.getElementById('payrollFrequency').value = 1;  // Adjust as necessary
      }

      // Set the Livewire component properties
      @this.set('payrollDateFrom', document.getElementById('dateFromPayroll').value);
      @this.set('payrollDateTo', document.getElementById('dateToPayroll').value);
      @this.set('payrollFrequency', document.getElementById('payrollFrequency').value);
      @this.set('isLessFifteen', document.getElementById('isLessFifteen').value);
  }


  // document.addEventListener("DOMContentLoaded", function(event) {
  //     const currentDate = dayjs();
  //     const fifteenthDay = dayjs().date(15);
  //     setDates(currentDate, fifteenthDay)
  //   });

  // function setDates(currentDate, fifteenthDay){
  //   if (currentDate.isBefore(fifteenthDay, 'date')) {
  //         console.log('Current date is less than the 15th day of the month.');
  //         document.getElementById('dateFromPayroll').value = dayjs().date(16).subtract(1, 'month').format('YYYY-MM-DD')
  //         document.getElementById('dateToPayroll').value = dayjs().subtract(1, 'month').endOf('month').format('YYYY-MM-DD')
          
  //         document.getElementById('payrollFrequency').value = 2;
          
  //       } else {
  //         console.log('Current date is greater than or equal to the 15th day of the month.');
  //         document.getElementById('dateFromPayroll').value = dayjs().date(1).format('YYYY-MM-DD')
  //         document.getElementById('dateToPayroll').value = dayjs().date(15).format('YYYY-MM-DD')

  //         document.getElementById('payrollFrequency').value = 1;
  //       }

  //       @this.set('payrollDateFrom', document.getElementById('dateFromPayroll').value);
  //       @this.set('payrollDateTo', document.getElementById('dateToPayroll').value);
  //       @this.set('payrollFrequency', document.getElementById('payrollFrequency').value);
  //       @this.set('isLessFifteen', document.getElementById('isLessFifteen').value);
      
  // }
      
  </script>
  @endpush