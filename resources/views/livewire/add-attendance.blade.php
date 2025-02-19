<div>
    <!-- Button trigger modal -->
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addAttendanceModal">
        <i class="fas fa-plus"></i> Add No. of Days  
    </button>

    @include('livewire.modals.add-attendance-modal')
    {{-- @livewire('modals.add-attendance-modal', ['payrollDateFrom' => $payrollDateFrom, 'payrollDateTo' => $payrollDateTo, 'isLessFifteen' => 1]) --}}
    {{-- @include('livewire.modals.configure-attendance-modal') --}}


</div>