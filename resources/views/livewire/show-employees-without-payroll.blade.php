<div>
    <div class="mx-auto text-center" style="max-width: 600px;">
        {{-- <div class="d-inline-block border rounded-pill text-primary px-4 mb-3">Testimonial</div> --}}
        <h3 class="">PAYROLL PERIOD:</h3>
        <div class="wow fadeInUp" data-wow-delay="0.1s">
            <form wire:submit.prevent="submitSearchForm" class="row">
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
                <div class="col-sm-12">
                    <button type="submit" class="btn btn-primary"><i class="bi bi-search"></i> Search</button>
                </div>
            </form>
    </div>
    <hr>
    <div class="container w-50 vh-100 overflow-auto">
        <ol class="list-group list-group-flush list-group-numbered">
            @forelse ($listOfEmployeesWoPayroll as $employee)
                <li class="list-group-item">{{ $employee->full_name }}</li>
            @empty
                <h2 class="text-muted m-auto">NO DATA TO BE SHOWN</h2>
            @endforelse
        </ol>
    </div>

</div>
