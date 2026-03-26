<?php

namespace The3LabsTeam\NovaRedirectorSeo\Tests;

use Illuminate\Support\Facades\Schema;
use Orchestra\Testbench\TestCase as Orchestra;
use The3LabsTeam\NovaRedirectorSeo\App\Http\Middleware\NovaRedirectorSeoMiddleware;
use The3LabsTeam\NovaRedirectorSeo\NovaRedirectorSeoServiceProvider;

abstract class TestCase extends Orchestra
{
    protected function getPackageProviders($app): array
    {
        return [
            NovaRedirectorSeoServiceProvider::class,
        ];
    }

    protected function defineRoutes($router): void
    {
        $router->middleware(NovaRedirectorSeoMiddleware::class)
            ->any('/{path?}', static fn () => response('fallback-response'))
            ->where('path', '.*');
    }

    protected function defineDatabaseMigrations(): void
    {
        $migration = include __DIR__.'/../database/migrations/create_nova-redirector-seo_table.php.stub';

        $migration->up();
    }

    protected function destroyDatabaseMigrations(): void
    {
        Schema::dropIfExists('nova_redirector_seo');
    }

    public function getEnvironmentSetUp($app): void
    {
        $app['config']->set('database.default', 'testing');
        $app['config']->set('database.connections.testing', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
            'foreign_key_constraints' => true,
        ]);
        $app['config']->set('cache.default', 'array');

        parent::getEnvironmentSetUp($app);
    }
}
