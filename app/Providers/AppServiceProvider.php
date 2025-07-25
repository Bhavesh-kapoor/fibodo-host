<?php

namespace App\Providers;

use App\Exceptions\Handler;
use Dedoc\Scramble\Scramble;
use Dedoc\Scramble\Support\Generator\OpenApi;
use Dedoc\Scramble\Support\Generator\SecurityScheme;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(
            \Illuminate\Contracts\Debug\ExceptionHandler::class,
            Handler::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // prevent N+1 prob
        Model::preventLazyLoading();

        Scramble::configure()
            ->withDocumentTransformers(function (OpenApi $openApi) {
                $openApi->secure(
                    SecurityScheme::http('bearer')
                );
            });

        Scramble::configure()
            ->expose(
                ui: '/docs/v1/api',
                document: '/docs/v1/openapi.json',
            );
    }
}
