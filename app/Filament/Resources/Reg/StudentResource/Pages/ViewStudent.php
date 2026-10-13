<?php

namespace App\Filament\Resources\Reg\StudentResource\Pages;

use Filament\Actions;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\Card;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ImageEntry;
use App\Filament\Resources\Reg\StudentResource;

class ViewStudent extends ViewRecord
{
    protected static string $resource = StudentResource::class;

    public  function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Card::make()->schema([

                Textentry::make('university_no')
                ->label(__('reg.university_no'))
                ->size(TextEntry\TextEntrySize::Large)
                ->color('danger'),

                Textentry::make('name_ar')
                ->size(TextEntry\TextEntrySize::Large)
                ->label(''),

                ImageEntry::make('photo')
                ->label('')
                    ->defaultImageUrl(url('/images/face.png'))
                    ->height(80)
                    ->square(),

                ])->columns(3),

                Section::make(__('reg.basic_info'))
                    // ->description(__('reg.basic_info_desc'))
                    ->icon('heroicon-m-shopping-bag')
                    ->schema([
                Textentry::make('name_en')->label(__('reg.name_en')),
                Textentry::make('rstatus.name')->label(__('reg.rstatus')),
                Textentry::make('faculty.campus.name')->label(__('reg.campus')),
                Textentry::make('faculty.name')->label(__('reg.faculty')),
                Textentry::make('department.name')->label(__('reg.department')),
                Textentry::make('special.name')->label(__('reg.special')),
                Textentry::make('level.name')->label(__('reg.level')),
                Textentry::make('admission.name')->label(__('reg.admission')),
                Textentry::make('stady.name')->label(__('reg.stady')),
                Textentry::make('academicyear.name')->label(__('reg.academic_year')),
                Textentry::make('batch.name')->label(__('reg.batch')),
                Textentry::make('x_status.name')->label(__('reg.xstatus')),
                    ])
                    ->collapsed()
                    ->columns(4),

                    Section::make(__('reg.personal_info'))
                    // ->description(__('reg.personal_info'))
                    ->icon('heroicon-m-shopping-bag')
                    ->schema([
                    Textentry::make('nationalty.name')->label(__('reg.nationality')),
                    Textentry::make('gender.name')->label(__('reg.gender')),
                    Textentry::make('birth_place')->label(__('reg.birth_place')),
                    Textentry::make('birth_date')->label(__('reg.birth_date')),
                    Textentry::make('state.name')->label(__('reg.state')),
                    Textentry::make('city')->label(__('reg.city')),
                    Textentry::make('localty')->label(__('reg.localty')),
                    Textentry::make('block')->label(__('reg.block')),
                    Textentry::make('home_no')->label(__('reg.homeno')),
                    Textentry::make('email')->label(__('reg.email')),
                    Textentry::make('mobile')->label(__('reg.phone')),
                    Textentry::make('social_media')->label(__('reg.social_media')),
                    Textentry::make('activites')->label(__('reg.activites')),
                    Textentry::make('skill')->label(__('reg.skill')),
                    Textentry::make('parent')->label(__('reg.guardian_name')),
                    Textentry::make('parent_alive')->label(__('reg.father_alive')),
                    Textentry::make('parent_job')->label(__('reg.parent_job')),
                    Textentry::make('parent_address')->label(__('reg.parent_address')),
                    Textentry::make('parent_contacts')->label(__('reg.parent_contacts')),
                    Textentry::make('mother')->label(__('reg.mother_name')),
                    Textentry::make('mother_alive')->label(__('reg.mother_alive')),
                    Textentry::make('mother_job')->label(__('reg.mother_job')),
                    Textentry::make('mother_address')->label(__('reg.mother_address')),
                    Textentry::make('mother_contacts')->label(__('reg.mother_contacts')),
                    Textentry::make('brothers')->label(__('reg.brothers')),
                    ])
                    ->collapsed()
                    ->columns(4),


