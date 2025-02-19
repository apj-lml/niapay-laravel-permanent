<div>
    <form wire:submit.prevent="addCasabloan" class="needs-validation" novalidate id="addCasabloan">
        <input type="hidden" wire:model="yebId">
        <input type="text" tabindex="1" class="m-0 p-0 no-days @error('casabloan') is-invalid @enderror" wire:model.lazy="casabloan" aria-describedby="" placeholder="Casabloan">
    <form>
</div>
