<div dir="rtl" class="p-6 bg-white rounded-lg shadow-md max-w-4xl mx-auto">
    <!-- العنوان الرئيسي -->
    <div class="mb-8 text-center">
        <h1 class="text-2xl font-bold text-primary-600 mb-2">دليل استخدام نموذج إدارة الأعمدة</h1>
        <p class="text-gray-500">دليل تفصيلي لكيفية استخدام نموذج إضافة/تعديل الأعمدة في النظام</p>
    </div>

    <!-- القسم الرئيسي -->
    <div class="mb-8 bg-gray-50 p-4 rounded-lg border border-gray-200">
        <h2 class="text-xl font-semibold text-gray-700 mb-4 flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-primary-500" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
            </svg>
            الحقول الأساسية
        </h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- حقل الكود -->
            <div class="bg-white p-3 rounded border border-gray-100 shadow-sm">
                <div class="flex justify-between items-start">
                    <h3 class="font-medium text-gray-700">كود العمود</h3>
                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800">مطلوب</span>
                </div>
                <ul class="mt-2 text-sm text-gray-600 space-y-1 list-disc list-inside">
                    <li>يجب أن يكون رقماً فقط</li>
                    <li>يجب أن يكون فريداً (غير مكرر)</li>
                    <li>الحد الأقصى 10 خانات</li>
                </ul>
            </div>

            <!-- حقل الاسم العربي -->
            <div class="bg-white p-3 rounded border border-gray-100 shadow-sm">
                <div class="flex justify-between items-start">
                    <h3 class="font-medium text-gray-700">الاسم (عربي)</h3>
                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800">مطلوب</span>
                </div>
                <ul class="mt-2 text-sm text-gray-600 space-y-1 list-disc list-inside">
                    <li>اسم العمود باللغة العربية</li>
                    <li>الحد الأقصى 100 حرف</li>
                </ul>
            </div>

            <!-- حقل الاسم الإنجليزي -->
            <div class="bg-white p-3 rounded border border-gray-100 shadow-sm">
                <h3 class="font-medium text-gray-700">الاسم (إنجليزي)</h3>
                <ul class="mt-2 text-sm text-gray-600 space-y-1 list-disc list-inside">
                    <li>اسم العمود باللغة الإنجليزية</li>
                    <li>الحد الأقصى 100 حرف</li>
                    <li>حقل اختياري</li>
                </ul>
            </div>

            <!-- حقل المجموعة -->
            <div class="bg-white p-3 rounded border border-gray-100 shadow-sm">
                <div class="flex justify-between items-start">
                    <h3 class="font-medium text-gray-700">المجموعة</h3>
                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800">مطلوب</span>
                </div>
                <ul class="mt-2 text-sm text-gray-600 space-y-1 list-disc list-inside">
                    <li>اختر المجموعة التي ينتمي إليها العمود</li>
                    <li>يتم تحميل الخيارات من قاعدة البيانات</li>
                </ul>
            </div>

            <!-- حقل الوصف -->
            <div class="bg-white p-3 rounded border border-gray-100 shadow-sm md:col-span-2">
                <h3 class="font-medium text-gray-700">الوصف</h3>
                <ul class="mt-2 text-sm text-gray-600 space-y-1 list-disc list-inside">
                    <li>وصف مختصر للعمود</li>
                    <li>الحد الأقصى 100 حرف</li>
                    <li>حقل اختياري</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- قسم معلومات النظام -->
    <div class="mb-8 bg-gray-50 p-4 rounded-lg border border-gray-200">
        <h2 class="text-xl font-semibold text-gray-700 mb-4 flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-primary-500" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
            </svg>
            معلومات النظام
        </h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="bg-white p-3 rounded border border-gray-100 shadow-sm">
                <h3 class="font-medium text-gray-700">تاريخ الإنشاء</h3>
                <ul class="mt-2 text-sm text-gray-600 space-y-1 list-disc list-inside">
                    <li>يظهر فقط للسجلات الموجودة</li>
                    <li>يعرض الوقت منذ الإنشاء (مثال: منذ ساعتين)</li>
                </ul>
            </div>

            <div class="bg-white p-3 rounded border border-gray-100 shadow-sm">
                <h3 class="font-medium text-gray-700">تاريخ التعديل</h3>
                <ul class="mt-2 text-sm text-gray-600 space-y-1 list-disc list-inside">
                    <li>يظهر فقط للسجلات الموجودة</li>
                    <li>يعرض الوقت منذ آخر تعديل</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- ملاحظات مهمة -->
    <div class="bg-blue-50 p-4 rounded-lg border border-blue-200">
        <h2 class="text-lg font-semibold text-blue-700 mb-3 flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-500" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
            </svg>
            ملاحظات مهمة
        </h2>
        <ul class="text-blue-600 space-y-2">
            <li class="flex items-start gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-500 mt-0.5 flex-shrink-0" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
                <span>الحقول المميزة بعلامة "مطلوب" إلزامية ولا يمكن تركها فارغة</span>
            </li>
            <li class="flex items-start gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-500 mt-0.5 flex-shrink-0" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
                <span>يجب التأكد من عدم تكرار كود العمود</span>
            </li>
            <li class="flex items-start gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-500 mt-0.5 flex-shrink-0" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
                <span>يتم حفظ البيانات عند الضغط على زر "حفظ"</span>
            </li>
        </ul>
    </div>
</div>
