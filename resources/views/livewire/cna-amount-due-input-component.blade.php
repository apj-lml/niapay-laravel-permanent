<div>
    <input type="hidden" wire:model="cnaId">
    <input type="text" tabindex="1" class="m-0 p-0 no-days @error('amountDue') is-invalid @enderror" wire:model.lazy="amountDue" aria-describedby="" placeholder="Amount Due">
</div>
