<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="text-center mb-12">
        <h1 class="text-3xl font-bold text-blue-800 mb-2">دليل استخدام نظام إدارة الطلاب</h1>
        <p class="text-gray-600">دليل شامل لاستخدام لوحة إدارة بيانات الطلاب</p>
    </div>



    <!-- Tabs Navigation
    <div class="flex flex-wrap border-b border-gray-200 mb-8">
        <button onclick="showTab('form-guide')" class="tab-btn active px-6 py-3 font-medium text-blue-600 border-b-2 border-blue-600">
            نموذج إضافة/تعديل الطلاب
        </button>
        <button onclick="showTab('table-guide')" class="tab-btn px-6 py-3 font-medium text-gray-500 hover:text-blue-500">
            جدول عرض الطلاب
        </button>
        <button onclick="showTab('actions-guide')" class="tab-btn px-6 py-3 font-medium text-gray-500 hover:text-blue-500">
            الإجراءات والعمليات
        </button>
    </div> -->



    <!-- Table Guide Content -->
    <div  class="tab-content">
        <div class="bg-white rounded-lg shadow-md p-6 mb-8">
            <h2 class="text-2xl font-bold text-blue-700 mb-6 border-b pb-2">جدول عرض بيانات الطلاب</h2>

            <!-- Table Features -->
            <div class="mb-8">
                <h3 class="text-xl font-semibold text-gray-800 mb-4">مميزات الجدول</h3>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="bg-blue-50 p-4 rounded-lg border border-blue-100">
                        <h4 class="font-bold text-blue-700 mb-2">التجميع</h4>
                        <p class="text-sm text-blue-600">يمكن تجميع الطلاب حسب:</p>
                        <ul class="list-disc list-inside text-sm text-blue-600 mt-2">
                            <li>الكلية</li>
                            <li>المستوى</li>
                            <li>حالة التسجيل</li>
                            <li>طريقة القبول</li>
                        </ul>
                    </div>

                    <div class="bg-green-50 p-4 rounded-lg border border-green-100">
                        <h4 class="font-bold text-green-700 mb-2">الفرز والبحث</h4>
                        <p class="text-sm text-green-600">يمكن:</p>
                        <ul class="list-disc list-inside text-sm text-green-600 mt-2">
                            <li>فرز الطلاب حسب أي عمود</li>
                            <li>البحث بالاسم أو الرقم الجامعي</li>
                            <li>البحث الفردي في كل عمود</li>
                        </ul>
                    </div>

                    <div class="bg-purple-50 p-4 rounded-lg border border-purple-100">
                        <h4 class="font-bold text-purple-700 mb-2">إظهار/إخفاء الأعمدة</h4>
                        <p class="text-sm text-purple-600">يمكن:</p>
                        <ul class="list-disc list-inside text-sm text-purple-600 mt-2">
                            <li>إظهار أو إخفاء أي عمود</li>
                            <li>تخصيص الأعمدة الظاهرة</li>
                            <li>حفظ التفضيلات</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Filters -->
            <div class="mb-8">
                <h3 class="text-xl font-semibold text-gray-800 mb-4">الفلاتر والتصفية</h3>

                <div class="bg-gray-50 p-4 rounded-lg">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h4 class="font-bold text-gray-700 mb-2">فلاتر أساسية</h4>
                            <ul class="list-disc list-inside text-sm text-gray-600 space-y-1">
                                <li>تصفية حسب الكلية</li>
                                <li>تصفية حسب المستوى</li>
                                <li>تصفية حسب حالة التسجيل</li>
                                <li>تصفية حسب الجنس</li>
                            </ul>
                        </div>

                        <div>
                            <h4 class="font-bold text-gray-700 mb-2">بناء الاستعلام</h4>
                            <ul class="list-disc list-inside text-sm text-gray-600 space-y-1">
                                <li>بحث متقدم بالاسم</li>
                                <li>تصفية حسب الدفعة</li>
                                <li>تصفية حسب الرسوم</li>
                                <li>تصفية حسب تاريخ الإنشاء</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Columns -->
            <div>
                <h3 class="text-xl font-semibold text-gray-800 mb-4">الأعمدة الرئيسية</h3>

                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white border border-gray-200">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="py-2 px-4 border-b text-right font-medium text-gray-700">اسم العمود</th>
                                <th class="py-2 px-4 border-b text-right font-medium text-gray-700">الوصف</th>
                                <th class="py-2 px-4 border-b text-right font-medium text-gray-700">مخفي افتراضياً</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="py-2 px-4 border-b text-right">الصورة</td>
                                <td class="py-2 px-4 border-b text-right">الصورة الشخصية للطالب</td>
                                <td class="py-2 px-4 border-b text-center">لا</td>
                            </tr>
                            <tr class="bg-gray-50">
                                <td class="py-2 px-4 border-b text-right">الكلية</td>
                                <td class="py-2 px-4 border-b text-right">اسم الكلية المسجل بها الطالب</td>
                                <td class="py-2 px-4 border-b text-center">لا</td>
                            </tr>
                            <tr>
                                <td class="py-2 px-4 border-b text-right">الاسم بالعربية</td>
                                <td class="py-2 px-4 border-b text-right">اسم الطالب باللغة العربية</td>
                                <td class="py-2 px-4 border-b text-center">لا</td>
                            </tr>
                            <tr class="bg-gray-50">
                                <td class="py-2 px-4 border-b text-right">الرقم الجامعي</td>
                                <td class="py-2 px-4 border-b text-right">الرقم الجامعي للطالب</td>
                                <td class="py-2 px-4 border-b text-center">لا</td>
                            </tr>
                            <tr>
                                <td class="py-2 px-4 border-b text-right">حالة التسجيل</td>
                                <td class="py-2 px-4 border-b text-right">حالة تسجيل الطالب</td>
                                <td class="py-2 px-4 border-b text-center">لا</td>
                            </tr>
                            <tr class="bg-gray-50">
                                <td class="py-2 px-4 border-b text-right">الرسوم</td>
                                <td class="py-2 px-4 border-b text-right">إجمالي الرسوم الدراسية</td>
                                <td class="py-2 px-4 border-b text-center">لا</td>
                            </tr>
                            <tr>
                                <td class="py-2 px-4 border-b text-right">المتحصل</td>
                                <td class="py-2 px-4 border-b text-right">المبلغ المدفوع</td>
                                <td class="py-2 px-4 border-b text-center">نعم</td>
                            </tr>
                            <tr class="bg-gray-50">
                                <td class="py-2 px-4 border-b text-right">المتبقي</td>
                                <td class="py-2 px-4 border-b text-right">المبلغ المتبقي للدفع</td>
                                <td class="py-2 px-4 border-b text-center">نعم</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Actions Guide Content -->
    <div  class="tab-content">
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-2xl font-bold text-blue-700 mb-6 border-b pb-2">الإجراءات والعمليات المتاحة</h2>

            <!-- Individual Actions -->
            <div class="mb-8">
                <h3 class="text-xl font-semibold text-gray-800 mb-4">إجراءات فردية</h3>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="bg-green-50 p-4 rounded-lg border border-green-100">
                        <h4 class="font-bold text-green-700 mb-2">عرض</h4>
                        <p class="text-sm text-green-600">عرض كافة تفاصيل الطالب في صفحة واحدة</p>
                    </div>

                    <div class="bg-blue-50 p-4 rounded-lg border border-blue-100">
                        <h4 class="font-bold text-blue-700 mb-2">تعديل</h4>
                        <p class="text-sm text-blue-600">تعديل بيانات الطالب</p>
                    </div>

                    <div class="bg-red-50 p-4 rounded-lg border border-red-100">
                        <h4 class="font-bold text-red-700 mb-2">حذف</h4>
                        <p class="text-sm text-red-600">حذف سجل الطالب</p>
                    </div>
                </div>
            </div>

            <!-- Bulk Actions -->
            <div class="mb-8">
                <h3 class="text-xl font-semibold text-gray-800 mb-4">إجراءات جماعية</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="bg-purple-50 p-4 rounded-lg border border-purple-100">
                        <h4 class="font-bold text-purple-700 mb-2">تحديث جماعي</h4>
                        <p class="text-sm text-purple-600">تحديث بيانات مجموعة من الطلاب مرة واحدة:</p>
                        <ul class="list-disc list-inside text-sm text-purple-600 mt-2">
                            <li>تحديث الكلية أو القسم</li>
                            <li>تحديث المستوى أو الدفعة</li>
                            <li>تحديث حالة التسجيل</li>
                            <li>تحديث الرسوم الدراسية</li>
                        </ul>
                    </div>

                    <div class="bg-yellow-50 p-4 rounded-lg border border-yellow-100">
                        <h4 class="font-bold text-yellow-700 mb-2">حذف جماعي</h4>
                        <p class="text-sm text-yellow-600">حذف مجموعة من سجلات الطلاب المحددين</p>
                    </div>
                </div>
            </div>

            <!-- Import/Export -->
            <div>
                <h3 class="text-xl font-semibold text-gray-800 mb-4">استيراد وتصدير البيانات</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="bg-indigo-50 p-4 rounded-lg border border-indigo-100">
                        <h4 class="font-bold text-indigo-700 mb-2">تصدير البيانات</h4>
                        <p class="text-sm text-indigo-600">تصدير بيانات الطلاب إلى:</p>
                        <ul class="list-disc list-inside text-sm text-indigo-600 mt-2">
                            <li>ملف Excel (XLSX)</li>
                            <li>ملف CSV</li>
                        </ul>
                    </div>

                    <div class="bg-pink-50 p-4 rounded-lg border border-pink-100">
                        <h4 class="font-bold text-pink-700 mb-2">استيراد البيانات</h4>
                        <p class="text-sm text-pink-600">استيراد بيانات الطلاب من ملف Excel أو CSV</p>
                    </div>
                </div>
            </div>
        </div>
    </div>


        <!-- Form Guide Content -->
        <div id="form-guide" class="tab-content">
        <div class="bg-white rounded-lg shadow-md p-6 mb-8">
            <h2 class="text-2xl font-bold text-blue-700 mb-6 border-b pb-2">نموذج إضافة/تعديل بيانات الطالب</h2>

            <!-- Basic Info Tab -->
            <div class="mb-10">
                <h3 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                    <span class="bg-blue-100 text-blue-800 p-2 rounded-full mr-2">1</span>
                    المعلومات الأساسية
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Personal Info -->
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h4 class="font-bold text-gray-700 mb-3">المعلومات الشخصية</h4>
                        <ul class="space-y-3">
                            <li class="p-2 bg-white rounded shadow-sm">
                                <span class="font-medium text-gray-700">الاسم بالعربية:</span>
                                <span class="text-sm text-gray-600 block">- مطلوب - 5 أحرف على الأقل</span>
                            </li>
                            <li class="p-2 bg-white rounded shadow-sm">
                                <span class="font-medium text-gray-700">الاسم بالإنجليزية:</span>
                                <span class="text-sm text-gray-600 block">- اختياري - 5 أحرف على الأقل</span>
                            </li>
                            <li class="p-2 bg-white rounded shadow-sm">
                                <span class="font-medium text-gray-700">الرقم الجامعي:</span>
                                <span class="text-sm text-gray-600 block">- مطلوب - فريد - 20 حرف كحد أقصى</span>
                            </li>
                            <li class="p-2 bg-white rounded shadow-sm">
                                <span class="font-medium text-gray-700">رقم الهوية/القومي:</span>
                                <span class="text-sm text-gray-600 block">- فريد - 20 حرف كحد أقصى</span>
                            </li>
                        </ul>
                    </div>

                    <!-- Academic Info -->
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h4 class="font-bold text-gray-700 mb-3">المعلومات الأكاديمية</h4>
                        <ul class="space-y-3">
                            <li class="p-2 bg-white rounded shadow-sm">
                                <span class="font-medium text-gray-700">الكلية:</span>
                                <span class="text-sm text-gray-600 block">- مطلوب - يتم البحث في القائمة</span>
                            </li>
                            <li class="p-2 bg-white rounded shadow-sm">
                                <span class="font-medium text-gray-700">القسم:</span>
                                <span class="text-sm text-gray-600 block">- مطلوب - يتم تحديثه حسب الكلية</span>
                            </li>
                            <li class="p-2 bg-white rounded shadow-sm">
                                <span class="font-medium text-gray-700">التخصص:</span>
                                <span class="text-sm text-gray-600 block">- يتم تحديثه حسب القسم</span>
                            </li>
                            <li class="p-2 bg-white rounded shadow-sm">
                                <span class="font-medium text-gray-700">المستوى:</span>
                                <span class="text-sm text-gray-600 block">- يتم البحث في القائمة</span>
                            </li>
                            <li class="p-2 bg-white rounded shadow-sm">
                                <span class="font-medium text-gray-700">الدفعة:</span>
                                <span class="text-sm text-gray-600 block">- مطلوب - يمكن إضافة دفعة جديدة</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Personal Info Tab -->
            <div class="mb-10">
                <h3 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                    <span class="bg-blue-100 text-blue-800 p-2 rounded-full mr-2">2</span>
                    المعلومات الشخصية
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Personal Details -->
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h4 class="font-bold text-gray-700 mb-3">البيانات الشخصية</h4>
                        <ul class="space-y-3">
                            <li class="p-2 bg-white rounded shadow-sm">
                                <span class="font-medium text-gray-700">الجنسية:</span>
                                <span class="text-sm text-gray-600 block">- مطلوب - يتم البحث في القائمة</span>
                            </li>
                            <li class="p-2 bg-white rounded shadow-sm">
                                <span class="font-medium text-gray-700">الجنس:</span>
                                <span class="text-sm text-gray-600 block">- مطلوب - اختيار من أزرار</span>
                            </li>
                            <li class="p-2 bg-white rounded shadow-sm">
                                <span class="font-medium text-gray-700">مكان الولادة:</span>
                                <span class="text-sm text-gray-600 block">- مطلوب</span>
                            </li>
                            <li class="p-2 bg-white rounded shadow-sm">
                                <span class="font-medium text-gray-700">تاريخ الميلاد:</span>
                                <span class="text-sm text-gray-600 block">- مطلوب - اختيار من التقويم</span>
                            </li>
                        </ul>
                    </div>

                    <!-- Photo Upload -->
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h4 class="font-bold text-gray-700 mb-3">الصورة الشخصية</h4>
                        <div class="p-2 bg-white rounded shadow-sm">
                            <p class="text-sm text-gray-600">
                                - يمكن تحميل صورة شخصية<br>
                                - يمكن تعديل الصورة قبل الحفظ<br>
                                - اقتصاص الصورة بشكل دائري<br>
                                - يتم تخزين الصور في مجلد "std"
                            </p>
                        </div>
                    </div>

                    <!-- Contact Info -->
                    <div class="md:col-span-2 bg-gray-50 p-4 rounded-lg">
                        <h4 class="font-bold text-gray-700 mb-3">معلومات الاتصال</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="p-2 bg-white rounded shadow-sm">
                                <span class="font-medium text-gray-700">البريد الإلكتروني:</span>
                                <span class="text-sm text-gray-600 block">- مطلوب - يجب أن يكون فريداً</span>
                            </div>
                            <div class="p-2 bg-white rounded shadow-sm">
                                <span class="font-medium text-gray-700">رقم الهاتف:</span>
                                <span class="text-sm text-gray-600 block">- يجب أن يكون رقم هاتف صالح</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Fees Tab -->
            <div class="mb-10">
                <h3 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                    <span class="bg-blue-100 text-blue-800 p-2 rounded-full mr-2">3</span>
                    الرسوم الدراسية
                </h3>

                <div class="bg-gray-50 p-4 rounded-lg">
                    <h4 class="font-bold text-gray-700 mb-3">الرسوم الأساسية</h4>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                        <div class="p-2 bg-white rounded shadow-sm">
                            <span class="font-medium text-gray-700">رسوم التسجيل:</span>
                            <span class="text-sm text-gray-600 block">- مطلوب - قيمة رقمية</span>
                        </div>
                        <div class="p-2 bg-white rounded shadow-sm">
                            <span class="font-medium text-gray-700">الرسوم الدراسية:</span>
                            <span class="text-sm text-gray-600 block">- مطلوب - قيمة رقمية</span>
                        </div>
                        <div class="p-2 bg-white rounded shadow-sm">
                            <span class="font-medium text-gray-700">العملة:</span>
                            <span class="text-sm text-gray-600 block">- مطلوب - اختيار من أزرار</span>
                        </div>
                    </div>

                    <h4 class="font-bold text-gray-700 mb-3">الرسوم الإضافية</h4>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                        <div class="p-2 bg-white rounded shadow-sm">
                            <span class="font-medium text-gray-700">الكورس:</span>
                            <span class="text-sm text-gray-600 block">- مطلوب - قيمة رقمية</span>
                        </div>
                        <div class="p-2 bg-white rounded shadow-sm">
                            <span class="font-medium text-gray-700">الامتحان:</span>
                            <span class="text-sm text-gray-600 block">- مطلوب - قيمة رقمية</span>
                        </div>
                        <div class="p-2 bg-white rounded shadow-sm">
                            <span class="font-medium text-gray-700">البطاقة:</span>
                            <span class="text-sm text-gray-600 block">- مطلوب - قيمة رقمية</span>
                        </div>
                    </div>

                    <h4 class="font-bold text-gray-700 mb-3">نظام الأقساط</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="p-2 bg-white rounded shadow-sm">
                            <span class="font-medium text-gray-700">نظام الأقساط:</span>
                            <span class="text-sm text-gray-600 block">- اختيار من القائمة</span>
                        </div>
                        <div class="p-2 bg-white rounded shadow-sm">
                            <span class="font-medium text-gray-700">القسط الأول:</span>
                            <span class="text-sm text-gray-600 block">- قيمة رقمية - غير قابلة للتعديل</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Notes Tab -->
            <div>
                <h3 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                    <span class="bg-blue-100 text-blue-800 p-2 rounded-full mr-2">4</span>
                    الملاحظات
                </h3>

                <div class="bg-gray-50 p-4 rounded-lg">
                    <div class="p-2 bg-white rounded shadow-sm">
                        <p class="text-sm text-gray-600">
                            - يمكن إضافة ملاحظات باستخدام محرر النصوص المتقدم<br>
                            - يدعم المحرر: النص الغامق، العناوين، الخط المحذوف، الخط التحتي<br>
                            - يمكن إرفاق ملفات بالملاحظات<br>
                            - يوجد زر للتراجع عن التعديلات
                        </p>
                    </div>
                </div>
            </div>
        </div>

          <!-- Important Notes -->
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mt-8">
        <h3 class="text-xl font-semibold text-blue-700 mb-4">ملاحظات مهمة</h3>
        <ul class="space-y-2 text-blue-600">
            <li class="flex items-start">
                <svg class="h-5 w-5 text-blue-500 mt-0.5 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
                <span>الحقول المميزة بعلامة "مطلوب" إلزامية ولا يمكن تركها فارغة</span>
            </li>
            <li class="flex items-start">
                <svg class="h-5 w-5 text-blue-500 mt-0.5 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
                <span>يجب التأكد من عدم تكرار الرقم الجامعي أو رقم الهوية</span>
            </li>
            <li class="flex items-start">
                <svg class="h-5 w-5 text-blue-500 mt-0.5 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
                <span>يتم حفظ البيانات عند الضغط على زر "حفظ"</span>
            </li>
            <li class="flex items-start">
                <svg class="h-5 w-5 text-blue-500 mt-0.5 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
                <span>يمكن إضافة دفعة جديدة من خلال زر "إضافة دفعة" إذا لم تكن موجودة</span>
            </li>
        </ul>
    </div>
    </div>

</div>

