<?php

namespace The3LabsTeam\NovaRedirectorSeo\Commands;

use Illuminate\Console\Command;

class NovaRedirectorSeoCommand extends Command
{
    public $signature = 'nova-redirector-seo';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
