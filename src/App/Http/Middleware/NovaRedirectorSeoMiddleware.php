<?php

namespace The3LabsTeam\NovaRedirectorSeo\App\Http\Middleware;

use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Redirect;
use The3LabsTeam\NovaRedirectorSeo\App\Models\NovaRedirectorSeo;

class NovaRedirectorSeoMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure(Request): (Response|RedirectResponse) $next
     * @return Response|RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $path = $request->path();

        $redirect = cache()->remember(
            "nova-redirector-seo.{$path}",
            config('nova-redirector-seo.cache.ttl'),
            function () use ($path) {
                return NovaRedirectorSeo::where('from_url', 'regexp', $path)
                    ->where('enabled', true)
                    ->first();
            }
        );

        if ($redirect) {
            return Redirect::to($redirect->to_url, $redirect->status_code);
        }

        return $next($request);
    }
}