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
use Filament\Tables\Actions\BulkAction;
use File;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\HtmlString;
use Spatie\Browsershot\Browsershot; // reference browsershot


use Filament\Forms;
use App\Models\Luminaire;
use Storage;
class BulkGenerateIndividualCardFromLuminaireFamily extends BulkAction
{

    public static function make(?string $name = null): static
    {

        return parent::make('generateIndividualCard')
            ->label('Generuj PDF / HTML')
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
                                            Toggle::make('pl_html_externalDisk')
                                                ->label('Zapisać plik HTML na zewnętrznym dysku?')
                                                ->live()
                                                ->hidden(function (Get $get) {
                                                    return !!!$get('pl_create_html');
                                                }),

                                            Toggle::make('pl_create_pdf')
                                                ->live()
                                                ->label('Stworzyć plik PDF?'),
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
                                            Toggle::make('en_html_externalDisk')
                                                ->label('Zapisać plik HTML na zewnętrznym dysku?')
                                                ->live()
                                                ->hidden(function (Get $get) {
                                                    return !!!$get('en_create_html');
                                                }),
                                            Toggle::make('en_create_pdf')
                                                ->live()
                                                ->label('Stworzyć plik PDF?'),
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
                                            Toggle::make('de_html_externalDisk')
                                                ->label('Zapisać plik HTML na zewnętrznym dysku?')
                                                ->live()
                                                ->hidden(function (Get $get) {
                                                    return !!!$get('de_create_html');
                                                }),
                                            Toggle::make('de_create_pdf')
                                                ->live()
                                                ->label('Stworzyć plik PDF?'),
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
                            Toggle::make('removeEmpty')
                                ->label('Usunąć puste wartości z generowanych treści')
                        ])
                ])
            ])
            ->action(function (Collection $records, array $data) {
                dd($records, $data);
                // GenerateIndividualCardJob::dispatch($record, $data, auth()->user());
    
                Notification::make()
                    ->title('Dodano do kolejki')
                    ->info()
                    ->send();

            });
    }

}