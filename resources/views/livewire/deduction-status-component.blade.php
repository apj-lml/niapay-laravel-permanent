<div>
    <div class="form-check form-switch">
        <input class="form-check-input" type="checkbox" wire:model="isChecked">
        <label class="form-check-label">
            {{ $isChecked ? 'ACTIVE' : 'INACTIVE' }}
        </label>
    </div>
</div>
