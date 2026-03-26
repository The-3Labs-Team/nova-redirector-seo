<?php

namespace The3LabsTeam\NovaRedirectorSeo\Tests\Feature;

use Illuminate\Support\Facades\Schema;
use The3LabsTeam\NovaRedirectorSeo\NovaRedirectorSeoServiceProvider;
use The3LabsTeam\NovaRedirectorSeo\Tests\TestCase;

class PackageBootTest extends TestCase
{
    public function test_it_loads_the_package_defaults(): void
    {
        $this->assertTrue(app()->getLoadedProviders()[NovaRedirectorSeoServiceProvider::class] ?? false);
        $this->assertSame(60 * 60 * 24 * 7, config('nova-redirector-seo.cache.ttl'));
        $this->assertTrue(Schema::hasTable('nova_redirector_seo'));
    }
}
