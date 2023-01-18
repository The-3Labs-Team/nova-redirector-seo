<?php

namespace The3LabsTeam\NovaRedirectorSeo\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \The3LabsTeam\NovaRedirectorSeo\NovaRedirectorSeo
 */
class NovaRedirectorSeo extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \The3LabsTeam\NovaRedirectorSeo\NovaRedirectorSeo::class;
    }
}
