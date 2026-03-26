<?php

namespace The3LabsTeam\NovaRedirectorSeo\App\Http\Middleware;

use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Redirect;
use The3LabsTeam\NovaRedirectorSeo\App\Helpers\NovaRedirectorSeoHelper;
use The3LabsTeam\NovaRedirectorSeo\App\Models\NovaRedirectorSeo;

class NovaRedirectorSeoMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response|RedirectResponse)  $next
     * @return Response|RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $redirect = $this->resolveRedirect($request->path());

        if ($redirect) {
            return Redirect::to($redirect->to_url, $redirect->status_code);
        }

        return $next($request);
    }

    private function resolveRedirect(string $path): ?object
    {
        $ttl = config('nova-redirector-seo.cache.ttl');

        if ($ttl === null || (int) $ttl < 1) {
            return NovaRedirectorSeoHelper::handle($path);
        }

        return cache()->remember(
            NovaRedirectorSeo::cacheKey($path),
            (int) $ttl,
            static fn (): ?object => NovaRedirectorSeoHelper::handle($path),
        );
    }
}
