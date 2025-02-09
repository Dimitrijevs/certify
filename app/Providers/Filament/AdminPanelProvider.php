<?php

namespace App\Providers\Filament;

use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Facades\Filament;
use Filament\Navigation\MenuItem;
use Illuminate\Support\Facades\Auth;
use App\Filament\Pages\Auth\Register;
use Filament\Navigation\NavigationItem;
use Filament\Navigation\NavigationGroup;
use Filament\Http\Middleware\Authenticate;
use Jeffgreco13\FilamentBreezy\BreezyCore;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Cookie\Middleware\EncryptCookies;
use App\Filament\Widgets\CertificateRequirements;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('app')
            ->path('app')
            ->login()
            ->font('Playfair Display')
            ->registration(Register::class)
            ->sidebarFullyCollapsibleOnDesktop()
            ->viteTheme('resources/css/filament/app/theme.css')
            ->topNavigation()
            ->userMenuItems([
                'profile' => MenuItem::make()->url(fn(): string => '/app/users/' . Auth::id() . '/edit')
            ])
            ->plugins([
                BreezyCore::make()
                    ->myProfile()
                    ->enableTwoFactorAuthentication(
                        force: false,
                    ),
            ])
            ->colors([
                'primary' => '#3B81F6',
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->discoverClusters(in: app_path('Filament/Clusters'), for: 'App\\Filament\\Clusters')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                CertificateRequirements::class,
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
            ]);
    }

    public function boot(): void
    {
        Filament::serving(function () {
            Filament::registerNavigationGroups([
                'People' => NavigationGroup::make()
                    ->label(__('other.people'))
                    ->icon('tabler-users-group'),
                'Learning' => NavigationGroup::make()
                    ->label(__('learning/learningCategory.group_label'))
                    ->icon('tabler-book'),
                'Additional' => NavigationGroup::make()
                    ->label(__('other.additional'))
                    ->icon('tabler-adjustments-plus'),
            ]);

            Filament::registerNavigationItems([
                NavigationItem::make('log_out')
                    ->label(__('auth.logout'))
                    ->sort(999)
                    ->url(fn(): string => route('landingpage'))
                    ->group('Additional'),
            ]);
        });
    }
}
