<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\DataService;
use App\Services\DataServiceInterface;
use App\Services\CspResolverInterface;
use App\Services\CspResolver;

class DataServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->app->singleton(DataServiceInterface::class, DataService::class);
        $this->app->singleton(CspResolverInterface::class, CspResolver::class);
    }
}
