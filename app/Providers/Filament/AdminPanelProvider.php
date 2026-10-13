<?php

namespace App\Providers\Filament;

use Althinect\FilamentSpatieRolesPermissions\FilamentSpatieRolesPermissionsPlugin;

use Filament\Pages;
use Filament\Panel;
use Filament\Widgets;
use Filament\PanelProvider;
use App\Filament\Clusters\Cert;
use Filament\Navigation\MenuItem;
use Filament\Support\Colors\Color;
use Filament\Http\Middleware\Authenticate;
use App\Filament\Widgets\Reg\StatusFaculty;
use App\Filament\Widgets\Reg\PaymentStatistics;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Filament\Http\Middleware\AuthenticateSession;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use BezhanSalleh\FilamentShield\FilamentShieldPlugin;


class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('')
            ->login()
            ->registration()
            ->favicon(asset('static/favicon.png'))
            ->brandLogo(fn () => view('filament.logo'))
            ->colors([
                // 'primary' => Color::Emerald,
                // 'danger' => Color::Rose,
                'primary' => Color::hex('#7e22ce'),
            ])
            ->databaseNotifications()
            ->topNavigation()
            ->sidebarFullyCollapsibleOnDesktop()
            ->topbar(true)
            ->sidebarCollapsibleOnDesktop()
            ->collapsedSidebarWidth('9rem')
            ->sidebarWidth('15rem')
            // ->topNavigation()
            ->userMenuItems([
                MenuItem::make()
                    ->label('Certificate')
                    ->url(fn (): string => Cert::getUrl())
                    ->icon('heroicon-o-cog-6-tooth'),
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->discoverClusters(in: app_path('Filament/Clusters'), for: 'App\\Filament\\Clusters')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                // Widgets\AccountWidget::class,
                StatusFaculty::class,
                PaymentStatistics::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->plugins([
                 FilamentShieldPlugin::make()
                    ->gridColumns([
                        'default' => 1,
                        'sm' => 2,
                        'lg' => 3
                    ])
                    ->sectionColumnSpan(1)
                    ->checkboxListColumns([
                        'default' => 1,
                        'sm' => 2,
                        'lg' => 4,
                    ])
                    ->resourceCheckboxListColumns([
                        'default' => 1,
                        'sm' => 2,
                    ]),
				//FilamentSpatieRolesPermissionsPlugin::make()
            ]);
    }
}
