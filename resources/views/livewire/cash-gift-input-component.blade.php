<div>
    <form wire:submit.prevent="addIndividualCg" class="needs-validation" novalidate id="addIndividualCg">
        <input type="hidden" wire:model="yebId">
        <input type="text" tabindex="1" class="m-0 p-0 no-days @error('cg') is-invalid @enderror" wire:model.lazy="cg" aria-describedby="" placeholder="CG">
    <form>
</div>
