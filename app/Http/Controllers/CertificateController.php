<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cert\CertSetting;
use App\Services\CertificateService;
use Illuminate\Support\Facades\View;
use App\Services\CertificateStyleService;

class CertificateController extends Controller
{
    private const SIGNATORY_GROUPS = [
        'dean_faculty',
        'registrar_faculty',
        'academic_affairs',
    ];

    public function __construct(
        private readonly CertificateService $certificateService
    ) {}

    /**
     * Generate main certificate view
     */
    public function generate(Request $request, string $slug)
    {
        $certificate = $this->certificateService->getCertificateForView($slug);
        $signatories = $this->getSignatoryGroups();

        $locale = $request->query('lang', 'en');
        app()->setLocale($locale);

        $styles = CertificateStyleService::getStyles('graduation');
        $css = CertificateStyleService::css($styles, $locale);


        return View::make('cert.template', [
            'certificate'  => $certificate,
            'signatories'  => $signatories,
            'today'        => now()->locale($locale)->isoFormat('MMMM Do, YYYY'),
            'lang'       => $locale,
            'alumiaDate'   => $this->certificateService->formatAlumiaDate($certificate->alumia_date, $locale),
            'hijriDate'    => $this->certificateService->formatHijriDate($certificate->hijri_date),
            'verifyUrl'    => url("/verify/{$certificate->verify_hash}"),
            'css'          => $css,
        ]);
    }

    /**
     * Generate academic transcript (HTML view - PDF commented)
     */
    public function generate_details(string $universityNo, string $lang = 'ar')
    {
        $transcriptData = $this->certificateService->generateTranscript($universityNo, $lang);

        $styles = CertificateStyleService::getStyles('transcript');
        $css = CertificateStyleService::cssTranscript($styles, $lang);
        $transcriptData['css'] = $css;

       
        if (isset($transcriptData['error'])) {
            return response()->json(['error' => $transcriptData['error']], 404);
        }

        // Optional: PDF generation (uncomment when ready)
        /*
        Pdf::setOptions([
            'dpi'         => 150,
            'defaultFont' => 'DejaVuSans',
            'tempDir'     => storage_path('temp'),
        ]);

        return Pdf::loadView('cert.transcript', compact('transcriptData'))
            ->download("transcript_{$universityNo}.pdf");
        */

        return view('cert.transcript', compact('transcriptData'));
    }

    /**
     * Print result page
     */
    public function PrintResult(Request $request, array $data)
    {
        return view('print-result', ['data' => $data]);
    }

    /**
     * Generate affidavit view
     */
    public function affidavit(Request $request, string $slug)
    {
        $certificate = $this->certificateService->getCertificateForView($slug);

        $signatories = $this->getSignatoryGroups();

        $locale = $request->query('lang', 'en');
        app()->setLocale($locale);

        $alumiaDate = $certificate->alumia_date;

        return View::make('cert.affidavit', [
            'certificate'         => $certificate,
            'signatories'         => $signatories,
            'today'               => now()->locale($locale)->isoFormat('MMMM Do, YYYY'),
            'lang'              => $locale,
            'alumiaDate'          => $this->certificateService->formatAlumiaDate($alumiaDate, $locale),
            'hijriDate'           => $this->certificateService->formatHijriDate($certificate->hijri_date),
            'affidavit_date_ar'   => $alumiaDate?->locale('ar')->translatedFormat('F Y'),
            'affidavit_date_en'   => $alumiaDate?->locale('en')->translatedFormat('F Y'),
        ]);
    }

    // ────────────────────────────────────────────────────────────────
    //  Private Helper Methods
    // ────────────────────────────────────────────────────────────────

    private function getSignatoryGroups(): \Illuminate\Support\Collection
    {
        return CertSetting::query()
            ->where('is_visible', true)
            ->whereIn('groups', self::SIGNATORY_GROUPS)
            ->get()
            ->groupBy('groups');
    }
}