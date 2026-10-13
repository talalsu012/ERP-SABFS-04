<x-filament::card>
    <x-filament::section>
        <h2 class="text-xl font-bold mb-4">دليل استخدام نموذج التسجيل</h2>
        <p class="mb-6">يحتوي هذا النموذج على عدة تبويبات لتسجيل بيانات الطالب، نوضح هنا وظائف الحقول لكل تبويب:</p>

        <x-filament::grid cols="1" md="2" gap="6">
            <!-- التبويب الأول: المعلومات الأساسية -->
            <x-filament::section>
                <h3 class="text-lg font-semibold mb-2">📝 المعلومات الأساسية</h3>
                <ul class="list-disc list-inside">
                    <li><strong>الاسم بالعربية / الإنجليزية:</strong> الاسم الكامل للطالب.</li>
                    <li><strong>الرقم الجامعي:</strong> رقم فريد لكل طالب.</li>
                    <li><strong>رقم السجل:</strong> رقم السجل الأكاديمي.</li>
                    <li><strong>الكلية / القسم / التخصص:</strong> تحديد المسار الأكاديمي.</li>
                    <li><strong>الدفعة / المستوى / نوع الدراسة / السنة الدراسية:</strong> بيانات تصنيفية.</li>
                    <li><strong>نوع القبول:</strong> مثل عام، خاص، منحة، وغيرها.</li>
                </ul>
            </x-filament::section>

            <!-- التبويب الثاني: المعلومات الشخصية -->
            <x-filament::section>
                <h3 class="text-lg font-semibold mb-2">👤 المعلومات الشخصية</h3>
                <ul class="list-disc list-inside">
                    <li><strong>الجنسية / النوع / مكان وتاريخ الميلاد:</strong> بيانات الهوية.</li>
                    <li><strong>الصورة الشخصية:</strong> رفع صورة للطالب.</li>
                    <li><strong>العنوان وبيانات التواصل:</strong> البريد والهاتف ومكان السكن.</li>
                    <li><strong>وسائل التواصل الاجتماعي / المهارات / الأنشطة:</strong> معلومات إضافية.</li>
                    <li><strong>بيانات الولي:</strong> بيانات الأب والأم مثل الاسم، الوظيفة، الاتصال.</li>
                </ul>
            </x-filament::section>

            <!-- التبويب الثالث: الرسوم -->
            <x-filament::section>
                <h3 class="text-lg font-semibold mb-2">💵 الرسوم</h3>
                <ul class="list-disc list-inside">
                    <li><strong>رسوم التسجيل:</strong> قيمة تسجيل الطالب.</li>
                    <li><strong>الرسوم الدراسية:</strong> المبلغ الأساسي المطلوب.</li>
                    <li><strong>العملة:</strong> اختيار العملة (جنيه - دولار - ريال).</li>
                    <li><strong>تفصيل الرسوم:</strong></li>
                    <ul class="ml-4">
                        <li><strong>الكورس:</strong> قيمة دراسة الكورس.</li>
                        <li><strong>امتحان:</strong> رسوم الامتحانات.</li>
                        <li><strong>بطاقة:</strong> رسوم إصدار البطاقة الجامعية.</li>
                        <li><strong>تقديم:</strong> رسوم التقديم الأولية.</li>
                        <li><strong>معاينة:</strong> رسوم المعاينات.</li>
                        <li><strong>أخرى:</strong> رسوم أخرى غير مصنفة.</li>
                    </ul>
                    <li><strong>نسبة الخصم:</strong> في حال وجود خصومات على الرسوم.</li>
                    <li><strong>التقسيط:</strong> خيارات الدفع على دفعات.</li>
                </ul>
            </x-filament::section>

            <!-- التبويب الرابع: الملاحظات -->
            <x-filament::section>
                <h3 class="text-lg font-semibold mb-2">🗒️ الملاحظات</h3>
                <p>يمكنك كتابة أي ملاحظات إضافية تتعلق بالطالب باستخدام محرر النصوص.</p>
            </x-filament::section>
        </x-filament::grid>
    </x-filament::section>
</x-filament::card>
