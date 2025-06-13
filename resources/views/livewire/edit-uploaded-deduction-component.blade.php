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

@php $counter = 1; @endphp

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
                aria-selected="false">Records To Be Not Updated</button>
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
            <h1 class="mt-2">Records to be Updated (GSIS)</h1>
              <button class="btn btn-primary mb-2" wire:click='saveRecords'><i class="bi bi-floppy2"></i> Save Deductions to Database (System)</button>
              <div class="col-sm-12 overflow-auto" style="height: 100vh;">
              {{-- <button wire:click='ddMe'>DD</button> --}}
                <table class="table table-bordered border-primary table-hover table-striped">
                    <thead class="sticky-top bg-white">
                        <tr>
                            <th>No.</th>
                            <th>ID</th>
                            <th>BPNO</th>
                            <th>LastName</th>
                            <th>FirstName</th>
                            <th>MI</th>
                            <th>APPELLATION</th>
                            <th>CRN</th>
                            <th>PS</th>
                            <th>SALARY_LOAN</th>
                            <th>MPL</th>
                            <th>MPL_LITE</th>
                            <th>PL_REG</th>
                            <th>CPL</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($listOfFinalToBeUpdated as $finalToBeUpdated)
                            <tr>
                                <td>{{ $counter }}</td>
                                <td>{{ $finalToBeUpdated['id'] }}</td>
                                <td>{{ $finalToBeUpdated['excel_data']['BPNO'] }}</td>
                                <td>{{ $finalToBeUpdated['excel_data']['LastName'] }}</td>
                                <td>{{ $finalToBeUpdated['excel_data']['FirstName'] }}</td>
                                <td>{{ $finalToBeUpdated['excel_data']['MI'] }}</td>
                                <td>{{ $finalToBeUpdated['excel_data']['APPELLATION'] }}</td>
                                <td>{{ $finalToBeUpdated['excel_data']['CRN'] }}</td>
                                <td>
                                  <div class="form-check">
                                    @php
                                      $isCheckedPS = collect($listTobeSaved)->firstWhere('id', $finalToBeUpdated['id'])['PS'] ?? 0;
                                      $isCheckedSALARY_LOAN = collect($listTobeSaved)->firstWhere('id', $finalToBeUpdated['id'])['SALARY_LOAN'] ?? 0;
                                      $isCheckedMPL = collect($listTobeSaved)->firstWhere('id', $finalToBeUpdated['id'])['MPL'] ?? 0;
                                      $isCheckedMPL_LITE = collect($listTobeSaved)->firstWhere('id', $finalToBeUpdated['id'])['MPL_LITE'] ?? 0;
                                      $isCheckedPLREG = collect($listTobeSaved)->firstWhere('id', $finalToBeUpdated['id'])['PLREG'] ?? 0;
                                      $isCheckedCPL = collect($listTobeSaved)->firstWhere('id', $finalToBeUpdated['id'])['CPL'] ?? 0;
                                      
                                        $validatedPsVal = false;
                                        $validateSalaryLoanVal = false;
                                        $validateMplVal = false;
                                        $validateMplLiteVal = false;
                                        $validatePlregVal = false;
                                        $validateCplVal = false;
                                      // Validate PS value if it exists in the list to be saved
                                        
                                        if($isCheckedPS > 0) {
                                            $validatedPsVal = $this->validateValueWithChanges($finalToBeUpdated['id'], 'PS', $finalToBeUpdated['excel_data']['PS']);
                                        }
                                        if($isCheckedSALARY_LOAN > 0) {
                                            $validateSalaryLoanVal = $this->validateValueWithChanges($finalToBeUpdated['id'], 'SALARY_LOAN', $finalToBeUpdated['excel_data']['SALARY_LOAN']);
                                        }
                                        if($isCheckedMPL > 0) {
                                            $validateMplVal = $this->validateValueWithChanges($finalToBeUpdated['id'], 'MPL', $finalToBeUpdated['excel_data']['MPL']);
                                        }
                                        if($isCheckedMPL_LITE > 0) {
                                            $validateMplLiteVal = $this->validateValueWithChanges($finalToBeUpdated['id'], 'MPL_LITE', $finalToBeUpdated['excel_data']['MPL_LITE']);
                                        }
                                        if($isCheckedPLREG > 0) {
                                            $validatePlregVal = $this->validateValueWithChanges($finalToBeUpdated['id'], 'PLREG', $finalToBeUpdated['excel_data']['PLREG']);
                                        }
                                        if($isCheckedCPL > 0) {
                                            $validateCplVal = $this->validateValueWithChanges($finalToBeUpdated['id'], 'CPL', $finalToBeUpdated['excel_data']['CPL']);
                                        }


                                    @endphp
                                    <input
                                        type="checkbox"
                                        class="form-check-input"
                                        id="PSCheck{{ $counter }}"
                                        wire:change="updateListToBeSaved({{ $finalToBeUpdated['id'] }}, 'PS', $event.target.checked ? {{ $finalToBeUpdated['excel_data']['PS'] }} : 0)"
                                        @if ($isCheckedPS > 0) checked @endif>
                                    <label class="form-check-label @if($validatedPsVal) text-danger @endif" for="PSCheck{{ $counter }}">
                                        {{ number_format($finalToBeUpdated['excel_data']['PS'], 2) }}
                                    </label>
                                  </div>
                                </td>
                                <td>
                                    <input type="checkbox" 
                                        class="form-check-input"
                                        id="SALARY_LOANCheck{{ $counter }}"
                                        wire:change="updateListToBeSaved({{ $finalToBeUpdated['id'] }}, 'SALARY_LOAN', $event.target.checked ? {{ $finalToBeUpdated['excel_data']['SALARY_LOAN'] }} : 0)"
                                        @if ($isCheckedSALARY_LOAN > 0) checked @endif>
                                    <label class="form-check-label @if($validateSalaryLoanVal) text-danger @endif" for="SALARY_LOANCheck{{ $counter }}">
                                      {{ number_format($finalToBeUpdated['excel_data']['SALARY_LOAN'], 2) }}
                                    </label>
                                </td>
                                <td>
                                  <input type="checkbox" 
                                        class="form-check-input"
                                        id="MPLCheck{{ $counter }}"
                                        wire:change="updateListToBeSaved({{ $finalToBeUpdated['id'] }}, 'MPL', $event.target.checked ? {{ $finalToBeUpdated['excel_data']['MPL'] }} : 0)"
                                        @if ($isCheckedMPL > 0) checked @endif>
                                    <label class="form-check-label @if($validateMplVal) text-danger @endif" for="MPLCheck{{ $counter }}">
                                      {{ number_format($finalToBeUpdated['excel_data']['MPL'], 2) }}
                                    </label>
                                </td>
                                <td>
                                  <input type="checkbox" 
                                        class="form-check-input"
                                        id="MPL_LITECheck{{ $counter }}"
                                        wire:change="updateListToBeSaved({{ $finalToBeUpdated['id'] }}, 'MPL_LITE', $event.target.checked ? {{ $finalToBeUpdated['excel_data']['MPL_LITE'] }} : 0)"
                                        @if ($isCheckedMPL_LITE > 0) checked @endif>
                                  <label class="form-check-label @if($validateMplLiteVal) text-danger @endif" for="MPL_LITECheck{{ $counter }}">
                                    {{ number_format($finalToBeUpdated['excel_data']['MPL_LITE'], 2) }}
                                  </label>

                                </td>
                                <td>
                                  <input type="checkbox"
                                        class="form-check-input"
                                        id="PLREGCheck{{ $counter }}"
                                        wire:change="updateListToBeSaved({{ $finalToBeUpdated['id'] }}, 'PLREG', $event.target.checked ? {{ $finalToBeUpdated['excel_data']['PLREG'] }} : 0)"
                                        @if ($isCheckedPLREG > 0) checked @endif>
                                  <label class="form-check-label @if($validatePlregVal) text-danger @endif" for="PLREGCheck{{ $counter }}">
                                    {{ number_format($finalToBeUpdated['excel_data']['PLREG'], 2) }}
                                  </label>
                                </td>
                                <td>
                                  <input type="checkbox"
                                        class="form-check-input"
                                        id="CPLCheck{{ $counter }}"
                                        wire:change="updateListToBeSaved({{ $finalToBeUpdated['id'] }}, 'CPL', $event.target.checked ? {{ $finalToBeUpdated['excel_data']['CPL'] }} : 0)"
                                        @if ($isCheckedCPL > 0) checked @endif>
                                  <label class="form-check-label @if($validateCplVal) text-danger @endif" for="CPLCheck{{ $counter }}">
                                    {{ number_format($finalToBeUpdated['excel_data']['CPL'], 2) }}
                                  </label>
                                  
                                </td>
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
            <h1 class="mt-2 mb-0">Records to be Not Updated</h1> 
            <h5>(From Payroll System)</h5>
            <div class="col-sm-12">
                <table class="table table-bordered border-primary table-hover table-striped w-50">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>ID</th>
                            <th>BPNO</th>
                            <th>LastName</th>
                            <th>FirstName</th>
                            <th>MI</th>
                            <th>APPELLATION</th>
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
                            <th>LastName</th>
                            <th>FirstName</th>
                            <th>MI</th>
                            <th>APPELLATION</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($listOfCannotFindInDatabase as $cannotFindInDatabase)
                            <tr>
                                <td>{{ $counter }}</td>
                                <td>{{ $cannotFindInDatabase['LastName'] }}</td>
                                <td>{{ $cannotFindInDatabase['FirstName'] }}</td>
                                <td>{{ $cannotFindInDatabase['MI'] }}</td>
                                <td>{{ $cannotFindInDatabase['APPELLATION'] }}</td>
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
      //     @this.call('updateListToBeSaved', id, 'PS', updateValue);
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