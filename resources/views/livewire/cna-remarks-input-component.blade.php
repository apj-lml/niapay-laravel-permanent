<div>
    <input type="hidden" wire:model="cnaId">
    <textarea height="100" tabindex="1" class="m-0 p-0 no-days text-start @error('cnaRemarks') is-invalid @enderror" wire:model.lazy="cnaRemarks" aria-describedby="" placeholder="Remarks"> </textarea>
</div>
