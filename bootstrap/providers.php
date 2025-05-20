<?php

return [
    App\Providers\AppServiceProvider::class,
    App\Providers\Filament\AdminPanelProvider::class,
    App\Providers\HorizonServiceProvider::class,
    App\Providers\MixinServiceProvider::class,
    App\Providers\TelescopeServiceProvider::class,
    Laravel\Sanctum\SanctumServiceProvider::class,
    App\Providers\SanctumRefreshServiceProvider::class,
    App\Providers\ScrambleServiceProvider::class,
];
