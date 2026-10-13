<!DOCTYPE html>
<html lang="{{ $lang }}" dir="{{ $lang === 'ar' ? 'rtl' : 'ltr' }}">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>{{ $lang === 'ar' ? 'شهادة تخرج' : 'Graduation Certificate' }}</title>
    <style>
        {!! $css !!}
    </style>
</head>

<body>
    <div class="certificate-container">

        <div class="student-no">
            {{ $lang === 'ar' ? 'رقم الطالب:' : 'Student No:' }} {{ $certificate->universityno }}
        </div>

        <div class="header-section">
            <div class="cert-title-main">
                {{ $lang === 'ar' ? 'شـــهــادة' : 'CERTIFICATE' }}
            </div>
            
        </div>

        <div class="content">
            <p>
                {{ $lang === 'ar' ? 'نشهد نحن الموقعين أدناه أن' : 'This is to certify that' }}
            </p>
            <div class="student-name">
                {{ $certificate->getNameAttribute() }}
            </div>
            <p>
                ({{ $lang === 'ar' ? 'الجنسية: '.$certificate->nationalty?->name : 'Nationality: '.$certificate->nationalty?->name_en }})
            </p>
            
            <p>
                @if($lang === 'ar')
                    قد اجتاز{{ $certificate->gender_code == 10010002 ? 'ت' : '' }} الامتحان النهائي و مُنح{{ $certificate->gender_code == 10010002 ? 'ت' : '' }} درجة
                    <br>
                    <span class="degree-details">{{ $certificate->degree?->name_ar }}</span>
                    في 
                    <span class="degree-details">{{ $certificate->faculty?->name_ar }} {{ $certificate->admission?->name_ar }}</span>
                    <br>
                    بتقدير <span class="degree-details">{{ $certificate->grade?->name_ar }}</span>
                @else
                    having completed the prescribed course of study and passed the final examination, 
                    was awarded the Degree of
                    <br>
                    <span class="degree-details">{{ $certificate->degree?->name_en }}</span>
                    in 
                    <span class="degree-details">{{ $certificate->faculty?->name_en }} {{ $certificate->admission?->name_en }}</span>
                    <br>
                    with grade <span class="degree-details">{{ $certificate->grade?->name_en }}</span>
                @endif
            </p>

            <table class="date-table">
                <tr><td><b>{!! $hijriDate[$lang] !!}</b></td></tr>
                <tr><td><b>{!! $alumiaDate[$lang] !!}</b></td></tr>
                <tr>
                    <td>
                        <p>{{ $lang === 'ar' ? 'وذلك بموافقة المجلس العلمي للأكاديمية' : 'By authority of the Academic Council' }}</p>
                    </td>
                </tr>
            </table>
        </div>

        <table class="signatures-section">
            <tr>
                <td>
                    @if(isset($signatories['registrar_faculty']))
                        @foreach($signatories['registrar_faculty'] as $item)
                            <strong>{{ $lang === 'ar' ? $item->name_ar : $item->name_en }}</strong><br>
                            {{ $lang === 'ar' ? 'المسجل' : 'Registrar' }}
                        @endforeach
                    @endif
                </td>
                <td>
                    @if(isset($signatories['academic_affairs']))
                        @foreach($signatories['academic_affairs'] as $item)
                            <strong>{{ $lang === 'ar' ? $item->name_ar : $item->name_en }}</strong><br>
                            {{ $lang === 'ar' ? 'أمين الشئون العلمية' : 'Secretary for Academic Affairs' }}
                        @endforeach
                    @endif
                </td>
                <td>
                    @if(isset($signatories['dean_faculty']))
                        @foreach($signatories['dean_faculty'] as $item)
                            <strong>{{ $lang === 'ar' ? $item->name_ar : $item->name_en }}</strong><br>
                            {{ $lang === 'ar' ? 'عميد الأكاديمية' : 'The Dean of the Academy' }}
                        @endforeach
                    @endif
                </td>
            </tr>
        </table>

        <div class="issued-date-footer">
            {{ $lang === 'ar' ? 'تحريراً في :' : 'Issued on:' }} 
            {{ \Carbon\Carbon::now()->locale($lang)->translatedFormat('j F Y') }}
        </div>

<div class="qrcode-container">
                {!! QrCode::size(110)->generate(
                    route('certificate.verify', $certificate->verify_hash)
                ) !!}
                <div class="qr-label">
                    {{ $lang === 'ar' ? 'امسح للتحقق من الشهادة' : 'Scan to verify certificate' }}
                </div>
        </div>
        
       


    </div>
</body>
</html>