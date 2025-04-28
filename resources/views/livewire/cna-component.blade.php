@push('styles')
    <style>
        input[type="text"]:focus {
            z-index: 1080;
        }

        /* Chrome, Safari, Edge, Opera */
        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        /* Firefox */
        input[type=number] {
            -moz-appearance: textfield;
        }

        .no-days {
            font-size: 12px;
            text-align: center;
            width: 90%;
            border: 1px solid #89878754;
        }

        .my-sticky-top {
            position: sticky;
            top: 65px;
            background-color: #fff;
            z-index: 195;
            /* Ensure background color to differentiate from table rows */
            /* z-index: 9999; Ensure it stays above the table rows */
        }

        .sample {
            background-image: url("{{ URL::asset('/img/bagong_pilipinas.png') }}");
            background-size: 75px 75px;
            background-repeat: no-repeat;
            background-position: left top;
        }

        table {
            border: 1px black;
        }

       table td {
            vertical-align: middle;
            text-align: right;
        }

        table td. {
            vertical-align: middle;
            text-align: right;
        }

        table tfoot {
            position: sticky;
        }

        table tfoot {
            inset-block-end: 0;
            /* "bottom" */
            color: #0a090a;
            background-color: #febdcd;
        }

        .table-hover tbody tr:hover td, .table-hover tbody tr:hover th {
            background-color: #fadee8;
        }


    </style>
@endpush

