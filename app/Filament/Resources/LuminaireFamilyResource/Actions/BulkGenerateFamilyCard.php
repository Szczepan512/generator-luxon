<?php
namespace App\Filament\Resources\LuminaireResource\Actions;
use App\Jobs\GenerateFamilyCardJob;
use App\Jobs\GenerateIndividualCardJob;
use App\Settings\IndividualCardSettings;
use App\Settings\SheetSettings;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Get;
use Filament\Notifications\Notification;
use Filament\Support\Exceptions\Halt;
use Filament\Tables\Actions\BulkAction;
use File;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\HtmlString;
use Spatie\Browsershot\Browsershot; // reference browsershot


use Filament\Forms;
use App\Models\Luminaire;
use Storage;
class BulkGenerateFamilyCard extends BulkAction
{


    public static function make(?string $name = null): static
    {

        return parent::make('generateFamilyCard')
            ->label('Generuj Kartę Rodzin Masowo')
            ->form([
                Wizard::make([
                    Wizard\Step::make('Wersję językowe')
                        ->schema([
                            Tabs::make('Języki')
                                ->tabs([
                                    Tabs\Tab::make('Polski')
                                        ->schema([
                                            Toggle::make('pl_create_html')
                                                ->live()
                                                ->default(true)
                                                ->label('Stworzyć plik HTML?'),
                                            Toggle::make('pl_html_externalDisk')
                                                ->label('Zapisać plik HTML na zewnętrznym dysku?')
                                                ->live()
                                                ->default(false)
                                                ->visible(function (Get $get) {
                                                    return !!$get('pl_create_html');
                                                }),
                                            TextInput::make('pl_html_externalDisk_path')
                                                ->visible(function (Get $get) {
                                                    return !!($get('pl_html_externalDisk') && $get('pl_create_html'));
                                                })
                                                ->default('')
                                                ->regex('/^\/([a-zA-Z0-9_\s]+\/)*$/')
                                                ->helperText('Ścieka startowa to /public_html/luxon.pl/PIM')
                                                ->label('Ścieka do pliku HTML na dysku zewnętrznym'),
                                            Toggle::make('pl_create_pdf')
                                                ->live()
                                                ->default(true)
                                                ->label('Stworzyć plik PDF?'),
                                            Toggle::make('pl_pdf_externalDisk')
                                                ->label('Zapisać plik PDF na zewnętrznym dysku?')
                                                ->live()
                                                ->default(false)
                                                ->visible(function (Get $get) {
                                                    return !!$get('pl_create_pdf');
                                                }),
                                            TextInput::make('pl_pdf_externalDisk_path')
                                                ->visible(function (Get $get) {
                                                    return !!($get('pl_pdf_externalDisk') && $get('pl_create_pdf'));
                                                })
                                                ->default('')
                                                ->regex('/^\/([a-zA-Z0-9_\s]+\/)*$/')
                                                ->helperText('Ścieka startowa to /public_html/luxon.pl/PIM')
                                                ->label('Ścieka do pliku PDF na dysku zewnętrznym'),
                                        ]),
                                    Tabs\Tab::make(label: 'Angielski')
                                        ->schema([
                                            Toggle::make('en_create_html')
                                                ->live()
                                                ->default(true)
                                                ->label('Stworzyć plik HTML?'),
                                            Toggle::make('en_html_externalDisk')
                                                ->label('Zapisać plik HTML na zewnętrznym dysku?')
                                                ->live()
                                                ->default(false)
                                                ->visible(function (Get $get) {
                                                    return !!$get('en_create_html');
                                                }),
                                            TextInput::make('en_html_externalDisk_path')
                                                ->visible(function (Get $get) {
                                                    return !!($get('en_html_externalDisk') && $get('en_create_html'));
                                                })
                                                ->default('')
                                                ->regex('/^\/([a-zA-Z0-9_\s]+\/)*$/')
                                                ->helperText('Ścieka startowa to /public_html/luxonled.eu/PIM')
                                                ->label('Ścieka do pliku HTML na dysku zewnętrznym'),
                                            Toggle::make('en_create_pdf')
                                                ->live()
                                                ->default(true)
                                                ->label('Stworzyć plik PDF?'),
                                            Toggle::make('en_pdf_externalDisk')
                                                ->label('Zapisać plik PDF na zewnętrznym dysku?')
                                                ->live()
                                                ->default(false)
                                                ->visible(function (Get $get) {
                                                    return !!$get('en_create_pdf');
                                                }),
                                            TextInput::make('en_pdf_externalDisk_path')
                                                ->visible(function (Get $get) {
                                                    return !!($get('en_pdf_externalDisk') && $get('pl_create_pdf'));
                                                })
                                                ->default('')
                                                ->regex('/^\/([a-zA-Z0-9_\s]+\/)*$/')
                                                ->helperText('Ścieka startowa to /public_html/luxonled.eu/PIM')
                                                ->label('Ścieka do pliku PDF na dysku zewnętrznym'),
                                        ]),

                                    Tabs\Tab::make('Niemiecki')
                                        ->schema([
                                            Toggle::make('de_create_html')
                                                ->live()
                                                ->default(true)
                                                ->label('Stworzyć plik HTML?'),
                                            Toggle::make('de_html_externalDisk')
                                                ->label('Zapisać plik HTML na zewnętrznym dysku?')
                                                ->live()
                                                ->default(false)
                                                ->visible(function (Get $get) {
                                                    return !!$get('de_create_html');
                                                }),
                                            TextInput::make('de_html_externalDisk_path')
                                                ->visible(function (Get $get) {
                                                    return !!($get('de_html_externalDisk') && $get('de_create_html'));
                                                })
                                                ->default('')
                                                ->regex('/^\/([a-zA-Z0-9_\s]+\/)*$/')
                                                ->label('Ścieka do pliku HTML na dysku zewnętrznym')
                                                ->helperText('Ścieka startowa to /public_html/luxonled.de/PIM'),
                                            Toggle::make('de_create_pdf')
                                                ->live()
                                                ->default(true)
                                                ->label('Stworzyć plik PDF?'),
                                            Toggle::make('de_pdf_externalDisk')
                                                ->label('Zapisać plik PDF na zewnętrznym dysku?')
                                                ->live()
                                                ->default(false)
                                                ->visible(function (Get $get) {
                                                    return !!$get('de_create_pdf');
                                                }),
                                            TextInput::make('de_pdf_externalDisk_path')
                                                ->visible(function (Get $get) {
                                                    return !!($get('de_pdf_externalDisk') && $get('de_create_pdf'));
                                                })
                                                ->default('')
                                                ->regex('/^\/([a-zA-Z0-9_\s]+\/)*$/')
                                                ->label('Ścieka do pliku PDF na dysku zewnętrznym')
                                                ->helperText('Ścieka startowa to /public_html/luxonled.de/PIM'),
                                        ])
                                ])
                        ])
                        ->afterValidation(function ($state) {
                            $halt = true;
                            if (
                                $state['pl_create_html'] || $state['pl_create_pdf']
                                || $state['en_create_html'] || $state['en_create_pdf']
                                || $state['de_create_html'] || $state['de_create_pdf']
                            ) {
                                $halt = false;
                            }

                            if ($halt) {
                                Notification::make()
                                    ->title('Nie wybrano żadnego pliku do utworzenia')
                                    ->info()
                                    ->send();
                                throw new Halt();
                            }
                        }),
                    Wizard\Step::make('Wybór generowanych sekcji')
                        ->schema([
                            Forms\Components\CheckboxList::make('sections')
                                ->label('Wybierz sekcje które mają się wygenerować')
                                ->options([
                                    'hero' => 'Sekcja nagłówkowa',
                                    'iconsBar' => 'Pasek z ikonami',
                                    'tables' => 'Tabele',
                                    'dimensions' => 'Sekcja z wymiarmi',
                                    'montage' => 'Montaż',
                                    'light' => 'Natężenie świtła',
                                    'configurator' => 'Konfigurator',
                                    'example' => 'Przykładowy kod'
                                ])
                                ->default([
                                    'hero',
                                    'iconsBar',
                                    'tables',
                                    'dimensions',
                                    'montage',
                                    'light',
                                    'configurator',
                                    'example'
                                ])
                        ])
                        ->afterValidation(function ($state) {
                            if (empty($state['sections'])) {
                                Notification::make()
                                    ->title('Żadna sekcja nie została sekcja')
                                    ->warning()
                                    ->send();
                                throw new Halt();
                            }
                        }),
                ])
                    ->contained(false),
            ])
            ->action(function (Collection $records, array $data) {



                if (count($records) == 0) {
                    Notification::make('')
                        ->label('Nie wybrano żadnego rekordu')
                        ->warning()
                        ->send();
                    throw new Halt();
                }
                $count = 0;

                $chunks = $records->chunk(10);
                
                foreach ($chunks as $chunk) {
                    
                    $jobs = [];
                    foreach ($chunk as $record) {
                        $data['pl_html_filename'] = 'Karta_katalogowa_'.str_replace(' ','_',$record->name).'.html';
                        $data['pl_pdf_filename'] = 'Karta_katalogowa_'.str_replace(' ','_',$record->name).'.pdf';
                        $data['en_html_filename'] = 'Data_sheet_'.str_replace(' ','_',$record->name).'.html';
                        $data['en_pdf_filename'] = 'Data_sheet_'.str_replace(' ','_',$record->name).'.pdf';
                        $data['de_html_filename'] = 'Datenblatt_'.str_replace(' ','_',$record->name).'.html';
                        $data['de_pdf_filename'] = 'Datenblatt_'.str_replace(' ','_',$record->name).'.pdf';

                        $jobs[] = new GenerateFamilyCardJob($record, $data, auth()->user());
                        $count++;
                    }

                $batch = Bus::batch($jobs)->onQueue('generator')->dispatch();
                }
                Notification::make()
                    ->title('Dodano ' . $count . ' kart do kolejki')
                    ->info()
                    ->send();
            })
            ->deselectRecordsAfterCompletion();
    }

}