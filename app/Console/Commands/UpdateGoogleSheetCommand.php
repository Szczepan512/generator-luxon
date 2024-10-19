<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\UpdateGoogleSheetJob;

class UpdateGoogleSheetCommand extends Command
{
    // Nazwa i opis komendy
    protected $signature = 'google-sheets:update';
    protected $description = 'Pobierz dane z Google Sheets i zaktualizuj tabelę w bazie danych';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        // Dodaj zadanie do kolejki
        UpdateGoogleSheetJob::dispatch();
        $this->info('Zadanie dodane do kolejki.');
    }
}