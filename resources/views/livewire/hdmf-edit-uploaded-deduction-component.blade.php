@push('styles')
    <style type="text/css">
        .table-hover tbody tr:hover td {
            background-color: rgba(0, 162, 255, 0.208);
        }

        .spinner-overlay {
            position: relative;
        }

        .spinner-overlay::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(255, 255, 255, 0.8);
            z-index: 999;
        }

        table {
            border-collapse: collapse;
            font-size: 12px;
        }

        th {
            padding: 0.5rem;
            text-align: center;
        }
    </style>
@endpush

@php 
    use Illuminate\Support\Str;
    $counter = 1; 
@endphp

<div class="row">
    <ul class="nav nav-tabs" id="myTab" role="tablist" wire:ignore>
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="listOfToBeUpdated-tab" data-bs-toggle="tab"
                data-bs-target="#listOfToBeUpdated" type="button" role="tab" aria-controls="listOfToBeUpdated"
                aria-selected="true">Records To Be Updated</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="listOfToBeNotUpdated-tab" data-bs-toggle="tab"
                data-bs-target="#listOfToBeNotUpdated" type="button" role="tab" aria-controls="listOfToBeNotUpdated"
                aria-selected="false">Records To Be NOT Updated</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="listOfCannotFindInDatabase-tab" data-bs-toggle="tab"
                data-bs-target="#listOfCannotFindInDatabase" type="button" role="tab"
                aria-controls="listOfCannotFindInDatabase" aria-selected="false">Records Not Found in Database</button>
        </li>
    </ul>

    <div class="tab-content" id="myTabContent">
        {{-- Tab 1 --}}
        <div wire:ignore.self class="tab-pane fade show active" id="listOfToBeUpdated" role="tabpanel"
            aria-labelledby="listOfToBeUpdated-tab">
            <h1 class="mt-2">Records to be Updated (HDMF)</h1>
              <button class="btn btn-primary mb-2" wire:click='saveRecords'><i class="bi bi-floppy2"></i> Save Deductions to Database (System)</button>
              <div class="col-sm-12 overflow-auto" style="height: 100vh;">
              <button wire:click='ddMe'>DD</button>
                <table class="table table-bordered border-primary table-hover table-striped">
                    <thead class="sticky-top bg-white">
                        <tr>
                            <th>No.</th>
                            <th>ID</th>
                            <th>Pag-IBIG ID</th>
                            <th>LastName</th>
                            <th>FirstName</th>
                            <th>MiddleName</th>
                            <th>Name Extn</th>
                            <th style="width:12%">Scheme Description</th>
                            <th>Loan Type</th>
                            <th>Loan Granted</th>
                            <th style="width:8%">Monthly Ammortization</th>
                            <th>Start Term</th>
                            <th>End Term</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($listOfFinalToBeUpdated as $finalToBeUpdated)
                            <tr>
                                <td>{{ $counter }}</td>
                                <td>{{ $finalToBeUpdated['user_id'] }}</td>
                                <td>{{ $finalToBeUpdated['excel_data']['pagibigid'] }}</td>
                                <td>{{ $finalToBeUpdated['excel_data']['lname'] }}</td>
                                <td>{{ $finalToBeUpdated['excel_data']['fname'] }}</td>
                                <td>{{ $finalToBeUpdated['excel_data']['mid'] }}</td>
                                <td>{{ $finalToBeUpdated['excel_data']['name_ext'] }}</td>
                                <td>{{ $finalToBeUpdated['excel_data']['scheme_desc'] }}</td>
                                <td>
                                    @if(Str::contains($finalToBeUpdated['excel_data']['scheme_desc'], '448'))
                                        MPL
                                    @elseif(Str::contains($finalToBeUpdated['excel_data']['scheme_desc'], '469'))
                                        MPL
                                    @elseif(Str::contains($finalToBeUpdated['excel_data']['scheme_desc'], '449'))
                                        Calamity Loan
                                    @else
                                        Unknown Loan Type
                                    @endif
                                </td>
                                <td>{{ number_format($finalToBeUpdated['excel_data']['loan_grante'], 2) }}</td>
                                <td>
                                    <div class="form-check">
                                      @php
                                        $isCheckedAmort = collect($listTobeSaved)->firstWhere('user_id', $finalToBeUpdated['user_id'])['monthly_amortization'] ?? 0;
                                        $deductionType = '';

                                        if(Str::contains($finalToBeUpdated['excel_data']['scheme_desc'], '448') || Str::contains($finalToBeUpdated['excel_data']['scheme_desc'], '469')){
                                            $isCheckedMPL = $isCheckedAmort;
                                            $deductionType = 'HDMF_MPL';
                                        } elseif(Str::contains($finalToBeUpdated['excel_data']['scheme_desc'], '449')) {
                                            $isCheckedCAL = $isCheckedAmort;
                                            $deductionType = 'HDMF_CAL';
                                        }

                                        $validatedAmortVal = false;
                                          
                                        // Validate HDMF_MPL value if it exists in the list to be saved
                                          
                                          if($isCheckedAmort > 0) {
                                              $validatedAmortVal = $this->validateValueWithChanges($finalToBeUpdated['user_id'], $deductionType, $finalToBeUpdated['excel_data']['monthly_amo']);
                                          }

                                      @endphp
                                      <input
                                          type="checkbox"
                                          class="form-check-input"
                                          id="HDMF_MPLCheck{{ $counter }}"
                                          wire:change="updateListToBeSaved({{ $finalToBeUpdated['user_id'] }},
                                                        $event.target.checked ? {{ $finalToBeUpdated['excel_data']['monthly_amo'] }} : 0,
                                                        {{ $finalToBeUpdated['excel_data']['loan_grante'] }},
                                                        '{{ $finalToBeUpdated['excel_data']['start_term'] }}',
                                                        '{{ $finalToBeUpdated['excel_data']['end_term'] }}')"
                                          @if ($isCheckedMPL > 0) checked @endif>
                                      <label class="form-check-label @if($validatedAmortVal) text-danger @endif" for="HDMF_MPLCheck{{ $counter }}">
                                          {{ number_format($finalToBeUpdated['excel_data']['monthly_amo'], 2) }}
                                      </label>
                                    </div>
                                  </td>
                                <td>{{ $finalToBeUpdated['excel_data']['start_term'] }}</td>
                                <td>{{ $finalToBeUpdated['excel_data']['end_term'] }}</td>

   
                            </tr>
                            @php $counter++; @endphp
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Tab 2 --}}
        @php $counter = 1; @endphp
        <div wire:ignore.self class="tab-pane fade" id="listOfToBeNotUpdated" role="tabpanel"
            aria-labelledby="listOfToBeNotUpdated-tab">
            <h1 class="mt-2 mb-0">Records to be NOT Updated</h1> 
            <h5>(From Payroll System)</h5>
            <div class="col-sm-12">
                <table class="table table-bordered border-primary table-hover table-striped w-50">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>ID</th>
                            <th>BPNO</th>
                            <th>Last Name</th>
                            <th>First Name</th>
                            <th>Middle Name</th>
                            <th>Name Extn</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($listOfToBeNotUpdated as $toBeNotUpdated)
                            <tr>
                                <td>{{ $counter }}</td>
                                <td>{{ $toBeNotUpdated['id'] }}</td>
                                <td>{{ $toBeNotUpdated['gsis'] }}</td>
                                <td>{{ $toBeNotUpdated['last_name'] }}</td>
                                <td>{{ $toBeNotUpdated['first_name'] }}</td>
                                <td>{{ $toBeNotUpdated['middle_name'] }}</td>
                                <td>{{ $toBeNotUpdated['name_extn'] }}</td>
                            </tr>
                            @php $counter++; @endphp
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">No data found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Tab 3 --}}
        @php $counter = 1; @endphp
        <div wire:ignore.self class="tab-pane fade" id="listOfCannotFindInDatabase" role="tabpanel"
            aria-labelledby="listOfCannotFindInDatabase-tab">
            <h1 class="mt-2">Records Not Found in System Database</h1>
            <div class="col-sm-12">
                <table class="table table-bordered border-primary table-hover table-striped w-50">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Last Name</th>
                            <th>First Name</th>
                            <th>Middle Name</th>
                            <th>Name Extn</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($listOfCannotFindInDatabase as $cannotFindInDatabase)
                            <tr>
                                <td>{{ $counter }}</td>
                                <td>{{ $cannotFindInDatabase['lname'] }}</td>
                                <td>{{ $cannotFindInDatabase['fname'] }}</td>
                                <td>{{ $cannotFindInDatabase['mid'] }}</td>
                                <td>{{ $cannotFindInDatabase['name_ext'] }}</td>
                            </tr>
                            @php $counter++; @endphp
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@push('scripts')
  <script>
      // function handleCheckboxChange(event, id, value) {
      //     const isChecked = event.target.checked;
      //     const updateValue = isChecked ? value : 0;
      //     @this.call('updateListToBeSaved', id, 'HDMF_MPL', updateValue);
      // }
  </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const tabs = document.querySelectorAll('.nav-link');
            tabs.forEach(tab => {
                tab.addEventListener('click', function() {
                    tabs.forEach(t => t.classList.remove('active'));
                    this.classList.add('active');
                });
            });
        });
    </script>
@endpush