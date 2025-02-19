<div>
    <form wire:submit.prevent="addIndividualYeb" class="needs-validation" novalidate id="addIndividualYeb">
        <input type="hidden" wire:model="yebId">
        <input type="text" tabindex="1" class="m-0 p-0 no-days @error('yeb') is-invalid @enderror" wire:model.lazy="yeb" aria-describedby="" placeholder="YEB">
    <form>
</div>
