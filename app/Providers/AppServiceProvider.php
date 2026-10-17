<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // @hasPermission('master-data.vehicle-types.create')
        Blade::directive('hasPermission', function (string $expression) {
            return "<?php if(auth()->check() && auth()->user()->hasPermission({$expression})): ?>";
        });

        Blade::directive('endhasPermission', function () {
            return '<?php endif; ?>';
        });

        // @hasAccessToMenu('master-data.vehicle-types')
        Blade::directive('hasAccessToMenu', function (string $expression) {
            return "<?php if(auth()->check() && auth()->user()->hasAccessToMenu({$expression})): ?>";
        });

        Blade::directive('endhasAccessToMenu', function () {
            return '<?php endif; ?>';
        });

        // @isSuperAdmin
        Blade::directive('isSuperAdmin', function () {
            return '<?php if(auth()->check() && auth()->user()->isSuperAdmin()): ?>';
        });

        Blade::directive('endisSuperAdmin', function () {
            return '<?php endif; ?>';
        });
    }
}