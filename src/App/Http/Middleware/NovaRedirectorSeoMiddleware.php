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
     * @param  Closure(Request): (Response|RedirectResponse)  $next
     * @return Response|RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $redirect = NovaRedirectorSeoHelper::handle($request->path());

        if ($redirect) {
            return Redirect::to($redirect->to_url, $redirect->status_code);
        }

        return $next($request);
    }
}
