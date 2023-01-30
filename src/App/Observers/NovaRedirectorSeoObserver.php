<?php

namespace The3LabsTeam\NovaRedirectorSeo\App\Observers;

use The3LabsTeam\NovaRedirectorSeo\App\Models\NovaRedirectorSeo;

class NovaRedirectorSeoObserver
{
    /**
     * Handle the NovaRedirectorSeo "created" event.
     *
     * @param  \The3LabsTeam\NovaRedirectorSeo\App\Models\NovaRedirectorSeo  $novaRedirectorSeo
     * @return void
     */
    public function created(NovaRedirectorSeo $novaRedirectorSeo)
    {
        $this->clearCache($novaRedirectorSeo);
    }

    /**
     * Handle the NovaRedirectorSeo "updated" event.
     *
     * @param  \The3LabsTeam\NovaRedirectorSeo\App\Models\NovaRedirectorSeo  $novaRedirectorSeo
     * @return void
     */
    public function updated(NovaRedirectorSeo $novaRedirectorSeo)
    {
        $this->clearCache($novaRedirectorSeo);
    }

    /**
     * Handle the NovaRedirectorSeo "deleted" event.
     *
     * @param  \The3LabsTeam\NovaRedirectorSeo\App\Models\NovaRedirectorSeo  $novaRedirectorSeo
     * @return void
     */
    public function deleted(NovaRedirectorSeo $novaRedirectorSeo)
    {
        $this->clearCache($novaRedirectorSeo);
    }

    /**
     * Handle the NovaRedirectorSeo "restored" event.
     *
     * @param  \The3LabsTeam\NovaRedirectorSeo\App\Models\NovaRedirectorSeo  $novaRedirectorSeo
     * @return void
     */
    public function restored(NovaRedirectorSeo $novaRedirectorSeo)
    {
        $this->clearCache($novaRedirectorSeo);
    }

    /**
     * Handle the NovaRedirectorSeo "force deleted" event.
     *
     * @param  \The3LabsTeam\NovaRedirectorSeo\App\Models\NovaRedirectorSeo  $novaRedirectorSeo
     * @return void
     */
    public function forceDeleted(NovaRedirectorSeo $novaRedirectorSeo)
    {
        $this->clearCache($novaRedirectorSeo);
    }
}
