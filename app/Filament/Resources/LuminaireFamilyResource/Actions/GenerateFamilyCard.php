<?php
namespace App\Filament\Resources\LuminaireResource\Actions;
use App\Jobs\GenerateFamilyCardJob;
use App\Jobs\GenerateIndividualCardJob;
use App\Models\LuminaireFamily;
use App\Settings\SheetSettings;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Get;
use Filament\Notifications\Notification;
use Filament\Support\Exceptions\Halt;
use Filament\Actions\Action;
use File;
use Illuminate\Support\Facades\Auth;
use Spatie\Browsershot\Browsershot; // reference browsershot


use Filament\Forms;
use App\Models\Luminaire;
use Filament\Forms\Components\Section;
use Storage;
class GenerateFamilyCard extends Action
{

    public static function make(?string $name = null): static
    {

        return parent::make('generateFamilyCard')
            ->label('Generuj Kartę Rodziny')
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
                                            TextInput::make('pl_html_filename')
                                                ->label('Nazwa pliku HTML')
                                                ->default(function($record) {
                                                    return 'Karta_katalogowa_'.str_replace(' ','_',$record->name).'.html';
                                                })
                                                ->visible(function (Get $get) {
                                                    return !!$get('pl_create_html');
                                                })
                                                ->endsWith('.html'),
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
                                            TextInput::make('pl_pdf_filename')
                                                ->label('Nazwa pliku PDF')
                                                ->visible(function (Get $get) {
                                                    return !!$get('pl_create_pdf');
                                                })
                                                ->default(function($record) {
                                                    return 'Karta_katalogowa_'.str_replace(' ','_',$record->name).'.pdf';
                                                })
                                                ->endsWith('.pdf'),
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
                                            TextInput::make('en_html_filename')
                                                ->label('Nazwa pliku HTML')
                                                ->visible(function (Get $get) {
                                                    return !!$get('en_create_html');
                                                })
                                                ->default(function($record) {
                                                    return 'Data_sheet_'.str_replace(' ','_',$record->name).'.html';
                                                })
                                                ->endsWith('.html'),
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
                                            TextInput::make('en_pdf_filename')
                                                ->label('Nazwa pliku PDF')
                                                ->visible(function (Get $get) {
                                                    return !!$get('en_create_pdf');
                                                })
                                                ->default(function($record) {
                                                    return 'Data_sheet_'.str_replace(' ','_',$record->name).'.pdf';
                                                })
                                                ->endsWith('.pdf'),
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
                                            TextInput::make('de_html_filename')
                                                ->label('Nazwa pliku HTML')
                                                ->visible(function (Get $get) {
                                                    return !!$get('de_create_html');
                                                })
                                                ->default(function($record) {
                                                    return 'Datenblatt_'.str_replace(' ','_',$record->name).'.html';
                                                })
                                                ->endsWith('.html'),
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
                                            TextInput::make('de_pdf_filename')
                                                ->label('Nazwa pliku PDF')
                                                ->visible(function (Get $get) {
                                                    return !!$get('de_create_pdf');
                                                })
                                                ->default(function($record) {
                                                    return 'Datenblatt_'.str_replace(' ','_',$record->name).'.pdf';
                                                })
                                                ->endsWith('.pdf'),
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
                        ->beforeValidation(function () {
                            // $sheetSettings = new SheetSettings();
                
                            // if (empty($sheetSettings->sheet_headings)) {
                            //     Notification::make()
                            //         ->title('Brak nagłówków tabeli. Skontaktuj się z administratorem')
                            //         ->danger()
                            //         ->send();
                            //     throw new Halt();
                            // }
                        })
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
                    Wizard\Step::make("Wybór opraw")
                        ->schema([
                            Section::make('Lampy (zwinięte)')
                            ->schema([
                                CheckboxList::make('luminaires')
                                    ->label("Wybierz które SKU brać pod uwagę")
                                    ->options(function (LuminaireFamily $record) {
                                        $luminaires = $record->luminaires;
                                        $options = [];
                                        foreach ($luminaires as $luminaire) {
                                            $options[$luminaire->id] = $luminaire->name;
                                        }
                                        return $options;
                                    })
                                    ->default(function (LuminaireFamily $record) {
                                        $luminaires = $record->luminaires;
                                        $options = [];
                                        foreach ($luminaires as $luminaire) {
                                            $options[] = $luminaire->id;
                                        }
                                        return $options;
                                    })
                                    ->searchable()
                                    ->bulkToggleable()

                            ])
                            ->collapsed(),
                        ])
                        ->afterValidation(function ($state) {
                            if (empty($state['luminaires'])) {
                                Notification::make('')
                                    ->title('Żadna lampa nie została wybrana')
                                    ->warning()
                                    ->send();
                                throw new Halt();
                            }
                        })
                ])
                    ->contained(false),
            ])->action(function (LuminaireFamily $record, array $data) {

                GenerateFamilyCardJob::dispatch($record, $data, auth()->user());
                Notification::make()
                    ->title('Dodano do kolejki')
                    ->info()
                    ->send();
            });
    }

}