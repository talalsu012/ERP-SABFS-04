<x-filament::card>
    <x-filament::section>
        <h2 class="text-xl font-bold mb-4">📊 دليل استخدام صفحة تقرير حالة التسجيل</h2>

        <p class="mb-6">
            تتيح لك هذه الصفحة عرض حالة تسجيل الطلاب حسب الكليات، موزعة على ثلاث فئات رئيسية: مكتمل، بالتقسيط، وغير مسجل. كما يمكنك تصفية النتائج حسب الكلية والمستوى الأكاديمي.
        </p>

        <!-- الأقسام التوضيحية -->
        <x-filament::section>
            <h3 class="text-lg font-semibold mb-2">🎯 محتوى الجدول</h3>
            <ul class="list-disc list-inside space-y-2">
                <li><strong>الكلية:</strong> اسم الكلية المعروضة في التقرير.</li>
                <li><strong>مكتمل:</strong> عدد الطلاب المسجلين بالكامل، مع عرض النسبة المئوية من إجمالي الطلاب.</li>
                <li><strong>بالتقسيط:</strong> عدد الطلاب الذين قاموا بدفع الأقساط، مع النسبة المئوية.</li>
                <li><strong>غير مسجل:</strong> عدد الطلاب غير المسجلين، مع النسبة المئوية.</li>
                <li><strong>الإجمالي:</strong> العدد الكلي للطلاب في الكلية.</li>
            </ul>
        </x-filament::section>

        <x-filament::section class="mt-6">
            <h3 class="text-lg font-semibold mb-2">🧭 استخدام الفلاتر</h3>
            <ul class="list-disc list-inside space-y-2">
                <li><strong>تصفية حسب الكلية:</strong> يمكنك اختيار كلية أو أكثر لتصفية النتائج، مع إمكانية البحث داخل القائمة.</li>
                <li><strong>تصفية حسب المستوى:</strong> يعرض التقرير بناءً على المستوى الأكاديمي للطلاب، مثل السنة الأولى، الثانية، إلخ.</li>
                <!-- يمكن توسيع القائمة إذا تم تفعيل المزيد من الفلاتر مستقبلاً -->
            </ul>
        </x-filament::section>

        <x-filament::section class="mt-6">
            <h3 class="text-lg font-semibold mb-2">📈 المخططات المصاحبة</h3>
            <p>
                تعرض هذه الصفحة أيضًا عناصر واجهة (Widgets) في الأعلى مثل:
            </p>
            <ul class="list-disc list-inside">
                <li><strong>حالة التسجيل الإجمالية:</strong> ملخص إجمالي لحالة الطلاب.</li>
                <li><strong>حالة التسجيل حسب الكليات:</strong> توزيع الحالة بين الكليات.</li>
            </ul>
        </x-filament::section>

        <x-filament::section class="mt-6">
            <h3 class="text-lg font-semibold mb-2">🔧 ملاحظات تقنية</h3>
            <ul class="list-disc list-inside text-sm text-gray-700">
                <li>يتم احتساب النسب المئوية داخل الأعمدة باستخدام العلاقة بين عدد الطلاب في الحالة المعينة والإجمالي.</li>
                <li>يتم جلب البيانات باستخدام علاقات `withCount()` من نموذج الكلية.</li>
                <li>يمكن إضافة المزيد من الفلاتر كالجنس، القبول، أو الحالة إذا تم تفعيلها لاحقاً في الكود.</li>
            </ul>
        </x-filament::section>
    </x-filament::section>
</x-filament::card>
