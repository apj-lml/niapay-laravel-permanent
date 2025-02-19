<div>
    <form wire:submit.prevent="submit">
        @csrf
        <div>
            @if (session()->has('message'))
                <div class="alert alert-success">
                    {{ session('message') }}
                </div>
            @endif
        </div>
        {{-- <div class="row mb-3">
            <label for="employee_id" class="col-md-4 col-form-label text-md-end">{{ __('ID No.') }}</label>

            <div class="col-md-6">
                <input id="employee_id" type="text" class="form-control @error('employee_id') is-invalid @enderror" name="employee_id" value="{{ old('employee_id') }}" required autocomplete="employee_id" autofocus>

                @error('employee_id')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div> --}}

        <div class="row mb-1">
            <label for="password" class="col-form-label text-md-first">{{ __('Current Password') }}</label>

            <div class="col-md-6 input-group">
                <input id="password" type="{{ $fieldType }}" class="form-control @error('password') is-invalid @enderror" name="password" wire:model="password" required autocomplete="current-password">
                {{-- <button class="btn btn-outline-secondary" type="button" wire:click="changeType()"><i class="bi bi-eye"></i></button> --}}
                @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>

        <div class="row mb-1">
            <label for="newPassword" class="col-form-label text-md-first">{{ __('New Password') }}</label>

            <div class="col-md-6 input-group">
                <input id="newPassword" type="{{ $fieldType }}" class="form-control @error('newPassword') is-invalid @enderror" name="newPassword" wire:model="newPassword" required>
                {{-- <button class="btn btn-outline-secondary" type="button" wire:click="changeType()"><i class="bi bi-eye"></i></button> --}}
                @error('newPassword')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>

        <div class="row mb-3">
            <label for="newPassword_confirmation" class="col-form-label text-md-first">{{ __('Confirm Password') }}</label>

            <div class="col-md-6 input-group">
                <input id="newPassword_confirmation" type="{{ $fieldType }}" class="form-control @error('newPassword_confirmation') is-invalid @enderror" name="newPassword_confirmation" wire:model="newPassword_confirmation" required>
                {{-- <button class="btn btn-outline-secondary" type="button" wire:click="changeType()"><i class="bi bi-eye"></i></button> --}}

                @error('newPassword_confirmation')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>

        <div class="row mb-0 row">
            <div class="col-md-12 mb-3">
                <a type="submit" class="" wire:click="changeType()">
                    <i class="bi bi-eye"></i> {{ __('Show Passwords') }}
                </a>
            </div>
            <div class="col-md-12 ">
                <button type="submit" class="btn btn-primary">
                    {{ __('Update Password') }}
                </button>
                
            </div>

        </div>
    </form>
</div>
