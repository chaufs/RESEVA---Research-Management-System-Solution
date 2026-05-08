<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL; // <-- 1. Add this import
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton('App\Helpers\FileHelper', function ($app) {
            return new \App\Helpers\FileHelper();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Helper function to check if a file can be viewed inline in browser
        \Blade::directive('canViewFile', function ($filePath) {
            return "<?php echo app('App\\Helpers\\FileHelper')->canViewInline($filePath); ?>";
        });
        if (env('APP_ENV') !== 'local') {
            URL::forceScheme('https');
    }
}
