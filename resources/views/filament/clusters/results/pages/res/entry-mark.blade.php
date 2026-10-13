<x-filament-panels::page>
{{ $this->form }}


@if(filled($batch) && filled($faculty) && filled($level) && filled($semester) && filled($subject))

{{ $this->table }}
@else
<div class="text-center bg-blue-100 text-blue-800 p-4 rounded-lg">
    يرجى ملء جميع الحقول المطلوبة لعرض البيانات
</div>
@endif

</x-filament-panels::page>
