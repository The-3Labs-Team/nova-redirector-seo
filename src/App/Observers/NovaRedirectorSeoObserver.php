<?php

namespace The3LabsTeam\NovaRedirectorSeo\App\Observers;

use The3LabsTeam\NovaRedirectorSeo\App\Models\NovaRedirectorSeo;

class NovaRedirectorSeoObserver
{
    /**
     * Handle the NovaRedirectorSeo "created" event.
     *
     * @return void
     */
    public function created(NovaRedirectorSeo $novaRedirectorSeo)
    {
        $this->clearCache($novaRedirectorSeo);
    }

    /**
     * Handle the NovaRedirectorSeo "updated" event.
     *
     * @return void
     */
    public function updated(NovaRedirectorSeo $novaRedirectorSeo)
    {
        $this->clearCache($novaRedirectorSeo);
    }

    /**
     * Handle the NovaRedirectorSeo "deleted" event.
     *
     * @return void
     */
    public function deleted(NovaRedirectorSeo $novaRedirectorSeo)
    {
        $this->clearCache($novaRedirectorSeo);
    }

    /**
     * Handle the NovaRedirectorSeo "restored" event.
     *
     * @return void
     */
    public function restored(NovaRedirectorSeo $novaRedirectorSeo)
    {
        $this->clearCache($novaRedirectorSeo);
    }

    /**
     * Handle the NovaRedirectorSeo "force deleted" event.
     *
     * @return void
     */
    public function forceDeleted(NovaRedirectorSeo $novaRedirectorSeo)
    {
        $this->clearCache($novaRedirectorSeo);
    }

    private function clearCache(NovaRedirectorSeo $novaRedirectorSeo): void
    {
        $originalPath = $novaRedirectorSeo->getOriginal('from_url');

        if (is_string($originalPath) && $originalPath !== '') {
            cache()->forget(NovaRedirectorSeo::cacheKey($originalPath));
        }

        $novaRedirectorSeo->clearCache();
    }
}
