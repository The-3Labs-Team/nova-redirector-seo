<?php

namespace The3LabsTeam\NovaRedirectorSeo\Tests\Feature;

use Illuminate\Support\Facades\DB;
use The3LabsTeam\NovaRedirectorSeo\App\Models\NovaRedirectorSeo;
use The3LabsTeam\NovaRedirectorSeo\Tests\TestCase;

class RuleCacheTest extends TestCase
{
    public function test_it_reads_the_rules_once_for_paths_that_do_not_match(): void
    {
        $this->createRule('old-page', '/new-page');

        $this->get('/first-unmatched-path')->assertSee('fallback-response');

        $this->get('/second-unmatched-path')->assertSee('fallback-response');
        $this->get('/third-unmatched-path')->assertSee('fallback-response');

        $this->assertSame(0, $this->countRuleQueries(fn () => $this->get('/fourth-unmatched-path')));
    }

    public function test_it_reads_the_rules_once_for_paths_that_match(): void
    {
        $this->createRule('old-page', '/new-page');

        $this->get('/old-page')->assertRedirect('/new-page');

        $this->assertSame(0, $this->countRuleQueries(fn () => $this->get('/old-page')));
    }

    public function test_it_invalidates_the_cache_when_a_rule_changes(): void
    {
        $rule = $this->createRule('cached-page', '/first-destination');

        $this->get('/cached-page')->assertRedirect('/first-destination');
        $this->assertNotNull(cache()->get(NovaRedirectorSeo::cacheKey()));

        $rule->update(['to_url' => '/second-destination']);

        $this->assertNull(cache()->get(NovaRedirectorSeo::cacheKey()));
        $this->get('/cached-page')->assertRedirect('/second-destination');
    }

    public function test_it_invalidates_the_cache_when_a_regex_rule_changes(): void
    {
        $rule = $this->createRule('posts/(.*)', '/articles/$1', isRegex: true);

        $this->get('/posts/hello-world')->assertRedirect('/articles/hello-world');

        $rule->update(['enabled' => false]);

        $this->get('/posts/hello-world')->assertSee('fallback-response');
    }

    public function test_it_invalidates_the_cache_when_a_rule_is_deleted(): void
    {
        $rule = $this->createRule('old-page', '/new-page');

        $this->get('/old-page')->assertRedirect('/new-page');

        $rule->delete();

        $this->get('/old-page')->assertSee('fallback-response');
    }

    public function test_it_reads_the_rules_on_every_request_when_the_cache_is_disabled(): void
    {
        config()->set('nova-redirector-seo.cache.ttl', 0);

        $rule = $this->createRule('old-page', '/new-page');

        $this->get('/old-page')->assertRedirect('/new-page');
        $this->assertNull(cache()->get(NovaRedirectorSeo::cacheKey()));

        DB::table('nova_redirector_seo')
            ->where('id', $rule->id)
            ->update(['to_url' => '/second-destination']);

        $this->get('/old-page')->assertRedirect('/second-destination');
    }

    private function countRuleQueries(callable $callback): int
    {
        $queries = 0;

        DB::listen(function ($query) use (&$queries) {
            if (str_contains($query->sql, 'nova_redirector_seo')) {
                $queries++;
            }
        });

        $callback();

        return $queries;
    }

    private function createRule(
        string $fromUrl,
        string $toUrl,
        int $statusCode = 301,
        bool $enabled = true,
        bool $isRegex = false,
    ): NovaRedirectorSeo {
        $rule = new NovaRedirectorSeo;
        $rule->from_url = $fromUrl;
        $rule->to_url = $toUrl;
        $rule->status_code = $statusCode;
        $rule->enabled = $enabled;
        $rule->is_regex = $isRegex;
        $rule->save();

        return $rule;
    }
}
