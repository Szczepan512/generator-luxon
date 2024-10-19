<?php
namespace App\Filament\Resources\LuminaireResource\Actions;
use App\Jobs\GenerateIndividualCardJob;
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
use Filament\Tables\Actions\Action;
use File;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\HtmlString;
use Spatie\Browsershot\Browsershot; // reference browsershot


use Filament\Forms;
use App\Models\Luminaire;
use Storage;
class GenerateIndividualCard extends Action
{

    public static function make(?string $name = null): static
    {

        return parent::make('generateIndividualCard')
            ->label('Generuj Kartę Indywidualną')
            ->button()
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
                                                ->label('Stworzyć plik HTML?'),
                                            TextInput::make('pl_html_filename')
                                                ->label('Nazwa pliku HTML')
                                                ->hidden(function (Get $get) {
                                                    return !!!$get('pl_create_html');
                                                })
                                                ->endsWith('.html')
                                                ->helperText('Pozostawione puste plik zostanie nazwany kodem sku wraz z wersją językową'),
                                            Toggle::make('pl_html_externalDisk')
                                                ->label('Zapisać plik HTML na zewnętrznym dysku?')
                                                ->live()
                                                ->hidden(function (Get $get) {
                                                    return !!!$get('pl_create_html');
                                                }),

                                            Toggle::make('pl_create_pdf')
                                                ->live()
                                                ->label('Stworzyć plik PDF?'),
                                            TextInput::make('pl_pdf_filename')
                                                ->label('Nazwa pliku PDF')
                                                ->hidden(function (Get $get) {
                                                    return !!!$get('pl_create_pdf');
                                                })
                                                ->endsWith('.pdf')
                                                ->helperText('Pozostawione puste plik zostanie nazwany kodem sku wraz z wersją językową'),
                                            Toggle::make('pl_pdf_externalDisk')
                                                ->label('Zapisać plik PDF na zewnętrznym dysku?')
                                                ->live()
                                                ->hidden(function (Get $get) {
                                                    return !!!$get('pl_create_pdf');
                                                }),

                                        ]),
                                    Tabs\Tab::make(label: 'Angielski')
                                        ->schema([
                                            Toggle::make('en_create_html')
                                                ->live()
                                                ->label('Stworzyć plik HTML?'),
                                            TextInput::make('en_html_filename')
                                                ->label('Nazwa pliku HTML')
                                                ->hidden(function (Get $get) {
                                                    return !!!$get('en_create_html');
                                                })
                                                ->endsWith('.html')
                                                ->helperText('Pozostawione puste plik zostanie nazwany kodem sku wraz z wersją językową'),
                                            Toggle::make('en_html_externalDisk')
                                                ->label('Zapisać plik HTML na zewnętrznym dysku?')
                                                ->live()
                                                ->hidden(function (Get $get) {
                                                    return !!!$get('en_create_html');
                                                }),
                                            Toggle::make('en_create_pdf')
                                                ->live()
                                                ->label('Stworzyć plik PDF?'),
                                            TextInput::make('en_pdf_filename')
                                                ->label('Nazwa pliku PDF')
                                                ->hidden(function (Get $get) {
                                                    return !!!$get('en_create_pdf');
                                                })
                                                ->endsWith('.pdf')
                                                ->helperText('Pozostawione puste plik zostanie nazwany kodem sku wraz z wersją językową'),
                                            Toggle::make('en_pdf_externalDisk')
                                                ->label('Zapisać plik PDF na zewnętrznym dysku?')
                                                ->live()
                                                ->hidden(function (Get $get) {
                                                    return !!!$get('en_create_pdf');
                                                }),
                                        ]),

                                    Tabs\Tab::make('Niemiecki')
                                        ->schema([
                                            Toggle::make('de_create_html')
                                                ->live()
                                                ->label('Stworzyć plik HTML?'),
                                            TextInput::make('de_html_filename')
                                                ->label('Nazwa pliku HTML')
                                                ->hidden(function (Get $get) {
                                                    return !!!$get('de_create_html');
                                                })
                                                ->endsWith('.html')
                                                ->helperText('Pozostawione puste plik zostanie nazwany kodem sku wraz z wersją językową'),
                                            Toggle::make('de_html_externalDisk')
                                                ->label('Zapisać plik HTML na zewnętrznym dysku?')
                                                ->live()
                                                ->hidden(function (Get $get) {
                                                    return !!!$get('de_create_html');
                                                }),
                                            Toggle::make('de_create_pdf')
                                                ->live()
                                                ->label('Stworzyć plik PDF?'),
                                            TextInput::make('de_pdf_filename')
                                                ->label('Nazwa pliku PDF')
                                                ->hidden(function (Get $get) {
                                                    return !!!$get('de_create_pdf');
                                                })
                                                ->endsWith('.pdf')
                                                ->helperText('Pozostawione puste plik zostanie nazwany kodem sku wraz z wersją językową'),
                                            Toggle::make('de_pdf_externalDisk')
                                                ->label('Zapisać plik PDF na zewnętrznym dysku?')
                                                ->live()
                                                ->hidden(function (Get $get) {
                                                    return !!!$get('de_create_pdf');
                                                }),
                                        ])
                                ])
                        ])
                        ->beforeValidation(function () {
                            $sheetSettings = new SheetSettings();

                            if (empty($sheetSettings->sheet_headings)) {
                                Notification::make()
                                    ->title('Brak nagłówków tabeli. Skontaktuj się z administratorem')
                                    ->danger()
                                    ->send();
                                throw new Halt();
                            }
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
                                    'description' => 'Opis Oprawy',
                                    'default' => 'Dane ogólne',
                                    'electrical' => 'Dane elektryczne',
                                    'psu' => 'Dane techniczne układu zasilającego',
                                    'light_source' => 'Dane źródła światła',
                                    'materials' => 'Materiały',
                                    'fotometric' => 'Dane Fotometryczne oprawy',
                                    'img' => 'Obrazek fotometryczny',
                                    'dimensions' => 'Wymiary',
                                    'files' => 'Pliki do pobrania',
                                    'gallery' => "Galeria",
                                ])
                                ->default([
                                    'hero',
                                    'iconsBar',
                                    'description',
                                    'default',
                                    'electrical',
                                    'psu',
                                    'light_source',
                                    'materials',
                                    'fotometric',
                                    'img',
                                    'dimensions',
                                    'files',
                                    'gallery'
                                ])
                                ->required()
                        ]),
                    Wizard\Step::make('Wybór Danych')
                        ->schema([
                            Forms\Components\CheckboxList::make('fields')
                                ->label('Wybierz pola do wykluczenia')
                                ->options(function (Luminaire $record, SheetSettings $sheetSettings) {
                                    $values = $record->values;
                                    $values = array_pad($values, count($sheetSettings->sheet_headings), '');

                                    $combined = array_map(function ($a, $b) {
                                        return $a . ' => ' . $b;
                                    }, $sheetSettings->sheet_headings, $values);

                                    return $combined;
                                })
                                ->default(function (Luminaire $record, SheetSettings $sheetSettings) {

                                    $values = $record->values;
                                    $values = array_pad($values, count($sheetSettings->sheet_headings), '');

                                    $combined = array_map(function ($a, $b) {
                                        return $a . ' => ' . $b;
                                    }, $sheetSettings->sheet_headings, $values);

                                    $defaults = [];
                                    foreach ($combined as $key => $value) {

                                        if (!preg_match('/=>(\s*)(nie dotyczy|#N\/A)?(\s*)$/', $value)) {
                                            $defaults[] = $key;
                                        }
                                    }
                                    return $defaults;
                                })
                                ->required()
                                ->searchable()
                                ->bulkToggleable()
                        ])
                ])
            ])
            ->action(function (Luminaire $record, array $data) {

                GenerateIndividualCardJob::dispatch($record, $data, auth()->user())->onQueue('generator');

                Notification::make()
                    ->title('Dodano do kolejki')
                    ->info()
                    ->send();
            });
    }

}