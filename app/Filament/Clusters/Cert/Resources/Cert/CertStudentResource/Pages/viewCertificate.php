<?php

namespace App\Filament\Clusters\Cert\Resources\Cert\CertStudentResource\Pages;

use App\Filament\Clusters\Cert\Resources\Cert\CertStudentResource;
use App\Models\Cert\CertStudent;
use App\Models\Result\ResMark;
use App\Models\Result\ResRecom;
use Filament\Resources\Pages\Page;
use Illuminate\Database\Eloquent\Model;

class ViewCertificate extends Page
{
    protected static string $resource = CertStudentResource::class;

    protected static string $view = 'filament.resources.cert-student-resource.pages.view-certificate';

    public CertStudent $record;

    public $marksByYear;
    public $recommendations;

    public function mount(int | string $record): void
    {
        $this->record = CertStudent::findOrFail($record);
        $this->loadStudentData();
    }

    protected function loadStudentData(): void
    {
        // Fetch marks and group them by academic year (assuming batch relates to year)
        // This logic might need adjustment based on your exact schema for linking marks to years
        $this->marksByYear = ResMark::where('university_no', $this->record->universityno)
            ->with('subject', 'batch') // Eager load relationships
            ->get()
            ->groupBy('batch.name'); // Group by batch name (e.g., "2019/2020")

        // Fetch recommendations
        $this->recommendations = ResRecom::where('university_no', $this->record->universityno)
            ->with('recomName', 'level', 'batch') // Eager load relationships
            ->get();
    }
}
