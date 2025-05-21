<?php

namespace App\Providers;

use Dedoc\Scramble\Scramble;
use Dedoc\Scramble\Support\Generator\OpenApi;
use Dedoc\Scramble\Support\Generator\SecurityScheme;
use Dedoc\Scramble\Support\Generator\Server;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Schema::defaultStringLength(191);
        Scramble::configure()->withDocumentTransformers(function (OpenApi $openApi) {
             // Forcer l'URL de base ici :
        $openApi->servers = [
            new Server(
                url: 'https://0b2f-2c0f-2a80-3bc-b510-dd1-3fb7-9d2d-9e73.ngrok-free.app/api',
            ),
        ];
            $openApi->secure(
                SecurityScheme::http('bearer')
            );
        });

    }
}
