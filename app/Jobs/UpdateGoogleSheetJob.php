<?php

namespace App\Jobs;

use App\Models\User;
use App\Notifications\CardGeneratedNotification;
use App\Notifications\UpdateGoogleSheetNotification;
use App\Settings\FamilyCardSettings;
use Filament\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Luminaire;
use App\Models\LuminaireFamily;
use App\Settings\SheetSettings;
use Google\Client;
use Google\Service\Sheets;
use Illuminate\Http\Request;
use DB;
use Livewire\Livewire;

class UpdateGoogleSheetJob implements ShouldQueue
{
    use Queueable;
    public $timeout = 0; // na przykład zwiększenie do 300 sekund (5 minut)

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {

        set_time_limit(0); // Brak limitu czasu
        ini_set('memory_limit', '512M'); // Ustawienie limitu pamięci, jeśli to konieczne

        $client = new Client();
        $client->setApplicationName('Luxon Generator Laravel App');
        $client->setScopes([Sheets::SPREADSHEETS_READONLY]);
        $client->setAuthConfig(storage_path('app/credentials/credentials.json'));
        $client->setAccessType('offline');
        $service = new Sheets($client);

        $sheetSettings = new SheetSettings();
        $google_sheet_ids = array_column($sheetSettings->sheets_ids, 'sheet_id');

        // DB::table('luminaire_families')->truncate();
        // DB::table('luminaires')->truncate();

        LuminaireFamily::query()->update(['visible' => false]);
        Luminaire::query()->update(['visible' => false]);

        $headingsFlag = 1;

        foreach ($google_sheet_ids as $sheet_id) {
            $attempts = 0;
            while ($attempts < 30) {
                try {
                    $sheets = $service->spreadsheets->get($sheet_id)->getSheets();
                    break;
                } catch (\Exception $e) {
                    $attempts++;
                    if ($attempts < 30) {
                        sleep(6);
                    } else {
                        throw $e;
                    }
                }
            }
            foreach ($sheets as $sheet) {
                if (in_array($sheet->properties->title, ['OPRAWY_WSZYSTKIE', 'Niestandardy']))
                    continue;

                $attempts = 0;
                $range = $sheet->properties->title . '!A:KH';
                while ($attempts < 30) {
                    try {
                        $response = $service->spreadsheets_values->get($sheet_id, $range);
                        $values = $response->getValues();

                        if ($values !== null && $values[0][0] == "SKU") {

                            echo $sheet->properties->title . PHP_EOL;
                           
                            $luminaireFamily = LuminaireFamily::firstOrCreate([
                                'name' => $sheet->properties->title,
                                'sheet_id' => $sheet_id
                            ]);

                            $luminaireFamily->visible = true;
                            $luminaireFamily->save();

                            foreach ($values as $rowIndex => $row) {
                                if ($headingsFlag) {
                                    $sheetSettings->sheet_headings = $row;
                                    $sheetSettings->save();
                                    $headingsFlag = 0;
                                }
                            
                                if ($rowIndex < 2 || !(isset($row[148]) && $row[148] == "TAK")) {
                                    continue;
                                }
                            
                                // Używamy updateOrCreate zamiast tworzenia tablicy i masowego insertu
                                Luminaire::updateOrCreate(
                                    // Warunek, który sprawdza istnienie rekordu
                                    ['name' => $row[0], 'luminaireFamily_id' => $luminaireFamily->id],
                                    
                                    // Atrybuty do aktualizacji lub ustawienia przy tworzeniu nowego rekordu
                                    [
                                        'name' => $row[0],  // Zaktualizuje nazwę, jeśli już istnieje
                                        'luminaireFamily_id' => $luminaireFamily->id,
                                        'values' => $row,
                                        'visible' => true,
                                    ]
                                );
                            }
                        }
                        break;
                    } catch (\Exception $e) {
                        $attempts++;
                        if ($attempts < 30) {
                            sleep(6);
                        } else {
                            throw $e;
                        }
                    }
                }
            }
        }

        FindMissingConfiguratorTranslations::dispatch();

        Notification::make()
            ->title("Pomyślnie pobrano dane z GoogleAPI")
            ->success()
            ->sendToDatabase(User::all());


    }
}
