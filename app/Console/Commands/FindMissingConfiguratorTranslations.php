<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class FindMissingConfiguratorTranslations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'luminaires:find-missing-configurator-translations';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Przeszukuje oprawy na brakujące ';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //
        \App\Jobs\FindMissingConfiguratorTranslations::dispatch();
        $this->info('Zadanie dodane do kolejki.');
    }
}