                    Section::make(__('reg.fees'))
                    // ->description(__('reg.fees'))
                    ->icon('heroicon-m-shopping-bag')
                    ->schema([


                        Textentry::make('discount_percent.name')->label(__('reg.discount_percent')),
                    Textentry::make('all_fees')->label(__('reg.all_fees')),
                    Textentry::make('pay')->label(__('reg.payment')),
                    Textentry::make('xmod')->label(__('reg.xmod')),

                    Textentry::make('fees')->label(__('reg.fees')),
                    Textentry::make('reg_fees')->label(__('reg.regfees')),
                    Textentry::make('currency')->label(__('reg.currency')),
                    Textentry::make('installment.name')->label(__('reg.Installments')),

                    Textentry::make('fees1')->label(__('reg.fees1')),
                    Textentry::make('fees2')->label(__('reg.fees2')),
                    Textentry::make('fees3')->label(__('reg.fees3')),
                    Textentry::make('fees6')->label(__('reg.fees6')),
                    Textentry::make('fees7')->label(__('reg.fees7')),
                    Textentry::make('fees8')->label(__('reg.fees8')),


                    Textentry::make('pay1')->label(__('reg.pay1')),
                    Textentry::make('pay2')->label(__('reg.pay2')),
                    Textentry::make('pay3')->label(__('reg.pay3')),
                    Textentry::make('pay4')->label(__('reg.pay4')),





                    ])
                    ->collapsed()
                    ->columns(6),

                    Section::make(__('reg.personal_info_student'))
                    // ->description(__('reg.personal_info_student'))
                    ->icon('heroicon-m-shopping-bag')
                    ->schema([
                    Textentry::make('StudentAccount.nationalty.name')->label(__('reg.nationality')),
                    Textentry::make('StudentAccount.gender.name')->label(__('reg.gender')),
                    Textentry::make('StudentAccount.birth_place')->label(__('reg.birth_place')),
                    Textentry::make('StudentAccount.birth_date')->label(__('reg.birth_date')),
                    Textentry::make('StudentAccount.state.name')->label(__('reg.state')),
                    Textentry::make('StudentAccount.city')->label(__('reg.city')),
                    Textentry::make('StudentAccount.localty')->label(__('reg.localty')),
                    Textentry::make('StudentAccount.block')->label(__('reg.block')),
                    Textentry::make('StudentAccount.home_no')->label(__('reg.homeno')),
                    Textentry::make('StudentAccount.email')->label(__('reg.email')),
                    Textentry::make('StudentAccount.mobile')->label(__('reg.phone')),
                    Textentry::make('StudentAccount.social_media')->label(__('reg.social_media')),
                    Textentry::make('StudentAccount.activites')->label(__('reg.activites')),
                    Textentry::make('StudentAccount.skill')->label(__('reg.skill')),
                    Textentry::make('StudentAccount.parent')->label(__('reg.guardian_name')),
                    Textentry::make('StudentAccount.parent_alive')->label(__('reg.father_alive')),
                    Textentry::make('StudentAccount.parent_job')->label(__('reg.parent_job')),
                    Textentry::make('StudentAccount.parent_address')->label(__('reg.parent_address')),
                    Textentry::make('StudentAccount.parent_contacts')->label(__('reg.parent_contacts')),
                    Textentry::make('StudentAccount.mother')->label(__('reg.mother_name')),
                    Textentry::make('StudentAccount.mother_alive')->label(__('reg.mother_alive')),
                    Textentry::make('StudentAccount.mother_job')->label(__('reg.mother_job')),
                    Textentry::make('StudentAccount.mother_address')->label(__('reg.mother_address')),
                    Textentry::make('StudentAccount.mother_contacts')->label(__('reg.mother_contacts')),
                    Textentry::make('StudentAccount.brothers')->label(__('reg.brothers')),
                    ])
                    ->collapsed()
                    ->columns(4),


            ]);
    }
}
