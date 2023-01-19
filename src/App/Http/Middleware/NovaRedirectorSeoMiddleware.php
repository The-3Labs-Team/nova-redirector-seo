<?php

namespace The3LabsTeam\NovaRedirectorSeo\App\Http\Middleware;

use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Redirect;
use The3LabsTeam\NovaRedirectorSeo\App\Helpers\NovaRedirectorSeoHelper;

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
        $redirect = cache()->rememberForever('nova-redirector-seo.' . $path, function () use
        ($path) {
            return NovaRedirectorSeoHelper::checkRedirectExists($path);
        });

        if ($redirect) {
            return Redirect::to($redirect->to_url, $redirect->status_code);
        }

        return $next($request);
    }
}