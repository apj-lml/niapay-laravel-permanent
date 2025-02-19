<div class="container m-2">
    <ul class="nav nav-tabs" id="myTab" role="tablist" wire:ignore>
        @php
            $characters = [' ', '(', ')', '[', ']', 1, 2, 3, 4, 5, 6, 7, 8, 9, 0];
            $replacement = null;
        @endphp
        @foreach ($funds as $fund)
            <li class="nav-item" role="presentation">
                <button wire:key="fund-{{ $fund->funding_charges }}"
                    class="nav-link @if ($loop->first) active @endif"
                    id="{{ Str::replace($characters, $replacement, $fund->funding_charges) }}-tab"
                    data-bs-toggle="tab"
                    data-bs-target="#{{ Str::replace($characters, $replacement, $fund->funding_charges) }}"
                    type="button" role="tab"
                    aria-controls="{{ Str::replace(' ', '-', $fund->funding_charges) }}"
                    aria-selected="true">{{ $fund->funding_charges }}</button>
            </li>
        @endforeach
    </ul>
    <div class="tab-content" id="myTabContent" wire:ignore.self>
        @foreach ($funds as $fund)

                <div wire:ignore.self class="tab-pane fade @if ($loop->first) show active @endif "
                    id="{{ Str::replace($characters, $replacement, $fund->funding_charges) }}"
                    role="tabpanel"
                    aria-labelledby="{{ Str::replace($characters, $replacement, $fund->funding_charges) }}-tab">
                    <h1 class="mt-2">{{ $fund->funding_charges }}</h1>
                    <table class="table table-bordered" style="width: 500px;">
                        <thead>
                            <tr class="table-dark">
                                <th class="text-center">Active User's Deduction</th>
                                <th class="text-center">Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($fund->deductions_summary as $deductionTitle => $amount)
                                <tr>
                                    <td>{{ $deductionTitle }}</td>
                                    <td class="text-end">{{ number_format(bcdiv((float) $amount, 1, 2), 2) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2">No deductions found</td>
                                </tr>
                            @endforelse
                        </tbody>
                        <tfoot>
                            <tr>
                                <td class="fw-bolder">TOTAL</td>
                                <td class="text-end fw-bolder">{{ number_format(bcdiv((float) $fund->deductions_summary->sum(), 1, 2), 2) }}</td>
                            </tr>
                        </tfoot>
                    </table>

                </div>

        @endforeach
    </div> 
</div>
