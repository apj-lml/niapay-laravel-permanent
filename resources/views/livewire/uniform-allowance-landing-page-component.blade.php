<div>
    <form class="needs-validation" novalidate>
        @csrf
        <div class="row">
            <div class="col-sm-12 mb-2">
                <div class="form-floating">
                    <input type="text" class="form-control @error('year') is-invalid @enderror" placeholder="YEAR" wire:model.debounce.500="year">
                    <label for="year">Year (CY)</label>
                    @error('year')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>
            <div class="col-sm-12 mb-2">
                <div class="form-floating">
                    <input type="text" class="form-control @error('uniformAllowance') is-invalid @enderror" placeholder="UNIFORM ALLOWANCE" wire:change="formatValue()" wire:model.debounce.500="uniformAllowance">
                    <label for="uniformAllowance">UNIFORM ALLOWANCE</label>
                    @error('uniformAllowance')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>
            <div class="col-sm-12 mb-2">
                <div class="form-floating">
                    <input type="text" class="form-control @error('mc') is-invalid @enderror" placeholder="NIA MC" wire:model.debounce.500="mc">
                    <label for="mc">NIA Memorandum Circular</label>
                    @error('mc')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>

            <div class="col-sm-12 mb-2">
                <div class="d-grid gap-2">
                    <button class="btn btn-primary" type="button" wire:click="processUniformAllowance()"> <i class="bi bi-box-arrow-in-up-right"></i> Process</button>
                  </div>
            </div>
        </div>
    </form>
</div>
