
        <!-- Header for print -->
        <div id="header" class="w-full text-center border-b-2 border-gray-300 pb-4 mb-6">
        <p>{{ __('main.startpage') }}</p>
        <h2 class="text-2xl font-bold">{{ __('main.sabfs') }}</h2>
            <h3 class="text-md font-bold">{{ $students->first()->faculty->name ?? 'غير محدد' }}</h3>
            <h3 class="text-xl mt-2">كشف درجات طلاب {{ $course->name }} - الدفعة {{ $students->first()->batch->batch_start ?? 'غير محدد' }}</h3>
            <h4 class="text-lg">المستوى: {{ $students->first()->level->name_ar ?? 'غير محدد' }}</h4>
        </div>


