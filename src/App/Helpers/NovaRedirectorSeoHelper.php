<?php

namespace The3LabsTeam\NovaRedirectorSeo\App\Helpers;

use The3LabsTeam\NovaRedirectorSeo\App\Models\NovaRedirectorSeo;

class NovaRedirectorSeoHelper
{
    /**
     * The first function to be called when the redirect is triggered.
     * It will check if the given path matches to any regex or exact rule.
     * If it matches, it will return the redirect object.
     */
    public static function handle(string $path): ?object
    {
        $rules = self::rules();

        return self::challengeRegex($rules['regex'], $path)
            ?? self::challengeExact($rules['exact'], $path);
    }

    /**
     * Every enabled rule, kept in a single cache entry.
     *
     * The whole set is cached at once instead of one entry per visited path: the
     * rules are few and shared by every request, while the paths are unbounded —
     * a crawler alone visits enough distinct URLs to fill the cache store with
     * entries that are never read twice.
     *
     * @return array{exact: array<string, array{to_url: string, status_code: int}>, regex: array<int, array{from_url: string, to_url: string, status_code: int}>}
     */
    private static function rules(): array
    {
        $ttl = config('nova-redirector-seo.cache.ttl');

        if ($ttl === null || (int) $ttl < 1) {
            return self::loadRules();
        }

        return cache()->remember(
            NovaRedirectorSeo::cacheKey(),
            (int) $ttl,
            static fn (): array => self::loadRules(),
        );
    }

    /**
     * @return array{exact: array<string, array{to_url: string, status_code: int}>, regex: array<int, array{from_url: string, to_url: string, status_code: int}>}
     */
    private static function loadRules(): array
    {
        $rules = ['exact' => [], 'regex' => []];

        $enabled = NovaRedirectorSeo::query()
            ->where('enabled', true)
            ->orderBy('id')
            ->get(['from_url', 'to_url', 'status_code', 'is_regex']);

        foreach ($enabled as $rule) {
            if ($rule->from_url === '' || $rule->to_url === '') {
                continue;
            }

            if ($rule->is_regex) {
                $rules['regex'][] = [
                    'from_url' => $rule->from_url,
                    'to_url' => $rule->to_url,
                    'status_code' => (int) $rule->status_code,
                ];

                continue;
            }

            $rules['exact'][$rule->from_url] = [
                'to_url' => $rule->to_url,
                'status_code' => (int) $rule->status_code,
            ];
        }

        return $rules;
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
     * Determine if the given path matches to any exact rule.
     * If it matches, return the redirect object.
     * If it doesn't match, return null.
     *
     * @param  array<string, array{to_url: string, status_code: int}>  $rules
     */
    private static function challengeExact(array $rules, string $path): ?object
    {
        $rule = $rules[$path] ?? null;

        if ($rule === null) {
            return null;
        }

        return (object) [
            'to_url' => $rule['to_url'],
            'status_code' => $rule['status_code'] ?: 301,
        ];
    }

    /**
     * Wrap a stored pattern in a delimiter it is allowed to contain.
     *
     * Rules are written as bare expressions such as `posts/(.*)`, so slashes are
     * expected and must not end the expression: `#` is used as the delimiter and
     * escaped where the author wrote it literally. Returns null when the pattern
     * does not compile, so one broken rule cannot take the redirector down.
     */
    private static function compilePattern(string $pattern): ?string
    {
        $delimited = '#'.preg_replace('/(?<!\\\\)#/', '\\#', $pattern).'#';

        if (@preg_match($delimited, '') === false) {
            return null;
        }

        return $delimited;
    }
}
