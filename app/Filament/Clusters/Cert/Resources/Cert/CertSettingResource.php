<?php

namespace App\Filament\Clusters\Cert\Resources\Cert;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\Reg\Faculty;
use Illuminate\Support\Str;
use App\Enums\CertSettingGroup;
use App\Filament\Clusters\Cert;
use App\Models\Cert\CertSetting;
use Filament\Resources\Resource;
use Filament\Forms\Components\Group;
use Illuminate\Support\Facades\Http;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use Stichoza\GoogleTranslate\GoogleTranslate;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Clusters\Cert\Resources\Cert\CertSettingResource\Pages;
use App\Filament\Clusters\Cert\Resources\Cert\CertSettingResource\RelationManagers;

class CertSettingResource extends Resource
{
    protected static ?string $model = CertSetting::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $cluster = Cert::class;
    protected static ?int $navigationSort = 2;

    protected static ?string $titles = 'cert.settings';
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function getEloquentQuery(): Builder
    {
        return static::getModel()::query()->orderBy('created_at', 'DESC');
    }

    public static function getNavigationGroup(): string
    {
        return __('main.certificate');
    }

    public static function getLabel(): ?string
    {
        return __(static::$titles);
    }

    public static function getPluralLabel(): ?string
    {
        return __(static::$titles);
    }

    public static function getNavigationLabel(): string
    {
        return ucfirst(__(static::$titles));
    }

    public static function shouldRegisterNavigation(): bool
    {
        return true;
    }

    // public static function canEdit(Model $record): bool
    // {
    //     return true;
    // }

    // public static function canDelete(Model $record): bool
    // {
    //     return true;
    // }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Main Information Section
                Section::make('')
                    ->schema([
                        Group::make([
                            TextInput::make('name_ar')
                                ->label(__('cert.name_ar'))
                                ->required()
                                ->live(onBlur: true)
                                ->afterStateUpdated(function (Set $set, ?string $state) {
                                    // Translate Arabic to English
                                    $translated = (new GoogleTranslate())->setSource('ar')->setTarget('en')->translate($state);
                                    $set('name_en', $translated);

                                    // Generate slug
                                    $set('slug', Str::slug($translated));
                                })
                                // ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug($state)))
                                ,
                            TextInput::make('name_en')
                                ->label(__('cert.name_en'))
                                ->required()
                                ->maxLength(255),
                            TextInput::make('slug')
                                ->label(__('cert.slug'))
                                ->required(),
                        ])->columns(3),
                    ]),

                // Relationships Section
                Section::make('')
                    // ->description('Certificate associations and hierarchy')
                    ->schema([
                        Group::make([

                            Select::make('groups')
                            ->label(__('cert.groups'))
                                ->options(
                                    fn () => collect(CertSettingGroup::cases())
                                        ->mapWithKeys(fn ($case) => [$case->value => $case->label()])
                                )
                                ->required()
                                ->live(),

                            Select::make('faculty_code')
                                ->label(__('cert.faculty'))
                                ->options(Faculty::pluck('name', 'code'))
                                ->required(fn (Get $get): bool => in_array(
                                    $get('groups'),
                                    ['degree', 'faculty_admin', 'special']
                                ))
                                ->hidden(fn (Get $get): bool => in_array(
                                    $get('groups'),
                                    ['gender', 'academic_year', 'admission_type', 'academic_affairs', 'stady']
                                )),
                                Select::make('parent_id')
                                ->label(__('cert.parent_id'))
                                ->options(CertSetting::pluck('name_ar', 'id'))
                                ->searchable()
                                ,
                                Toggle::make('is_visible')
                                ->label(__('cert.is_visible'))
                                ->default(true)
                                ->required(),

                        ])->columns(4),


                    ]),
            ]);
    }

    // Replace with your actual API logic
function translateArabicToEnglish(string $text): string
{
    $response = Http::withHeaders([
        'Authorization' => 'Bearer '.config('services.deepl.key'),
    ])->post('https://api.deepl.com/v2/translate', [
        'text' => $text,
        'source_lang' => 'AR',
        'target_lang' => 'EN',
    ]);

    return $response->json('translations.0.text') ?? Str::slug($text);
}
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name_ar')
                ->label(__('cert.name_ar'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('name_en')
                ->label(__('cert.name_en'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('groups')
                ->label(__('cert.groups'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('faculty.name')
                ->label(__('cert.faculty'))
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('parent.name_ar')
                    ->label(__('cert.parent_id'))
                    ->numeric()
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_visible')
                ->label(__('cert.is_visible'))
                    ->boolean(),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCertSettings::route('/'),
            'create' => Pages\CreateCertSetting::route('/create'),
            'edit' => Pages\EditCertSetting::route('/{record}/edit'),
        ];
    }
}
