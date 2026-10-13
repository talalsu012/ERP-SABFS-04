<x-filament::card>
    <x-filament::section>
        <h2 class="text-xl font-bold mb-4">🧾 دليل استخدام نموذج إيصال الدفع</h2>

        <p class="mb-6">يُستخدم هذا النموذج لإدخال إيصالات الدفع الخاصة بالطلاب، حيث يتم توليد رقم إيصال فريد تلقائيًا بمجرد تحديد الطالب، ويتم جلب قيمة الدفعة المستحقة تلقائيًا بناءً على بيانات الطالب.</p>

        <x-filament::grid cols="1" md="2" gap="6">
            <!-- اختيار الطالب -->
            <x-filament::section>
                <h3 class="text-lg font-semibold mb-2">👤 اختيار الطالب</h3>
                <ul class="list-disc list-inside">
                    <li><strong>الاسم أو الرقم الجامعي:</strong> ابحث عن الطالب بالاسم أو الرقم الجامعي، عند الاختيار يتم:</li>
                    <ul class="ml-4">
                        <li>توليد رقم إيصال تلقائي مثل: <code>VOUCH-20250410-123456-00123</code>.</li>
                        <li>جلب قيمة الدفعة (Pay1 أو Pay2...) تلقائيًا.</li>
                    </ul>
                </ul>
            </x-filament::section>

            <!-- بيانات الإيصال -->
            <x-filament::section>
                <h3 class="text-lg font-semibold mb-2">💳 بيانات الإيصال</h3>
                <ul class="list-disc list-inside">
                    <li><strong>المبلغ:</strong> قيمة الدفع، تُملأ تلقائيًا ويمكن تعديلها يدويًا.</li>
                    <li><strong>العملة:</strong> اختر العملة من بين: جنيه (SDG)، دولار (USD)، ترانزفير (TRN).</li>
                    <li><strong>رقم الإيصال:</strong> يُولد تلقائيًا ولا يمكن تعديله.</li>
                </ul>
            </x-filament::section>

            <!-- تفاصيل الإيداع -->
            <x-filament::section>
                <h3 class="text-lg font-semibold mb-2">🏦 تفاصيل الإيداع</h3>
                <ul class="list-disc list-inside">
                    <li><strong>نوع الإيداع (نظام):</strong></li>
                    <ul class="ml-4">
                        <li><code>REG</code> – تسجيل.</li>
                        <li><code>CRT</code> – شهادة.</li>
                        <li><code>OTH</code> – أخرى.</li>
                    </ul>
                    <li><strong>تاريخ الإيداع:</strong> يتم تعبئته تلقائيًا بتاريخ اليوم، ويمكن تعديله.</li>
                    <li><strong>طريقة الدفع:</strong> نقدًا (CASH) أو شيك (CHECK).</li>
                    <li><strong>الفرع:</strong> إدخال اختياري لاسم فرع البنك أو الجهة.</li>
                </ul>
            </x-filament::section>
        </x-filament::grid>
    </x-filament::section>
</x-filament::card>
