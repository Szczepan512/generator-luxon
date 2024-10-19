<?php

namespace App\Filament\Pages;

use App\Settings\FamilyCardSettings;
use App\Settings\IndividualCardSettings;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Pages\Page;
use Filament\Pages\SettingsPage;
use Filament\Actions\Action;
use Filament\Forms\Form;
class ManageIndividualCardSettings extends SettingsPage
{
    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';
    protected static ?string $title = "Ustawienia dla kart indywidualnych";
    protected static string $settings = IndividualCardSettings::class;
    protected static ?string $label = "Ustawienia Kart Indywidualnych";
    protected static ?string $navigationLabel = "Karty Indywidualnych";
    protected static ?string $navigationGroup = "Ustawienia";


    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Tabs::make('')
                    ->tabs([
                        Tab::make('Mapowanie')
                            ->schema([
                                Tabs::make('')
                                    ->tabs([
                                        Tab::make('Stałe')
                                            ->schema([
                                                TextInput::make('maping.title')
                                                    ->label("Tytuł")
                                                    ->inlineLabel()
                                                    ->required()
                                                    ->maxLength(2),
                                                TextInput::make('maping.sku')
                                                    ->label("SKU")
                                                    ->inlineLabel()
                                                    ->required()
                                                    ->maxLength(2),
                                                TextInput::make('maping.main_image')
                                                    ->label("Główne zdjęcie")
                                                    ->inlineLabel()
                                                    ->required()
                                                    ->maxLength(2),
                                                TextInput::make('maping.fotometric_image_url')
                                                    ->label("Zdjęcie rozkład światła")
                                                    ->inlineLabel()
                                                    ->required()
                                                    ->maxLength(2),
                                                TextInput::make('maping.dimensions_image_url')
                                                    ->label("Zdjęcie rozmiaru")
                                                    ->inlineLabel()
                                                    ->required()
                                                    ->maxLength(2),
                                            ]),
                                        Tab::make('Ikony')
                                            ->schema([
                                                Repeater::make('maping.icons')
                                                    ->schema([
                                                        TextInput::make('column')
                                                            ->label('Kolumna')
                                                            ->maxLength(2)
                                                            ->required(),
                                                        FileUpload::make('icon')
                                                            ->preserveFilenames()
                                                            ->required()
                                                            ->directory('assets/img')
                                                            ->visibility('public')
                                                            ->label("Ikona")
                                                            ->image(),
                                                    ])
                                            ]),
                                        Tab::make('Ikony zastosowań')->schema([
                                            Repeater::make('maping.header')
                                                ->label("Instalacja")
                                                ->schema([
                                                    TextInput::make('title')
                                                        ->label("Wartość z tabeli"),
                                                    FileUpload::make('image')
                                                        ->preserveFilenames()
                                                        ->directory('assets/img')
                                                        ->visibility('public')
                                                        ->label("Obrazek")
                                                        ->image(),
                                                ]),
                                        ]),
                                        Tab::make('Tabele')
                                            ->schema([
                                                KeyValue::make('maping.table_general_information')
                                                    ->label('Informację ogólne')
                                                    ->editableKeys(false)
                                                    ->required()
                                                    ->rules([
                                                        fn(): \Closure => function (string $attribute, $value, \Closure $fail) {
                                                            foreach ($value as $k => $v) {
                                                                if (empty($v)) {
                                                                    $fail('Wartość dla klucza ' . $k . ' jest pusta.');
                                                                }
                                                            }
                                                        },
                                                    ]),
                                                KeyValue::make('maping.tabel_electrical_information')
                                                    ->label('Dane elektryczne')
                                                    ->editableKeys(false)
                                                    ->required()
                                                    ->rules([
                                                        fn(): \Closure => function (string $attribute, $value, \Closure $fail) {
                                                            foreach ($value as $k => $v) {
                                                                if (empty($v)) {
                                                                    $fail('Wartość dla klucza ' . $k . ' jest pusta.');
                                                                }
                                                            }
                                                        },
                                                    ]),
                                                KeyValue::make('maping.table_psu_information')
                                                    ->label('Inofrmację o zasilaniu')
                                                    ->editableKeys(false)
                                                    ->required()
                                                    ->rules([
                                                        fn(): \Closure => function (string $attribute, $value, \Closure $fail) {
                                                            foreach ($value as $k => $v) {
                                                                if (empty($v)) {
                                                                    $fail('Wartość dla klucza ' . $k . ' jest pusta.');
                                                                }
                                                            }
                                                        },
                                                    ]),
                                                KeyValue::make('maping.table_light_source')
                                                    ->label('Źródło światła')
                                                    ->editableKeys(false)
                                                    ->required()
                                                    ->rules([
                                                        fn(): \Closure => function (string $attribute, $value, \Closure $fail) {
                                                            foreach ($value as $k => $v) {
                                                                if (empty($v)) {
                                                                    $fail('Wartość dla klucza ' . $k . ' jest pusta.');
                                                                }
                                                            }
                                                        },
                                                    ]),
                                                KeyValue::make('maping.table_fotometric_information')
                                                    ->label('Inofrmację Fotometryczne')
                                                    ->editableKeys(false)
                                                    ->required()
                                                    ->rules([
                                                        fn(): \Closure => function (string $attribute, $value, \Closure $fail) {
                                                            foreach ($value as $k => $v) {
                                                                if (empty($v)) {
                                                                    $fail('Wartość dla klucza ' . $k . ' jest pusta.');
                                                                }
                                                            }
                                                        },
                                                    ]),

                                            ]),
                                        Tab::make('Galeria')
                                            ->schema([
                                                TagsInput::make('maping.gallery')
                                                    ->reorderable()
                                                    ->required()
                                                    ->label('Galeria')
                                            ]),
                                        Tab::make('Rozmiary')
                                            ->schema([
                                                KeyValue::make('maping.dimensions')
                                                    ->label('Informację ogólne')
                                                    ->editableKeys(false)
                                                    ->required()
                                                    ->rules([
                                                        fn(): \Closure => function (string $attribute, $value, \Closure $fail) {
                                                            foreach ($value as $k => $v) {
                                                                if (empty($v)) {
                                                                    $fail('Wartość dla klucza ' . $k . ' jest pusta.');
                                                                }
                                                            }
                                                        },
                                                    ]),
                                            ]),
                                        Tab::make('System montażu')
                                            ->schema([
                                                TagsInput::make('maping.mounting_system')
                                                    ->reorderable()
                                                    ->required()
                                                    ->label('System montażu')
                                            ]),
                                    ])
                            ]),
                        Tab::make('Język Polski')
                            ->schema([
                                Tabs::make('')
                                    ->tabs([
                                        Tab::make('Tabela Materiały')
                                            ->schema([
                                                KeyValue::make('pl_maping.table_materials')
                                                    ->label('Tabeka materiały')
                                                    ->editableKeys(false)
                                                    ->required()
                                                    ->rules([
                                                        fn(): \Closure => function (string $attribute, $value, \Closure $fail) {
                                                            foreach ($value as $k => $v) {
                                                                if (empty($v)) {
                                                                    $fail('Wartość dla klucza ' . $k . ' jest pusta.');
                                                                }
                                                            }
                                                        },
                                                    ]),
                                            ]),
                                        Tab::make('Zastosowania')
                                            ->schema([
                                                TagsInput::make('pl_maping.application')
                                                    ->reorderable()
                                                    ->required()
                                                    ->label('System montażu')
                                            ]),
                                        Tab::make('Pliki')
                                            ->schema([
                                                KeyValue::make('pl_maping.files')
                                                    ->label('Pliki')
                                                    ->editableKeys(false)
                                                    ->required()
                                                    ->rules([
                                                        fn(): \Closure => function (string $attribute, $value, \Closure $fail) {
                                                            foreach ($value as $k => $v) {
                                                                if (empty($v)) {
                                                                    $fail('Wartość dla klucza ' . $k . ' jest pusta.');
                                                                }
                                                            }
                                                        },
                                                    ]),
                                            ]),
                                        Tab::make('Opis')->schema([
                                            RichEditor::make('pl_description')
                                                ->label("OPIS"),
                                        ]),
                                        Tab::make('Tłumaczenia')
                                            ->schema([
                                                KeyValue::make('pl_fixed_translations')
                                                    ->label('Tłumaczenia')
                                                    ->editableKeys(false)
                                                    ->required()
                                                    ->rules([
                                                        fn(): \Closure => function (string $attribute, $value, \Closure $fail) {
                                                            foreach ($value as $k => $v) {
                                                                if (empty($v)) {
                                                                    $fail('Wartość dla klucza ' . $k . ' jest pusta.');
                                                                }
                                                            }
                                                        },
                                                    ]),
                                            ]),
                                        Tab::make("Linki")
                                            ->schema([
                                                KeyValue::make('pl_links')
                                                    ->label('Linki')
                                                    ->required()
                                                    ->rules([
                                                        fn(): \Closure => function (string $attribute, $value, \Closure $fail) {
                                                            foreach ($value as $k => $v) {
                                                                if (empty($v)) {
                                                                    $fail('Wartość dla klucza ' . $k . ' jest pusta.');
                                                                }
                                                            }
                                                        },
                                                    ]),
                                            ]),

                                        Tab::make('Stałe')
                                            ->schema([
                                                TextInput::make('pl_maping.short_description')
                                                    ->label("Krótki opis")
                                                    ->inlineLabel()
                                                    ->required()

                                            ]),
                                        Tab::make("Montaż")
                                            ->schema([
                                                KeyValue::make('pl_montage')
                                                    ->label('Montaż')
                                                    ->required()
                                                    ->rules([
                                                        fn(): \Closure => function (string $attribute, $value, \Closure $fail) {
                                                            foreach ($value as $k => $v) {
                                                                if (empty($v)) {
                                                                    $fail('Wartość dla klucza ' . $k . ' jest pusta.');
                                                                }
                                                            }
                                                        },
                                                    ]),
                                            ]),
                                    ])
                            ]),
                        Tab::make('Język Angielski')
                            ->schema([
                                Tabs::make('')
                                    ->tabs([
                                        Tab::make('Tabela Materiały')
                                            ->schema([
                                                KeyValue::make('en_maping.table_materials')
                                                    ->label('Tabeka materiały')
                                                    ->editableKeys(false)
                                                    ->required()
                                                    ->rules([
                                                        fn(): \Closure => function (string $attribute, $value, \Closure $fail) {
                                                            foreach ($value as $k => $v) {
                                                                if (empty($v)) {
                                                                    $fail('Wartość dla klucza ' . $k . ' jest pusta.');
                                                                }
                                                            }
                                                        },
                                                    ]),
                                            ]),
                                        Tab::make('Zastosowania')
                                            ->schema([
                                                TagsInput::make('en_maping.application')
                                                    ->reorderable()
                                                    ->required()
                                                    ->label('System montażu')
                                            ]),
                                        Tab::make('Pliki')
                                            ->schema([
                                                KeyValue::make('en_maping.files')
                                                    ->label('Pliki')
                                                    ->editableKeys(false)
                                                    ->required()
                                                    ->rules([
                                                        fn(): \Closure => function (string $attribute, $value, \Closure $fail) {
                                                            foreach ($value as $k => $v) {
                                                                if (empty($v)) {
                                                                    $fail('Wartość dla klucza ' . $k . ' jest pusta.');
                                                                }
                                                            }
                                                        },
                                                    ]),
                                            ]),
                                        Tab::make('Opis')->schema([
                                            RichEditor::make('en_description')
                                                ->label("OPIS"),
                                        ]),
                                        Tab::make('Tłumaczenia')
                                            ->schema([
                                                KeyValue::make('en_fixed_translations')
                                                    ->label('Tłumaczenia')
                                                    ->editableKeys(false)
                                                    ->required()
                                                    ->rules([
                                                        fn(): \Closure => function (string $attribute, $value, \Closure $fail) {
                                                            foreach ($value as $k => $v) {
                                                                if (empty($v)) {
                                                                    $fail('Wartość dla klucza ' . $k . ' jest pusta.');
                                                                }
                                                            }
                                                        },
                                                    ]),
                                            ]),

                                        Tab::make("Linki")
                                            ->schema([
                                                KeyValue::make('en_links')
                                                    ->label('Linki')
                                                    ->required()
                                                    ->rules([
                                                        fn(): \Closure => function (string $attribute, $value, \Closure $fail) {
                                                            foreach ($value as $k => $v) {
                                                                if (empty($v)) {
                                                                    $fail('Wartość dla klucza ' . $k . ' jest pusta.');
                                                                }
                                                            }
                                                        },
                                                    ]),
                                            ]),
                                        Tab::make('Stałe')
                                            ->schema([
                                                TextInput::make('en_maping.short_description')
                                                    ->label("Krótki opis")
                                                    ->inlineLabel()
                                                    ->required()

                                            ]),

                                        Tab::make("Montaż")
                                            ->schema([
                                                KeyValue::make('en_montage')
                                                    ->label('Montaż')
                                                    ->required()
                                                    ->rules([
                                                        fn(): \Closure => function (string $attribute, $value, \Closure $fail) {
                                                            foreach ($value as $k => $v) {
                                                                if (empty($v)) {
                                                                    $fail('Wartość dla klucza ' . $k . ' jest pusta.');
                                                                }
                                                            }
                                                        },
                                                    ]),
                                            ]),
                                    ])
                            ]),
                        Tab::make('Język Niemiecki')
                            ->schema([
                                Tabs::make('')
                                    ->tabs([
                                        Tab::make('Tabela Materiały')
                                            ->schema([
                                                KeyValue::make('de_maping.table_materials')
                                                    ->label('Tabeka materiały')
                                                    ->editableKeys(false)
                                                    ->required()
                                                    ->rules([
                                                        fn(): \Closure => function (string $attribute, $value, \Closure $fail) {
                                                            foreach ($value as $k => $v) {
                                                                if (empty($v)) {
                                                                    $fail('Wartość dla klucza ' . $k . ' jest pusta.');
                                                                }
                                                            }
                                                        },
                                                    ]),
                                            ]),
                                        Tab::make('Zastosowania')
                                            ->schema([
                                                TagsInput::make('de_maping.application')
                                                    ->reorderable()
                                                    ->required()
                                                    ->label('System montażu')
                                            ]),
                                        Tab::make('Pliki')
                                            ->schema([
                                                KeyValue::make('de_maping.files')
                                                    ->label('Pliki')
                                                    ->editableKeys(false)
                                                    ->required()
                                                    ->rules([
                                                        fn(): \Closure => function (string $attribute, $value, \Closure $fail) {
                                                            foreach ($value as $k => $v) {
                                                                if (empty($v)) {
                                                                    $fail('Wartość dla klucza ' . $k . ' jest pusta.');
                                                                }
                                                            }
                                                        },
                                                    ]),
                                            ]),
                                        Tab::make('Opis')->schema([
                                            RichEditor::make('de_description')
                                                ->label("OPIS"),
                                        ]),
                                        Tab::make('Tłumaczenia')
                                            ->schema([
                                                KeyValue::make('de_fixed_translations')
                                                    ->label('Tłumaczenia')
                                                    ->editableKeys(false)
                                                    ->required()
                                                    ->rules([
                                                        fn(): \Closure => function (string $attribute, $value, \Closure $fail) {
                                                            foreach ($value as $k => $v) {
                                                                if (empty($v)) {
                                                                    $fail('Wartość dla klucza ' . $k . ' jest pusta.');
                                                                }
                                                            }
                                                        },
                                                    ]),
                                            ]),
                                        Tab::make("Linki")
                                            ->schema([
                                                KeyValue::make('de_links')
                                                    ->label('Linki')
                                                    ->required()
                                                    ->rules([
                                                        fn(): \Closure => function (string $attribute, $value, \Closure $fail) {
                                                            foreach ($value as $k => $v) {
                                                                if (empty($v)) {
                                                                    $fail('Wartość dla klucza ' . $k . ' jest pusta.');
                                                                }
                                                            }
                                                        },
                                                    ]),
                                            ]),
                                        Tab::make('Stałe')
                                            ->schema([
                                                TextInput::make('de_maping.short_description')
                                                    ->label("Krótki opis")
                                                    ->inlineLabel()
                                                    ->required()

                                            ]),
                                        Tab::make("Montaż")
                                            ->schema([
                                                KeyValue::make('de_montage')
                                                    ->label('Montaż')
                                                    ->required()
                                                    ->rules([
                                                        fn(): \Closure => function (string $attribute, $value, \Closure $fail) {
                                                            foreach ($value as $k => $v) {
                                                                if (empty($v)) {
                                                                    $fail('Wartość dla klucza ' . $k . ' jest pusta.');
                                                                }
                                                            }
                                                        },
                                                    ]),
                                            ]),
                                    ]),
                            ]),
                        Tab::make('Ścieki FTP')
                            ->schema([
                                TextInput::make("ftp_html_path_polish")
                                    ->label('Ścieżka zapisu plików HTML dla kart indywidualnych Język Polski')
                                    ->required()
                                    ->inlineLabel()
                                    ->regex('/^\/([a-zA-Z0-9_\s]+\/)*$/'),
                                    TextInput::make("ftp_pdf_path_polish")
                                        ->label('Ścieżka zapisu plików PDF dla kart indywidualnych Język Polski')
                                        ->required()
                                        ->inlineLabel()
                                        ->regex('/^\/([a-zA-Z0-9_\s]+\/)*$/'),
                                TextInput::make("ftp_html_path_english")
                                    ->label('Ścieżka zapisu plików HTML dla kart indywidualnych Język Angielski')
                                    ->required()
                                    ->inlineLabel()
                                    ->regex('/^\/([a-zA-Z0-9_\s]+\/)*$/'),
                                    TextInput::make("ftp_pdf_path_english")
                                        ->label('Ścieżka zapisu plików PDF dla kart indywidualnych Język Angielski')
                                        ->required()
                                        ->inlineLabel()
                                        ->regex('/^\/([a-zA-Z0-9_\s]+\/)*$/'),
                                TextInput::make("ftp_html_path_deutch")
                                    ->label('Ścieżka zapisu plików HTML dla kart indywidualnych Język Niemiecki')
                                    ->required()
                                    ->inlineLabel()
                                    ->regex('/^\/([a-zA-Z0-9_\s]+\/)*$/'),
                                TextInput::make("ftp_pdf_path_deutch")
                                    ->label('Ścieżka zapisu plików PDF dla kart indywidualnych Język Niemiecki')
                                    ->required()
                                    ->inlineLabel()
                                    ->regex('/^\/([a-zA-Z0-9_\s]+\/)*$/'),
                            ])
                    ])
            ])->columns(1);

    }
}
