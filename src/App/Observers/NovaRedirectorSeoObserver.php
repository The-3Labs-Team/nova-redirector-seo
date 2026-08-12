<?php

namespace The3LabsTeam\NovaRedirectorSeo\App\Observers;

use The3LabsTeam\NovaRedirectorSeo\App\Models\NovaRedirectorSeo;

class NovaRedirectorSeoObserver
{
    /**
     * Handle the NovaRedirectorSeo "created" event.
     */
    public function created(NovaRedirectorSeo $novaRedirectorSeo): void
    {
        $novaRedirectorSeo->clearCache();
    }

    /**
     * Handle the NovaRedirectorSeo "updated" event.
     */
    public function updated(NovaRedirectorSeo $novaRedirectorSeo): void
    {
        $novaRedirectorSeo->clearCache();
    }

    /**
     * Handle the NovaRedirectorSeo "deleted" event.
     */
    public function deleted(NovaRedirectorSeo $novaRedirectorSeo): void
    {
        $novaRedirectorSeo->clearCache();
    }

    /**
     * Handle the NovaRedirectorSeo "restored" event.
     */
    public function restored(NovaRedirectorSeo $novaRedirectorSeo): void
    {
        $novaRedirectorSeo->clearCache();
    }

    /**
     * Handle the NovaRedirectorSeo "force deleted" event.
     */
    public function forceDeleted(NovaRedirectorSeo $novaRedirectorSeo): void
    {
        $novaRedirectorSeo->clearCache();
    }
}
