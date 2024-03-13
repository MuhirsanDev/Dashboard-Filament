<?php

namespace App\Providers;

use App\Filament\Resources\UserResource;
use App\Models\User;
use Illuminate\Support\ServiceProvider;
use Filament\Facades\Filament;
use Filament\FilamentManager;
use Filament\Navigation\NavigationBuilder;
use Filament\Navigation\NavigationGroup;
use Filament\Navigation\NavigationItem;
use Filament\Navigation\UserMenuItem;
use Illuminate\Support\Facades\Auth;

class FilamentServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {


                Filament::serving(function () {
                    // Using Vite
                    Filament::registerViteTheme('resources/css/filament.css');


                    if(Auth::check() && Auth::user()->hasRole('admin')){
                        Filament::registerUserMenuItems([
                            UserMenuItem::make()
                                ->label('Settings')
                                ->url(UserResource::getUrl())
                                ->icon('heroicon-s-cog'),
                            // ...
                        ]);
                    }
                });

                // Filament::navigation(function (NavigationBuilder $builder): NavigationBuilder {
                //     return $builder->items([
                //         NavigationItem::make('User')
                //             ->icon('heroicon-o-home')
                //             ->activeIcon('heroicon-s-home')
                //             ->isActiveWhen(fn (): bool => request()->routeIs('filament.pages.dashboard'))
                //             ->url(route('filament.resources.users.index')),
                //     ]);
                // });


    }
}
