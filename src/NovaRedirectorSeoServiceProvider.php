<?php

namespace The3LabsTeam\NovaRedirectorSeo;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use The3LabsTeam\NovaRedirectorSeo\Commands\NovaRedirectorSeoCommand;

class NovaRedirectorSeoServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('nova-redirector-seo')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigration('create_nova-redirector-seo_table')
            ->hasCommand(NovaRedirectorSeoCommand::class);
    }
}