<div>
    <font size="2.5" face="Cambria">
        @php
            $hasNegativeNetpay = 0;
        @endphp
        <ul class="nav nav-tabs" id="myTab" role="tablist" wire:ignore>
            @php
                $characters = [' ', '(', ')', '[', ']', 1, 2, 3, 4, 5, 6, 7, 8, 9, 0];
                $replacement = null;
            @endphp
            @foreach ($payrollFunds as $payrollFund)
                <li class="nav-item" role="presentation">
                    <button wire:key="fund-{{ $payrollFund->fund_description }}"
                        class="nav-link @if ($loop->first) active @endif"
                        id="{{ Str::replace($characters, $replacement, $payrollFund->fund_description) }}-tab"
                        data-bs-toggle="tab"
                        data-bs-target="#{{ Str::replace($characters, $replacement, $payrollFund->fund_description) }}"
                        type="button" role="tab"
                        aria-controls="{{ Str::replace(' ', '-', $payrollFund->fund_description) }}"
                        aria-selected="true">{{ $payrollFund->fund_description }}
                    </button>
                </li>
            @endforeach
        </ul>

        <div class="tab-content" id="myTabContent" wire:ignore.self>
            @foreach ($payrollFunds as $payrollFund)
                {{-- @if (isset($payrollFund->users)) --}}
                    <div wire:ignore.self class="tab-pane fade @if ($loop->first) show active @endif "
                        id="{{ Str::replace($characters, $replacement, $payrollFund->fund_description) }}"
                        role="tabpanel"
                        aria-labelledby="{{ Str::replace($characters, $replacement, $payrollFund->fund_description) }}-tab">

                        <h1 class="mt-2">{{ $payrollFund->fund_description }} | {{ $year }}</h1>

                        @php
                            $counter = 0;
                            $total_amount_due = 0;
                        @endphp

                        @foreach ($payrollFund->sections as $office => $payrollUserSections)
                            <div class="border border-light shadow p-3 mb-3">
                                <div class="row">
                                    <div class="col-sm-12 row">
                                        <div class="col-sm-6">
                                            <h6 class="">{{ $office }}</h6>
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <table
                                                class="table table-striped table-hover table-bordered position-relative display"
                                                id="payrolltable{{ $office }}">
                                                <thead class="my-sticky-top">
                                                    <tr>
                                                        <th scope="col" rowspan="2" style="width:2%"
                                                            class="text-center align-middle">No.
                                                        </th>
                                                        <th scope="col" rowspan="2" style="width:15%"
                                                            class="text-center align-middle ">Name
                                                        </th>
                                                        <th scope="col" rowspan="2"
                                                            class="text-center align-middle" style="width:10%">Position Title / JG
                                                        </th>
                                                        <th scope="col" rowspan="2"
                                                            class="text-center align-middle" style="width:5%">Collective Negotiation Agreement (CNA) Incentive for CY {{ $year }}
                                                        </th>
                                                        <th scope="col" rowspan="2"
                                                            class="text-center align-middle" style="width:5%">No. of Months Rendered
                                                        </th>
                                                        <th scope="col"
                                                            class="text-center align-middle" style="width:5%">Amount Due
                                                        </th>
                                                        <th scope="col"
                                                            class="text-center align-middle" style="width:5%">Remarks
                                                        </th>
            
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @php
                                                        $payrollUsers = $payrollFund->users()
                                                            ->with('agencyUnit.agencySection') // Eager load the related agencyUnit and agencySection
                                                            ->where('employment_status', 'PERMANENT') // Filter by employment_status
                                                            ->orWhere('employment_status', 'COTERMINOUS') // Filter by employment_status
                                                            ->where('is_active', 1) // Filter by active users
                                                            ->get()
                                                            ->sortBy('full_name'); // Sort by full name


                                                    @endphp

                            {{-- @foreach ($payrollUserSections as $payrollUserSection) --}}
                                                @foreach ($payrollUsers as $payrollUser)
                                                    @if ($payrollFund->id == $payrollUser->fund_id)
                                                        @if ($office == $payrollUser->agencyUnit()->with('agencySection')->first()->toArray()['agency_section']['office'])

                                                            @php
                                                                $counter = $counter + 1;
                                                                $total_amount_due += $payrollUser->cnas->where('year', $year)->first()->amount_due;
                                                        
                                                            @endphp

                                                            @php
                                                                if (
                                                                    number_format(
                                                                        $payrollUser->basic_pay -
                                                                            $payrollUser->total_user_deduction,
                                                                        2,
                                                                    ) < 0
                                                                ) {
                                                                    $hasNegativeNetpay++;
                                                                }
                                                            @endphp
                                                            <tr
                                                                @if (number_format($payrollUser->basic_pay - $payrollUser->total_user_deduction, 2) < 0) style="background-color: rgba(245, 94, 39, 0.172)" @endif>
                                                                <td scope="row" style="width: 50px;"
                                                                    class="text-center align-middle">
                                                                    <div class="form-switch w-0 p-0 pt-1">
                                                                        <span
                                                                            style="">{{ $counter }}</span>
                                                                        <br>
                                                                        <input
                                                                            class="form-check-input myCheckInput{{ $payrollUser->id }} m-auto"
                                                                            type="checkbox"
                                                                            wire:change="updateIsActive([{{ $payrollUser->id }}])"
                                                                            {{ $payrollUser->include_to_payroll ? 'checked' : '' }}>

                                                                    </div>
                                                                </td>
                                                                <td scope="row" class="text-start">
                                                                    <strong>
                                                                        <a href="#" data-bs-toggle="modal"
                                                                            data-bs-target="#employeeProfileModal"
                                                                            wire:click="showEmployeeProfile({{ $payrollUser->id }})">
                                                                            {{ $payrollUser->full_name }}
                                                                        </a>
                                                                    </strong>
                                                                </td>
                                                                <td scope="row" class="text-start">
                                                                    {{ $payrollUser->position }} /
                                                                    {{ $payrollUser->sg_jg }}</td>
                                                                <td scope="row"
                                                                    class="text-center align-middle p-0">
                                                                    {{ number_format((float) $cna, 2) }}
                                                                </td>

                                                                <td scope="row"
                                                                    class="text-center align-middle p-0">
                                                                    @livewire('cna-no-of-mos-rendered-input-component', [
                                                                        'payrollUser'=>$payrollUser, 'year' => $year,
                                                                        ], key('cna-no-of-mos-rendered' . $payrollUser->id))
                                                                </td>
                                                                <td scope="row"
                                                                    class="text-center align-middle p-0">
                                                                    {{ number_format((float)$payrollUser->cnas->where('year', $year)->first()->amount_due, 2) }}
                                                                </td>

                                                                <td
                                                                    class="text-center align-middle p-0">
                                                                    @livewire('cna-remarks-input-component', [
                                                                        'payrollUser'=>$payrollUser, 
                                                                        'year' => $year
                                                                        ], key('cnaRemarks' . $payrollUser->id))
                                                                </td>
                                                            </tr>
                                                            @endif
                                                        @endif
                                                @endforeach

                                                </tbody>
                                                <tfoot>
                                                    <tr>
                                                        <td colspan=5 style="text-align: right;"><b>TOTAL</b></td>
                                                        <td colspan=2 class="text-start"><b>{{ number_format(bcdiv((float) $total_amount_due, 1, 2), 2) }}</b></td>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>


                                    </div>
                                </div>

                                @php
                                    $counter = 0;
                                    $total_amount_due = 0;

                                @endphp
                                @endforeach




                        {{-- @endforeach --}}


                    </div>
                    {{-- @endif --}}
                @endforeach
        </div>
    </font>


        <!-- Modal -->
        <div class="modal fade" id="printPreviewJoPayrollModal" tabindex="-1"
        aria-labelledby="printPreviewJoPayrollModalLabel" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-fullscreen">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="printPreviewJoPayrollModalLabel">Print Preview</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div wire:loading>
                        <div class="bg-white">
                            <div
                                class="h-100 w-100 position-absolute translate-middle top-50 start-50 bg-white d-flex align-items-center justify-content-center">
                                <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;"
                                    role="status">
                                    <span class="sr-only">Loading...</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="h-100" id="pdfPreview">
                        <h1 class="text-muted">No data to be shown</h1>

                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    {{-- <button type="button" class="btn btn-primary" wire:click="createPDF()">Print</button> --}}
                </div>
            </div>
        </div>
    </div>


</div>
@push('scripts')

<script type="text/javascript">

    window.addEventListener('previewCnaPdf', event => {
        let pdfWindow = document.getElementById('pdfPreview');
        pdfWindow.innerHTML =
            `<iframe width='100%' height='100%' src='data:application/pdf;base64, ${encodeURI(event.detail.mypdf)} ${event.detail.toolbar}'></iframe>`;
    });

    document.addEventListener("keydown", function(event) {
        if (event.ctrlKey && event.keyCode == 80) {
            event.stopPropagation();
            event.preventDefault();

            var printPayrollJoModal = new bootstrap.Modal(document.getElementById('printPreviewJoPayrollModal'));
                printPayrollJoModal.show();

            window.livewire.emit('createPdf')
        }
    });
</script>
@endpush