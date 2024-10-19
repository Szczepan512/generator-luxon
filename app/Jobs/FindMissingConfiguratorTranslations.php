<?php

namespace App\Jobs;

use App\Models\Luminaire;
use App\Models\User;
use App\Notifications\FindMissingConfiguratorTranslationsNotification;
use App\Notifications\UpdateGoogleSheetNotification;
use App\Settings\FamilyCardSettings;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class FindMissingConfiguratorTranslations implements ShouldQueue
{
    use Queueable;
    private $flag = false;
    private $configurator = [];
    private $changes = 0;
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

        Luminaire::chunk(100, function ($luminaires) {
            foreach ($luminaires as $luminaire) {
                $sku = $luminaire->name;
                $exploded_sku = explode('.', $sku);

                if (!isset($this->configurator[2]))
                    $this->configurator[2] = [];
                if (!in_array($exploded_sku[1][1], $this->configurator[2])) {
                    $this->configurator[2][] = $exploded_sku[1][1];
                }

                if (!isset($this->configurator[5]))
                    $this->configurator[5] = [];
                if (!in_array($exploded_sku[3][1], $this->configurator[5])) {
                    $this->configurator[5][] = $exploded_sku[3][1];
                }

                //Configurator Column 8
                if (!isset($this->configurator[7]))
                    $this->configurator[7] = [];
                if (!in_array($exploded_sku[4][0], $this->configurator[7])) {
                    $this->configurator[7][] = $exploded_sku[4][0];
                }
                //Configurator Column 9
                if (!isset($this->configurator[8]))
                    $this->configurator[8] = [];
                if (!in_array(substr($exploded_sku[4], -3), $this->configurator[8])) {
                    $this->configurator[8][] = substr($exploded_sku[4], -3);
                }

                //Configurator Column 11
                if (!isset($this->configurator[10]))
                    $this->configurator[10] = [];
                if (!in_array($exploded_sku[6], $this->configurator[10])) {
                    $this->configurator[10][] = $exploded_sku[6];
                }

                //Configurator Column 14
                if (!isset($this->configurator[13]))
                    $this->configurator[13] = [];
                if (!in_array($exploded_sku[9], $this->configurator[13])) {
                    $this->configurator[13][] = $exploded_sku[9];
                }

            }

        });
        $familyCardSettings = new FamilyCardSettings();
        $pl_configurator_data = $familyCardSettings->pl_configurator;
        $de_configurator_data = $familyCardSettings->de_configurator;
        $en_configurator_data = $familyCardSettings->en_configurator;
        foreach ($this->configurator as $key => $value) {
            $conf = $value;
            if ($key == 8) {
                $conf = [];
                foreach ($value as $v) {
                    foreach ([substr($v, -1), substr($v, 0, 2)] as $v) {
                        if (!array_key_exists($v, $conf)) {
                            $conf[] = $v;
                        }
                    }
                }
            }
            foreach ($conf as $v) {
                if (!array_key_exists($v, $pl_configurator_data[$key + 1])) {
                    $pl_configurator_data[$key + 1][$v] = '';
                    $this->flag = true;
                    $this->changes++;
                }
                if (!array_key_exists($v, $de_configurator_data[$key + 1])) {
                    $de_configurator_data[$key + 1][$v] = '';
                    $this->flag = true;
                    $this->changes++;
                }
                if (!array_key_exists($v, $en_configurator_data[$key + 1])) {
                    $en_configurator_data[$key + 1][$v] = '';
                    $this->flag = true;
                    $this->changes++;
                }
            }
        }
        $familyCardSettings->pl_configurator = $pl_configurator_data;
        $familyCardSettings->de_configurator = $de_configurator_data;
        $familyCardSettings->en_configurator = $en_configurator_data;
        $familyCardSettings->save();


        if ($this->flag) {
            Notification::make()
                ->title('Znaleziono braki w tłumaczeniach')
                ->warning()
                ->body('Przejdź do zakładki Ustawienia - Karta Rodziny i uzupełnij tłumaczenia')
                ->actions(
                    [
                        \Filament\Notifications\Actions\Action::make('complete')
                            ->label('Uzupełnij')
                            ->url(route('filament.admin.pages.manage-family-card-settings', ['tab' => 2, 'subtab' => 4]), shouldOpenInNewTab: true),
                    ]
                )
                ->persistent()
                ->sendToDatabase(User::all());
        } else {
            Notification::make()
                ->title('Nie znaleziono nowych tłumaczeń')
                ->info()
                ->sendToDatabase(User::all());
        }
    }
}
