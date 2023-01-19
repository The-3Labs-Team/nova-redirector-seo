<?php

namespace The3LabsTeam\NovaRedirectorSeo\App\Helpers;

use The3LabsTeam\NovaRedirectorSeo\App\Models\NovaRedirectorSeo;

class NovaRedirectorSeoHelper
{
    /**
     * @param string $path
     * @return object|null
     */
    public static function checkRedirectExists(string $path): object|null
    {
        $redirect = self::getRedirect($path);
        if ($redirect) {
            return (object) [
                'to_url' => $redirect->to_url,
                'status_code' => $redirect->status_code,
            ];
        }
        return null;
    }

    /**
     * @param string $path
     * @return object|null
     */
    private static function getRedirect($path) {
        $query = NovaRedirectorSeo::where('from_url', $path)
            ->where('enabled', true)
            ->first();

        $fromUrl = $query->from_url ?? null;
        $toUrl = $query->to_url ?? null;
        $statusCode = $query->status_code ?? 301;

        if(!$fromUrl || !$toUrl) {
            return null;
        }

        return (object) [
            'to_url' => $toUrl,
            'status_code' => $statusCode,
        ];
    }

}