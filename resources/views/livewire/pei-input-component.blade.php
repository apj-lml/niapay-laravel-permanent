<div>
    <input type="hidden" wire:model="peiId">
    <input type="text" tabindex="1" class="m-0 p-0 no-days text-center @error('peiInput') is-invalid @enderror" wire:model.lazy="peiInput" aria-describedby="" placeholder="PEI"/>
</div>
