<div>
    <input type="hidden" wire:model="peiId">
    <textarea height="100" tabindex="-1" class="m-0 p-0 no-days text-start @error('peiRemarks') is-invalid @enderror" wire:model.lazy="peiRemarks" aria-describedby="" placeholder="Remarks"> </textarea>
</div>
