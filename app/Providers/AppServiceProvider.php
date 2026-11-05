<?php

namespace App\Providers;

use Dedoc\Scramble\Scramble;
use Dedoc\Scramble\Support\Generator\OpenApi;
use Dedoc\Scramble\Support\Generator\SecurityScheme;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Limitar los intentos de inicio de sesión
        RateLimiter::for('login', function (Request $request) {
            return Limit::perMinute(5)
                ->by(
                    Str::lower((string) $request->input('email'))
                    .'|'.$request->ip()
                )
                ->response(
                    fn (Request $request, array $headers) => response()->json([
                        'message' => 'Demasiados intentos de inicio de sesión. Espera un minuto.',
                    ], 429, $headers)
                );
        });

        // Configurar autenticación Bearer en la documentación API
        Scramble::configure()
            ->withDocumentTransformers(function (OpenApi $openApi) {
                $openApi->secure(SecurityScheme::http('bearer'));
            });
    }
}
