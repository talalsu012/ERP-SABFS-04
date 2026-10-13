<?php

namespace App\Providers;

use Filament\Facades\Filament;
use App\Services\ResultService;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use App\Filament\Resources\UserResource;
use BezhanSalleh\FilamentLanguageSwitch\LanguageSwitch;
use App\Models\User;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(ResultService::class, function () {
            return new ResultService();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        LanguageSwitch::configureUsing(function (LanguageSwitch $switch) {
            $switch
                ->locales(['ar','en','fr']); // also accepts a closure
        });

     /*    Gate::guessPolicyNamesUsing(function (string $modelClass) {
            return str_replace('Models', 'Policies', $modelClass) . 'Policy';
        }); */
		
		/* Gate::before(function (User $user, string $ability) {
    return $user->isSuperAdmin() ? true: null;
});
 */
        Filament::serving(function () {
            Filament::registerResources([
                UserResource::class,
            ]);
        });
    }
}
