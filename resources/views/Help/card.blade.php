<x-filament::card>
    <x-filament::section>
        <h2 class="text-xl font-bold mb-4">🎓 دليل استخدام نموذج إصدار البطاقة الجامعية</h2>

        <p class="mb-6">
            يُستخدم هذا النموذج لإصدار بطاقة جامعية للطلاب. عند اختيار الطالب، يتم توليد رقم بطاقة فريد تلقائيًا. يمكن تحديد حالة البطاقة وتاريخ الإصدار والانتهاء، بالإضافة إلى خيار إظهار البطاقة في النظام أو إخفائها.
        </p>

        <!-- القسم الأول: بيانات الطالب والبطاقة -->
        <x-filament::section>
            <h3 class="text-lg font-semibold mb-2">👤 بيانات الطالب والبطاقة</h3>
            <ul class="list-disc list-inside space-y-2">
                <li><strong>اسم الطالب:</strong> اختر الطالب من القائمة باستخدام الاسم العربي، البحث متاح.</li>
                <li><strong>رقم البطاقة:</strong> يُولد تلقائيًا بعد اختيار الطالب بالصيغة:
                    <code>UNI-XXXXX-ABCD-1234</code> حيث:
                    <ul class="ml-4 list-disc">
                        <li><code>XXXXX</code>: أول 5 أحرف من الرقم الجامعي.</li>
                        <li><code>ABCD</code>: حروف عشوائية.</li>
                        <li><code>1234</code>: أرقام عشوائية.</li>
                    </ul>
                </li>
                <li><strong>حالة البطاقة:</strong> اختر حالة البطاقة من الخيارات المتاحة مثل "نشطة" أو "غير نشطة" (تُحدد عبر <code>CardStatus</code>).</li>
            </ul>
        </x-filament::section>

        <!-- القسم الثاني: التواريخ والإظهار -->
        <x-filament::section class="mt-6">
            <h3 class="text-lg font-semibold mb-2">🗓️ التواريخ وخيارات الظهور</h3>
            <ul class="list-disc list-inside space-y-2">
                <li><strong>تاريخ الإصدار:</strong> اختر تاريخ إصدار البطاقة بصيغة يوم - شهر - سنة. التاريخ إلزامي.</li>
                <li><strong>تاريخ الانتهاء:</strong> اختياري، ويجب أن يكون بعد تاريخ الإصدار.</li>
                <li><strong>إظهار البطاقة:</strong> زر تبديل يسمح بإظهار أو إخفاء البطاقة في النظام.</li>
            </ul>
        </x-filament::section>
    </x-filament::section>
</x-filament::card>
