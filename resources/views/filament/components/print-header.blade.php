<div class="flex justify-between items-center">
    <div class="w-1/4 text-center">
        <img src="{{ asset('static/academy_logo.png') }}" alt="شعار الكلية" class="h-16 mx-auto">
    </div>
    <div class="w-2/4 text-center">
        <p>{{ __('main.startpage')}}</p>
        <h1 class="text-xl font-bold text-gray-800">{{ __('main.sabfs') }}</h1>
        <h2 class="text-lg text-gray-600">كلية {{ $college }}</h2>
        <h3 class="text-md text-gray-500">{{ $title }}</h3>
        <p class="text-sm text-gray-500">{{ $subtitle }}</p>
        <p class="text-sm text-gray-500">المستوى: {{ $level }}</p>
    </div>
    <div class="w-1/4 text-left">
        <p class="text-sm text-gray-600">تاريخ الطباعة: {{ date('Y-m-d') }}</p>
    </div>
</div>
