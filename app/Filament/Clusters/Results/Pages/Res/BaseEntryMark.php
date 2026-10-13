<?php

namespace App\Filament\Clusters\Results\Pages\Res;

use Filament\Pages\Page;
use App\Models\Result\Grade;
use App\Enums\ShowAbsentCode;
use App\Models\Result\ResMark;
use App\Filament\Clusters\Results;
use App\Models\Result\CourseSubject;
use Filament\Support\Enums\MaxWidth;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Contracts\HasTable;
use Filament\Notifications\Notification;
use Illuminate\Contracts\Support\Htmlable;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Concerns\InteractsWithTable;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;


abstract class BaseEntryMark extends Page implements HasForms, HasTable
{
    use InteractsWithForms, InteractsWithTable;

    use HasPageShield;


    public bool $isFullScreen = false;
    protected $listeners = ['fullscreen-exited' => 'exitFullscreenState'];
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static string $view = 'filament.clusters.results.pages.res.entry-mark';
    protected static ?string $cluster = Results::class;
    protected static ?string $model = ResMark::class;
    protected static ?string $title = 'entry_mark';

    public $course, $subject, $faculty, $batch, $level, $semester, $absent;
    public $StudentCount = null;
    public $courseCode = null;
    public $subjectCode = false;


    public function exitFullscreenState()
    {
        $this->isFullScreen = false;
    }

    // public function getMaxContentWidth(): MaxWidth | string | null
    // {
    //     return MaxWidth::ScreenTwoExtraLarge;
    // }

    public function getTitle(): string | Htmlable
    {
        return __('res.entry_mark');
    }

    public static function getNavigationGroup(): string
    {
        return __('main.exam_entry');
    }

    public static function getLabel(): ?string
    {
        return __('res.' . static::$title);
    }

    public static function getPluralLabel(): ?string
    {
        return __('res.' . static::$title);
    }

    public static function getNavigationLabel(): string
    {
        return ucfirst(__('res.' . static::$title));
    }

    public static function shouldRegisterNavigation(): bool
    {
        return true;
    }


    public function StudentEntryFn()
    {
        $this->courseCode = $this->course;
        $this->subjectCode = $this->subject;
    }

    public function StudentCountFn($course = null)
    {
        if (!$course) {
            return 'No Students';
        }
        $count = 0;
        CourseSubject::where('course_code', $course)
            ->chunk(200, function ($courseSubjects) use (&$count) {
                foreach ($courseSubjects as $courseSubject) {
                    $count += $courseSubject->batch->students->count();
                }
            });

        return $count ?: 'No';
    }

    public function getCurrentAcademicYear(): string
    {
        return date('Y') . '-' . (date('Y') + 1);
    }




}
