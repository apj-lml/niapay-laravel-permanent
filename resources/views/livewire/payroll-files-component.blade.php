<div>
    <h2 class="text-xl font-bold mb-4">Payroll Files</h2>

    <input
        type="text"
        class="border px-3 py-1 mb-4 w-full"
        placeholder="Search filename..."
        wire:model.debounce.300ms="search"
    />

    <ul class="mb-4">
        @forelse ($files as $file)
            <li class="mb-2">
                <a href="{{ route('payroll.download', ['filename' => $file['name']]) }}" class="text-blue-500 underline">
                    {{ $file['name'] }}
                </a>
                <span class="text-sm text-gray-600">
                    - {{ \Carbon\Carbon::createFromTimestamp($file['created_at'])->toDayDateTimeString() }}
                </span>
            </li>
        @empty
            <li class="text-gray-500">No files found.</li>
        @endforelse
    </ul>

    <div>
        {{ $files->links() }}
    </div>
</div>
