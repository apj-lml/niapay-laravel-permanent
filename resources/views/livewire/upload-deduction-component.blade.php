@push('styles')
    <style type="text/css">
        .upload {
            margin: 10px;
            height: 85px;
            border: 8px dashed #e6e8f5;
            display: flex;
            justify-content: center;
            align-items: center;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .upload.dragover {
            background-color: #f0f2ff;
        }

        .upload p {
            margin-top: 12px;
            line-height: 0;
            font-size: 22px;
            color: #0c1232;
            letter-spacing: 1.5px;
        }

        .upload__button {
            background-color: #e6e8f5;
            border-radius: 10px;
            padding: 0px 8px 0px 10px;
        }

        .upload__button:hover {
            cursor: pointer;
            opacity: 0.8;
        }

        .file_type_input {
            border: 8px solid #e6e8f5;
            display: flex;
            justify-content: center;
            align-items: center;
            border-radius: 5px;
        }
    </style>
@endpush

<div class="row">
    <div class="col-sm-12">
        <select class="form-select file_type_input" wire:model="selectedDeductionType">
            <option value="" hidden>Select Deduction Type</option>
            @foreach ($deductionTypes as $deductionType)
                <option value="{{ $deductionType }}">{{ $deductionType }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-sm-12">
        <div class="upload" id="upload-area">
            <input type="file" id="fileInput" wire:model="uploadedFile" hidden>
            <p>Drag files here or <span class="upload__button" id="browseButton">Browse</span></p>
        </div>
    </div>
    <div class="col-sm-12">
        <div class="mx-auto w-100">
            <button class="btn btn-primary mt-1 mx-auto w-100" wire:click="uploadFile" wire:loading.attr="disabled" @if (!$uploadedFile) disabled @endif>
                <span wire:loading.remove><i class="bi bi-file-earmark-arrow-up"></i> Upload</span>
                <span wire:loading>Uploading...</span>
            </button>
        </div>

    </div>
    <div class="mt-3">
        @if ($uploadedFile)
            <p>File: <strong>{{ $uploadedFile->getClientOriginalName() }}</strong> </p>
        @endif
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const uploadArea = document.getElementById('upload-area');
        const fileInput = document.getElementById('fileInput');
        const browseButton = document.getElementById('browseButton');

        // Browse button click opens file dialog
        browseButton.addEventListener('click', () => fileInput.click());

        // Drag over
        uploadArea.addEventListener('dragover', (e) => {
            e.preventDefault();
            uploadArea.classList.add('dragover');
        });

        // Drag leave
        uploadArea.addEventListener('dragleave', () => {
            uploadArea.classList.remove('dragover');
        });

        // Drop file
        uploadArea.addEventListener('drop', (e) => {
            e.preventDefault();
            uploadArea.classList.remove('dragover');

            if (e.dataTransfer.files.length) {
                fileInput.files = e.dataTransfer.files;
                // Trigger change event manually for Livewire
                fileInput.dispatchEvent(new Event('change'));
            }
        });
    });
</script>
@endpush