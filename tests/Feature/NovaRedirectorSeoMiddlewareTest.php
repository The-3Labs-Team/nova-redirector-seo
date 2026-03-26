<?php

namespace The3LabsTeam\NovaRedirectorSeo\Tests\Feature;

use The3LabsTeam\NovaRedirectorSeo\App\Models\NovaRedirectorSeo;
use The3LabsTeam\NovaRedirectorSeo\Tests\TestCase;

class NovaRedirectorSeoMiddlewareTest extends TestCase
{
    public function test_it_redirects_exact_matches(): void
    {
        NovaRedirectorSeo::query()->create([
            'from_url' => 'old-page',
            'to_url' => '/new-page',
            'status_code' => 301,
            'enabled' => true,
            'is_regex' => false,
        ]);

        $response = $this->get('/old-page');

        $response->assertRedirect('/new-page');
        $this->assertSame(301, $response->getStatusCode());
    }

    public function test_it_expands_regex_matches(): void
    {
        NovaRedirectorSeo::query()->create([
            'from_url' => 'posts\/(.*)',
            'to_url' => '/articles/$1',
            'status_code' => 302,
            'enabled' => true,
            'is_regex' => true,
        ]);

        $response = $this->get('/posts/hello-world');

        $response->assertRedirect('/articles/hello-world');
        $this->assertSame(302, $response->getStatusCode());
    }

    public function test_it_invalidates_the_cached_redirect_when_the_rule_changes(): void
    {
        $redirect = NovaRedirectorSeo::query()->create([
            'from_url' => 'cached-page',
            'to_url' => '/first-destination',
            'status_code' => 301,
            'enabled' => true,
            'is_regex' => false,
        ]);

        $this->get('/cached-page')->assertRedirect('/first-destination');
        $this->assertNotNull(cache()->get(NovaRedirectorSeo::cacheKey('cached-page')));

        $redirect->update([
            'to_url' => '/second-destination',
        ]);

        $this->get('/cached-page')->assertRedirect('/second-destination');
    }
}
