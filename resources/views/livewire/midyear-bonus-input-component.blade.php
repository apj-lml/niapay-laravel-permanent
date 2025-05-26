<div>
    <form wire:submit.prevent="addIndividualMyb" class="needs-validation" novalidate id="addIndividualMyb">
        <input type="hidden" wire:model="mybId">
        <input type="text" tabindex="1" class="m-0 p-0 no-days @error('myb') is-invalid @enderror" wire:model.lazy="myb" aria-describedby="" placeholder="MYB">
    <form>
</div>