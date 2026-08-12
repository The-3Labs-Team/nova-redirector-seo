<?php

namespace The3LabsTeam\NovaRedirectorSeo\Tests\Feature;

use The3LabsTeam\NovaRedirectorSeo\App\Models\NovaRedirectorSeo;
use The3LabsTeam\NovaRedirectorSeo\Tests\TestCase;

class NovaRedirectorSeoMiddlewareTest extends TestCase
{
    public function test_it_redirects_exact_matches(): void
    {
        $this->createRule('old-page', '/new-page');

        $response = $this->get('/old-page');

        $response->assertRedirect('/new-page');
        $this->assertSame(301, $response->getStatusCode());
    }

    public function test_it_expands_regex_matches_written_with_plain_slashes(): void
    {
        $this->createRule('posts/(.*)', '/articles/$1', statusCode: 302, isRegex: true);

        $response = $this->get('/posts/hello-world');

        $response->assertRedirect('/articles/hello-world');
        $this->assertSame(302, $response->getStatusCode());
    }

    public function test_it_still_expands_regex_matches_written_with_escaped_slashes(): void
    {
        $this->createRule('posts\/(.*)', '/articles/$1', isRegex: true);

        $this->get('/posts/hello-world')->assertRedirect('/articles/hello-world');
    }

    public function test_it_ignores_a_rule_whose_pattern_does_not_compile(): void
    {
        $this->createRule('posts/(unclosed', '/articles', isRegex: true);
        $this->createRule('posts/(.*)', '/articles/$1', isRegex: true);

        $this->get('/posts/hello-world')->assertRedirect('/articles/hello-world');
    }

    public function test_it_ignores_disabled_rules(): void
    {
        $this->createRule('old-page', '/new-page', enabled: false);

        $this->get('/old-page')->assertSee('fallback-response');
    }

    public function test_it_lets_unmatched_paths_through(): void
    {
        $this->createRule('old-page', '/new-page');

        $this->get('/some-other-page')->assertSee('fallback-response');
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
