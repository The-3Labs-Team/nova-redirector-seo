<?php

namespace The3LabsTeam\NovaRedirectorSeo\App\Helpers;

use The3LabsTeam\NovaRedirectorSeo\App\Models\NovaRedirectorSeo;

class NovaRedirectorSeoHelper
{
    /**
     * Delimiter candidates for stored patterns, in order of preference. Slashes are
     * absent on purpose: they are the one character every URL pattern contains.
     *
     * @var array<int, string>
     */
    private const DELIMITERS = ['#', '~', '!', '@', ';', ',', '%', '=', '|', '+'];

    /**
     * The first function to be called when the redirect is triggered.
     * It will check if the given path matches to any regex or exact rule.
     * If it matches, it will return the redirect object.
     */
    public static function handle(string $path): ?object
    {
        return self::challengeRegex(self::regexRules(), $path)
            ?? self::challengeExact($path);
    }

    /**
     * Every enabled regex rule, kept in a single cache entry.
     *
     * @return array<int, array{from_url: string, to_url: string, status_code: int}>
     */
    private static function regexRules(): array
    {
        $ttl = config('nova-redirector-seo.cache.ttl');

        if ($ttl === null || (int) $ttl < 1) {
            return self::loadRegexRules();
        }

        return cache()->remember(
            NovaRedirectorSeo::cacheKey(),
            (int) $ttl,
            static fn (): array => self::loadRegexRules(),
        );
    }

    /**
     * Read every enabled regex rule from the database.
     *
     * @return array<int, array{from_url: string, to_url: string, status_code: int}>
     */
    private static function loadRegexRules(): array
    {
        return NovaRedirectorSeo::query()
            ->where('enabled', true)
            ->where('is_regex', true)
            ->where('from_url', '!=', '')
            ->where('to_url', '!=', '')
            ->orderBy('id')
            ->get(['from_url', 'to_url', 'status_code'])
            ->map(static fn (NovaRedirectorSeo $rule): array => [
                'from_url' => $rule->from_url,
                'to_url' => $rule->to_url,
                'status_code' => (int) $rule->status_code,
            ])
            ->all();
    }

    /**
     * Determine if the given path matches to any regex rule.
     * If it matches, return the redirect object.
     * If it doesn't match, return null.
     *
     * @param  array<int, array{from_url: string, to_url: string, status_code: int}>  $rules
     */
    private static function challengeRegex(array $rules, string $path): ?object
    {
        foreach ($rules as $rule) {
            $pattern = self::compilePattern($rule['from_url']);

            if ($pattern === null || preg_match($pattern, $path) !== 1) {
                continue;
            }

            return (object) [
                'to_url' => preg_replace($pattern, $rule['to_url'], $path),
                'status_code' => $rule['status_code'],
            ];
        }

        return null;
    }

    /**
     * Find the first enabled exact rule for the given path in the database.
     * If it matches, return the redirect object.
     * If it doesn't match, return null.
     */
    private static function challengeExact(string $path): ?object
    {
        $rule = NovaRedirectorSeo::query()
            ->where('from_url', $path)
            ->where('enabled', true)
            ->where('is_regex', false)
            ->where('to_url', '!=', '')
            ->orderBy('id')
            ->first(['to_url', 'status_code']);

        if ($rule === null) {
            return null;
        }

        return (object) [
            'to_url' => $rule->to_url,
            'status_code' => (int) $rule->status_code ?: 301,
        ];
    }

    /**
     * Wrap a stored pattern in a delimiter the pattern does not contain.
     *
     * Rules are written as bare expressions such as `posts/(.*)`, so slashes are
     * expected and must not end the expression. The pattern itself is never
     * rewritten: escaping an occurrence would change the constructs that give
     * that character meaning — `(?#comment)`, or `#` starting a comment under
     * the `x` modifier — so a delimiter absent from the pattern is picked
     * instead. Returns null when the pattern does not compile, so one broken
     * rule cannot take the redirector down.
     */
    private static function compilePattern(string $pattern): ?string
    {
        foreach (self::DELIMITERS as $delimiter) {
            if (str_contains($pattern, $delimiter)) {
                continue;
            }

            $delimited = $delimiter.$pattern.$delimiter;

            return @preg_match($delimited, '') === false ? null : $delimited;
        }

        return null;
    }
}
