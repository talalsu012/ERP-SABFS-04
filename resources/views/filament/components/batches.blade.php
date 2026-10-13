@props(['batches', 'record'])

<div class="flex items-center gap-2">
    <!-- Clickable code/link -->
    <a href="{{ route('filament.resources.batches.view', ['record' => $record]) }}"
       class="text-primary-600 hover:text-primary-800 hover:underline"
       wire:navigate>
        {{ $record->code }}
    </a>

    <!-- Batch details -->
    @if($batches->isNotEmpty())
        <span class="text-xs text-gray-500">
            ({{ $batches->count() }} related batches)
        </span>
    @endif
</div>
