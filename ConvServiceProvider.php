<?php

namespace Conv;

use Conv\Domain\PortsIn\ConvServiceInterface;
use Conv\Domain\PortsOut\ConverterInterface;
use Conv\Domain\Services\ConvService;
use Conv\Infra\Converter;
use Illuminate\Support\ServiceProvider;

class ConvServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(ConvServiceInterface::class, ConvService::class);
        $this->app->bind(ConverterInterface::class, Converter::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
