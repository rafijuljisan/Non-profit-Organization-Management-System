<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets\AccountWidget;
use Filament\Widgets\FilamentInfoWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            
            // 🎨 ১. থিম কালার আপডেট করা হলো (এনজিওর জন্য স্নিগ্ধ সবুজ/Emerald)
            ->colors([
                'primary' => Color::Emerald,
                'danger' => Color::Rose,
                'gray' => Color::Zinc,
                'info' => Color::Blue,
                'success' => Color::Emerald,
                'warning' => Color::Orange,
            ])
            
            // 🔤 ২. আধুনিক ফন্ট যুক্ত করা হলো
            ->font('Poppins')
            
            // 🏷️ ৩. ব্র্যান্ডিং: নাম, লোগো এবং ফেভিকন
            ->brandName('Foundation Management System') // আপনার এনজিওর নাম দিতে পারেন
            ->brandLogo(asset('images/logo.png'))       // public/images/ ফোল্ডারে logo.png রাখুন
            ->brandLogoHeight('3rem')
            ->favicon(asset('images/favicon.png'))      // public/images/ ফোল্ডারে favicon.png রাখুন
            
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            
            // ✅ আপনার ম্যানুয়াল রিসোর্সগুলো ঠিক রাখা হয়েছে
            ->resources([
                \App\Filament\Resources\Users\UserResource::class,
                \App\Filament\Resources\Pages\PageResource::class,
                \App\Filament\Resources\ActivityLogResource::class, 
            ])
            
            // ✅ আপনার ম্যানুয়াল উইজেটগুলো ঠিক রাখা হয়েছে
            ->widgets([
                    //AccountWidget::class,
                    //FilamentInfoWidget::class,
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
}