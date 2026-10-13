<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>دليل استخدام صفحة إدارة الكليات</title>
    <style>
        body {
            font-family: 'Tahoma', Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 900px;
            margin: 0 auto;
            padding: 20px;
        }
        h1, h2, h3 {
            color: #2c5282;
        }
        .feature-box {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
        }
        .note {
            background-color: #fffaf0;
            border-right: 4px solid #f6ad55;
            padding: 10px;
            margin: 10px 0;
        }
        code {
            background-color: #edf2f7;
            padding: 2px 4px;
            border-radius: 4px;
            font-family: monospace;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
        }
        th, td {
            border: 1px solid #e2e8f0;
            padding: 8px 12px;
            text-align: right;
        }
        th {
            background-color: #f7fafc;
        }
    </style>
</head>
<body>

<h1>دليل استخدام صفحة إدارة الكليات</h1>

<div class="feature-box">
    <h2>وصف الصفحة</h2>
    <p>صفحة إدارة الكليات تسمح للمسؤولين بإضافة وتعديل الكليات في النظام. تحتوي الصفحة على حقول لإدخال المعلومات الأساسية للكلية وتحديد فترات التسجيل.</p>
</div>

<h2>أقسام الصفحة</h2>

<div class="feature-box">
    <h3>1. قسم المعلومات الأساسية</h3>
    <p>يحتوي هذا القسم على الحقول الأساسية للكلية:</p>

    <table>
        <tr>
            <th>الحقل</th>
            <th>الوصف</th>
            <th>متطلبات</th>
        </tr>
        <tr>
            <td>الجامعة</td>
            <td>اختيار الجامعة التابعة لها الكلية من القائمة المنسدلة</td>
            <td>مطلوب</td>
        </tr>
        <tr>
            <td>الكود</td>
            <td>كود فريد للكلية (أرقام فقط)</td>
            <td>مطلوب - 10 خانات كحد أقصى</td>
        </tr>
        <tr>
            <td>الاسم (عربي)</td>
            <td>اسم الكلية باللغة العربية</td>
            <td>مطلوب - 150 حرف كحد أقصى</td>
        </tr>
        <tr>
            <td>الاسم (إنجليزي)</td>
            <td>اسم الكلية باللغة الإنجليزية</td>
            <td>مطلوب - 150 حرف كحد أقصى</td>
        </tr>
        <tr>
            <td>الرابط (Slug)</td>
            <td>يتم توليده تلقائياً من الاسم العربي</td>
            <td>تلقائي</td>
        </tr>
    </table>
</div>

<div class="feature-box">
    <h3>2. قسم إعدادات التسجيل</h3>
    <p>يحتوي على إعدادات خاصة بفترة التسجيل في الكلية:</p>

    <table>
        <tr>
            <th>الحقل</th>
            <th>الوصف</th>
            <th>متطلبات</th>
        </tr>
        <tr>
            <td>الحالة</td>
            <td>تفعيل/إيقاف الكلية</td>
            <td>اختياري</td>
        </tr>
        <tr>
            <td>تاريخ بدء التسجيل</td>
            <td>تاريخ بدء قبول الطلبات</td>
            <td>مطلوب</td>
        </tr>
        <tr>
            <td>تاريخ انتهاء التسجيل</td>
            <td>تاريخ انتهاء قبول الطلبات</td>
            <td>مطلوب</td>
        </tr>
        <tr>
            <td>نشر النتائج</td>
            <td>تفعيل إذا كانت نتائج القبول جاهزة للنشر</td>
            <td>اختياري</td>
        </tr>
    </table>

    <div class="note">
        <strong>ملاحظة:</strong> يتم عرض التواريخ بتنسيق يوم/شهر/سنة (DD/MM/YYYY) ولكن عند الإدخال يتم استخدام شهر/يوم/سنة (MM/DD/YYYY).
    </div>
</div>

<div class="feature-box">
    <h3>3. قسم المعلومات الإضافية</h3>
    <p>يظهر هذا القسم عند تعديل كلية موجودة ويحتوي على:</p>
    <ul>
        <li>تاريخ الإنشاء: يعرض المدة منذ إنشاء السجل (مثال: "منذ 3 أيام")</li>
        <li>تاريخ التحديث: يعرض المدة منذ آخر تعديل على السجل</li>
    </ul>
</div>

<h2>إرشادات الاستخدام</h2>

<div class="feature-box">
    <h3>إضافة كلية جديدة</h3>
    <ol>
        <li>اضغط على زر "إضافة كلية جديدة"</li>
        <li>املأ الحقول المطلوبة في قسم المعلومات الأساسية</li>
        <li>حدد إعدادات التسجيل في القسم الثاني</li>
        <li>اضغط على زر "حفظ"</li>
    </ol>

    <div class="note">
        <strong>ملاحظة:</strong> سيتم التحقق تلقائياً من أن الكود واسم الكلية (عربي وإنجليزي) فريدين في النظام.
    </div>
</div>

<div class="feature-box">
    <h3>تعديل كلية موجودة</h3>
    <ol>
        <li>ابحث عن الكلية المطلوبة في قائمة الكليات</li>
        <li>اضغط على زر "تعديل"</li>
        <li>قم بالتعديلات المطلوبة</li>
        <li>اضغط على زر "حفظ"</li>
    </ol>
</div>

<div class="feature-box">
    <h3>إيقاف/تفعيل كلية</h3>
    <p>يمكنك التحكم في ظهور الكلية للطلاب من خلال تبديل زر "الحالة" في قسم إعدادات التسجيل:</p>
    <ul>
        <li><strong>مفعل:</strong> الكلية ظاهرة للطلاب ويمكن التسجيل فيها</li>
        <li><strong>معطل:</strong> الكلية غير ظاهرة للطلاب</li>
    </ul>
</div>

<div class="feature-box">
    <h3>نشر نتائج القبول</h3>
    <p>عندما تكون نتائج القبول جاهزة:</p>
    <ol>
        <li>اذهب إلى صفحة تعديل الكلية</li>
        <li>قم بتبديل خيار "نشر النتائج" إلى "نعم"</li>
        <li>احفظ التعديلات</li>
    </ol>
    <p>سيتمكن الطلاب الآن من رؤية نتائجهم للكلية.</p>
</div>

<h2>استكشاف الأخطاء وإصلاحها</h2>

<div class="feature-box">
    <h3>أخطاء شائعة</h3>
    <table>
        <tr>
            <th>الخطأ</th>
            <th>السبب المحتمل</th>
            <th>الحل</th>
        </tr>
        <tr>
            <td>الكود موجود مسبقاً</td>
            <td>محاولة استخدام كود كلية مسجل بالفعل</td>
            <td>استخدم كوداً فريداً للكلية</td>
        </tr>
        <tr>
            <td>الاسم موجود مسبقاً</td>
            <td>محاولة استخدام اسم كلية مسجل بالفعل (عربي أو إنجليزي)</td>
            <td>استخدم اسماً فريداً للكلية</td>
        </tr>
        <tr>
            <td>تاريخ الانتهاء قبل تاريخ البدء</td>
            <td>تاريخ انتهاء التسجيل أقل من تاريخ البدء</td>
            <td>تأكد من أن تاريخ الانتهاء بعد تاريخ البدء</td>
        </tr>
    </table>
</div>

</body>
</html>
