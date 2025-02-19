<div>
      <form wire:submit.prevent="addIndividualAttendance" class="needs-validation" novalidate id="addIndividualAttendance">
            <input type="hidden" wire:model="userId" >
            @if($isLessFifteen == 'all' || $isLessFifteen == 'full_month')
                  <input type="number" tabindex="1" class="m-0 p-0 no-days @error('firstHalfDaysRendered') is-invalid @enderror" wire:model.lazy="firstHalfDaysRendered" wire:change="addIndividualAttendance" aria-describedby="" placeholder="first half">
                  <input type="number" tabindex="1" class="m-0 p-0 no-days @error('secondHalfDaysRendered') is-invalid @enderror" wire:model.lazy="secondHalfDaysRendered" wire:change="addIndividualAttendance" aria-describedby="" placeholder="second half">
                  <input type="number" tabindex="1" class="m-0 p-0 no-days d-none @error('daysRendered') is-invalid @enderror" wire:model.lazy="daysRendered" wire:change="addIndividualAttendance" aria-describedby="" placeholder="full month">
            @elseif ($isLessFifteen == 'less_fifteen_first_half') 
                  <input type="number" tabindex="1" class="m-0 p-0 no-days @error('firstHalfDaysRendered') is-invalid @enderror" wire:model.lazy="firstHalfDaysRendered" wire:change="addIndividualAttendance" aria-describedby="" placeholder="first half">
            @else
                  <input type="number" tabindex="1" class="m-0 p-0 no-days @error('secondHalfDaysRendered') is-invalid @enderror" wire:model.lazy="secondHalfDaysRendered" wire:change="addIndividualAttendance" aria-describedby="" placeholder="second half">
            @endif
        <form>
</div>
