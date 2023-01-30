<?php

namespace The3LabsTeam\NovaRedirectorSeo\App\Helpers;

use The3LabsTeam\NovaRedirectorSeo\App\Models\NovaRedirectorSeo;

class NovaRedirectorSeoHelper
{
    /**
     * The first function to be called when the redirect is triggered.
     * It will check if the given path matches to any regex or exact rule.
     * If it matches, it will return the redirect object.
     * @param  string  $path
     * @return object|null
     */
    public static function handle(string $path): object|null
    {
        $redirect = self::challengeRegex($path) ?? self::challengeExact($path) ?? null;
        if ($redirect) {
            return (object) [
                'to_url' => $redirect->to_url,
                'status_code' => $redirect->status_code,
            ];
        }
        return null;
    }

    /**
     * Determine if the given path matches to any regex saved in the database.
     * If it matches, return the redirect object.
     * If it doesn't match, return null.
     *
     * @param  string  $path
     * @return object|null
     */
    private static function challengeRegex(string $path)
    {
        $regexRules = NovaRedirectorSeo::where('enabled', true)
            ->where('is_regex', true)
            ->get();

        foreach ($regexRules as $regex) {
            if (self::testRegex($regex->from_url, $path)) {
                $toUrl = preg_replace("/$regex->from_url/", $regex->to_url, $path);

                return (object) [
                    'to_url' => $toUrl,
                    'status_code' => $regex->status_code,
                ];
            }
        }

        return null;
    }

    /**
     * Determine if the given path matches to any exact saved in the database.
     * If it matches, return the redirect object.
     * If it doesn't match, return null.
     *
     * @param  string  $path
     * @return object|null
     */
    private static function challengeExact(string $path)
    {
        $query = NovaRedirectorSeo::where('from_url', $path)
            ->where('enabled', true)
            ->where('is_regex', false)
            ->first();
        $fromUrl = $query->from_url ?? null;
        $toUrl = $query->to_url ?? null;
        $statusCode = $query->status_code ?? 301;

        if (! $fromUrl || ! $toUrl) {
            return null;
        }

        return (object) [
            'to_url' => $toUrl,
            'status_code' => $statusCode,
        ];
    }

    /**
     * Test if the regex provided by the user is valid.
     *
     * @param $regex
     * @param $path
     * @return false|int
     */
    private static function testRegex(string $regex, string $path): false|int
    {
        if (@preg_match("/$regex/", null) === false) {
            return false;
        }

        return preg_match("/$regex/", $path);
    }
}
