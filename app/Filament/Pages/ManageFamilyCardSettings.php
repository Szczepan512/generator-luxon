<?php

namespace App\Filament\Pages;

use App\Settings\FamilyCardSettings;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Pages\Page;
use Filament\Pages\SettingsPage;
use Filament\Actions\Action;
use Filament\Forms\Form;
class ManageFamilyCardSettings extends SettingsPage
{
    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';
    protected static ?string $title = "Ustawienia dla kart rodziny";
    protected static string $settings = FamilyCardSettings::class;
    protected static ?string $label = "Ustawienia Kart Rodziny";
    protected static ?string $navigationLabel = "Karty Rodziny";
    protected static ?string $navigationGroup = "Ustawienia";


    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Tabs::make('Ustawienia')
                    ->tabs([
                        Tab::make('Mapowanie')
                            ->schema([
                                TextInput::make('maping.sku')
                                    ->label('SKU')
                                    ->maxLength(2)
                                    ->required(),
                                TextInput::make('maping.title')
                                    ->label('Tytuł')
                                    ->maxLength(2)
                                    ->required(),
                                TextInput::make('maping.thumbnail')
                                    ->label('Obrazek główny')
                                    ->maxLength(2)
                                    ->required(),
                                TextInput::make('maping.installation_image')
                                    ->label('Zdjęcie instalacji')
                                    ->maxLength(2)
                                    ->required(),
                                Repeater::make('maping.installation')
                                    ->label("Instalacja")
                                    ->schema([
                                        TextInput::make('slug')
                                            ->label('Nazwa')
                                            ->required(),
                                        TextInput::make('column')
                                            ->label("Kolumna")
                                            ->maxLength(2)
                                            ->required(),
                                        FileUpload::make('icon')
                                            ->preserveFilenames()
                                            ->directory('assets/img')
                                            ->visibility('public')
                                            ->label("Ikona")
                                            ->image(),
                                        FileUpload::make(name: 'blueprint')
                                            ->preserveFilenames()
                                            ->directory('assets/img')
                                            ->visibility('public')
                                            ->label("Przekrój")
                                            ->image(),
                                    ]),
                                TagsInput::make('maping.header')
                                    ->reorderable()
                                    ->required()
                                    ->label('Nagłówki'),
                                Repeater::make('maping.icons_text')
                                    ->label('Ikony tekstowe')
                                    ->schema([
                                        TextInput::make('slug')
                                            ->label('Nazwa')
                                            ->required(),
                                        TextInput::make('column')
                                            ->label("Kolumna")
                                            ->maxLength(2)
                                            ->required(),
                                    ]),
                                Repeater::make('maping.icons')
                                    ->label("Ikony")
                                    ->schema([
                                        TextInput::make('slug')
                                            ->label('Nazwa')
                                            ->required(),
                                        TextInput::make('column')
                                            ->label("Kolumna")
                                            ->maxLength(2)
                                            ->required(),
                                    ]),
                                KeyValue::make("maping.dimensions")
                                    ->deletable(false)
                                    ->label('Rozmiary')
                                    ->addable(false)
                                    ->editableKeys(false),
                                TextInput::make('maping.dimensions_image')
                                    ->label('Zdjęcie rozmiary')
                                    ->maxLength(2)
                                    ->required(),
                                KeyValue::make("maping.package")
                                    ->deletable(false)
                                    ->label('Pakowanie')
                                    ->addable(false)
                                    ->editableKeys(false),

                            ]),
                        Tab::make('Język Polski')
                            ->schema([
                                Tabs::make('')->tabs([
                                    Tab::make('Mapowanie')->schema([
                                        TagsInput::make('pl_maping')
                                            ->reorderable()
                                            ->required()
                                            ->label('Zalety'),
                                    ]),
                                    Tab::make('Tłumaczenia')->schema([
                                        KeyValue::make('pl_translations')
                                            ->label('Tłumaczenia')
                                            ->deletable(false)
                                            ->addable(false)
                                            ->editableKeys(false),
                                    ]),
                                    Tab::make('Kolumny')->schema([
                                        KeyValue::make("pl_installations")
                                            ->label('Instalację tłumaczenie'),
                                    ]),
                                    Tab::make('Konfigurator')->schema([
                                        KeyValue::make('pl_configurator.3')
                                            ->required()
                                            ->rules([
                                                fn(): \Closure => function (string $attribute, $value, \Closure $fail) {
                                                    foreach ($value as $k => $v) {
                                                        if (empty($v)) {
                                                            $fail('Wartość dla klucza ' . $k . ' jest pusta.');
                                                        }
                                                    }
                                                },
                                            ])
                                            ->label('Kolumna 3 -> Wersja'),
                                        KeyValue::make('pl_configurator.6')
                                            ->required()
                                            ->rules([
                                                fn(): \Closure => function (string $attribute, $value, \Closure $fail) {
                                                    foreach ($value as $k => $v) {
                                                        if (empty($v)) {
                                                            $fail('Wartość dla klucza ' . $k . ' jest pusta.');
                                                        }
                                                    }
                                                },
                                            ])
                                            ->label('Kolumna 6 -> Typ diody'),
                                        KeyValue::make('pl_configurator.8')
                                            ->required()
                                            ->rules([
                                                fn(): \Closure => function (string $attribute, $value, \Closure $fail) {
                                                    foreach ($value as $k => $v) {
                                                        if (empty($v)) {
                                                            $fail('Wartość dla klucza ' . $k . ' jest pusta.');
                                                        }
                                                    }
                                                },
                                            ])
                                            ->label('Kolumna 8 -> Materiał soczewki'),
                                        KeyValue::make('pl_configurator.9')
                                            ->required()
                                            ->rules([
                                                fn(): \Closure => function (string $attribute, $value, \Closure $fail) {
                                                    foreach ($value as $k => $v) {
                                                        if (empty($v)) {
                                                            $fail('Wartość dla klucza ' . $k . ' jest pusta.');
                                                        }
                                                    }
                                                },
                                            ])
                                            ->label('Kolumna 9 -> Materiał dyfuzora'),
                                        KeyValue::make('pl_configurator.11')
                                            ->required()
                                            ->rules([
                                                fn(): \Closure => function (string $attribute, $value, \Closure $fail) {
                                                    foreach ($value as $k => $v) {
                                                        if (empty($v)) {
                                                            $fail('Wartość dla klucza ' . $k . ' jest pusta.');
                                                        }
                                                    }
                                                },
                                            ])
                                            ->label('Kolumna 11 -> Sterowanie'),
                                        KeyValue::make('pl_configurator.14')
                                            ->required()
                                            ->rules([
                                                fn(): \Closure => function (string $attribute, $value, \Closure $fail) {
                                                    foreach ($value as $k => $v) {
                                                        if (empty($v)) {
                                                            $fail('Wartość dla klucza ' . $k . ' jest pusta.');
                                                        }
                                                    }
                                                },
                                            ])
                                            ->label('Kolumna 14 -> Modyfikacje'),
                                        TextInput::make(name: 'pl_configurator.13')
                                            ->required()
                                            ->label('Kolumna 13'),
                                    ]),
                                ])
                                    ->activeTab(request()->query('subtab', 1)),

                            ]),
                        Tab::make('Język Niemiecki')
                            ->schema([
                                Tabs::make('')->tabs([
                                    Tab::make('Mapowanie')->schema([
                                        TagsInput::make('de_maping')
                                            ->reorderable()
                                            ->required()
                                            ->label('Zalety'),
                                    ]),
                                    Tab::make('Tłumaczenia')->schema([
                                        KeyValue::make('de_translations')
                                            ->label('Tłumaczenia')
                                            ->deletable(false)
                                            ->addable(false)
                                            ->editableKeys(false),
                                    ]),
                                    Tab::make('Kolumny')->schema([
                                        KeyValue::make("de_installations")
                                            ->label('Instalację tłumaczenie'),
                                    ]),
                                    Tab::make('Konfigurator')->schema([
                                        KeyValue::make('de_configurator.3')
                                            ->required()
                                            ->rules([
                                                fn(): \Closure => function (string $attribute, $value, \Closure $fail) {
                                                    foreach ($value as $k => $v) {
                                                        if (empty($v)) {
                                                            $fail('Wartość dla klucza ' . $k . ' jest pusta.');
                                                        }
                                                    }
                                                },
                                            ])
                                            ->label('Kolumna 3 -> Wersja'),
                                        KeyValue::make('de_configurator.6')
                                            ->required()
                                            ->rules([
                                                fn(): \Closure => function (string $attribute, $value, \Closure $fail) {
                                                    foreach ($value as $k => $v) {
                                                        if (empty($v)) {
                                                            $fail('Wartość dla klucza ' . $k . ' jest pusta.');
                                                        }
                                                    }
                                                },
                                            ])
                                            ->label('Kolumna 6 -> Typ diody'),
                                        KeyValue::make('de_configurator.8')
                                            ->required()
                                            ->rules([
                                                fn(): \Closure => function (string $attribute, $value, \Closure $fail) {
                                                    foreach ($value as $k => $v) {
                                                        if (empty($v)) {
                                                            $fail('Wartość dla klucza ' . $k . ' jest pusta.');
                                                        }
                                                    }
                                                },
                                            ])
                                            ->label('Kolumna 8 -> Materiał soczewki'),
                                        KeyValue::make('de_configurator.9')
                                            ->required()
                                            ->rules([
                                                fn(): \Closure => function (string $attribute, $value, \Closure $fail) {
                                                    foreach ($value as $k => $v) {
                                                        if (empty($v)) {
                                                            $fail('Wartość dla klucza ' . $k . ' jest pusta.');
                                                        }
                                                    }
                                                },
                                            ])
                                            ->label('Kolumna 9 -> Materiał dyfuzora'),
                                        KeyValue::make('de_configurator.11')
                                            ->required()
                                            ->rules([
                                                fn(): \Closure => function (string $attribute, $value, \Closure $fail) {
                                                    foreach ($value as $k => $v) {
                                                        if (empty($v)) {
                                                            $fail('Wartość dla klucza ' . $k . ' jest pusta.');
                                                        }
                                                    }
                                                },
                                            ])
                                            ->label('Kolumna 11 -> Sterowanie'),
                                        KeyValue::make('de_configurator.14')
                                            ->required()
                                            ->rules([
                                                fn(): \Closure => function (string $attribute, $value, \Closure $fail) {
                                                    foreach ($value as $k => $v) {
                                                        if (empty($v)) {
                                                            $fail('Wartość dla klucza ' . $k . ' jest pusta.');
                                                        }
                                                    }
                                                },
                                            ])
                                            ->label('Kolumna 14 -> Modyfikacje'),
                                        TextInput::make(name: 'de_configurator.13')
                                            ->required()
                                            ->label('Kolumna 13'),
                                    ]),
                                ])
                            ]),
                        Tab::make('Język Angielski')
                            ->schema([
                                Tabs::make('')->tabs([
                                    Tab::make('Mapowanie')->schema([
                                        TagsInput::make('en_maping')
                                            ->reorderable()
                                            ->required()
                                            ->label('Zalety'),
                                    ]),
                                    Tab::make('Tłumaczenia')->schema([
                                        KeyValue::make('en_translations')
                                            ->label('Tłumaczenia')
                                            ->deletable(false)
                                            ->addable(false)
                                            ->editableKeys(false),
                                    ]),
                                    Tab::make('Kolumny')->schema([
                                        KeyValue::make("en_installations")
                                            ->label('Instalację tłumaczenie'),
                                    ]),
                                    Tab::make('Konfigurator')->schema([
                                        KeyValue::make('en_configurator.3')
                                            ->required()
                                            ->rules([
                                                fn(): \Closure => function (string $attribute, $value, \Closure $fail) {
                                                    foreach ($value as $k => $v) {
                                                        if (empty($v)) {
                                                            $fail('Wartość dla klucza ' . $k . ' jest pusta.');
                                                        }
                                                    }
                                                },
                                            ])
                                            ->label('Kolumna 3 -> Wersja'),
                                        KeyValue::make('en_configurator.6')
                                            ->required()
                                            ->rules([
                                                fn(): \Closure => function (string $attribute, $value, \Closure $fail) {
                                                    foreach ($value as $k => $v) {
                                                        if (empty($v)) {
                                                            $fail('Wartość dla klucza ' . $k . ' jest pusta.');
                                                        }
                                                    }
                                                },
                                            ])
                                            ->label('Kolumna 6 -> Typ diody'),
                                        KeyValue::make('en_configurator.8')
                                            ->required()
                                            ->rules([
                                                fn(): \Closure => function (string $attribute, $value, \Closure $fail) {
                                                    foreach ($value as $k => $v) {
                                                        if (empty($v)) {
                                                            $fail('Wartość dla klucza ' . $k . ' jest pusta.');
                                                        }
                                                    }
                                                },
                                            ])
                                            ->label('Kolumna 8 -> Materiał soczewki'),
                                        KeyValue::make('en_configurator.9')
                                            ->required()
                                            ->rules([
                                                fn(): \Closure => function (string $attribute, $value, \Closure $fail) {
                                                    foreach ($value as $k => $v) {
                                                        if (empty($v)) {
                                                            $fail('Wartość dla klucza ' . $k . ' jest pusta.');
                                                        }
                                                    }
                                                },
                                            ])
                                            ->label('Kolumna 9 -> Materiał dyfuzora'),
                                        KeyValue::make('en_configurator.11')
                                            ->required()
                                            ->rules([
                                                fn(): \Closure => function (string $attribute, $value, \Closure $fail) {
                                                    foreach ($value as $k => $v) {
                                                        if (empty($v)) {
                                                            $fail('Wartość dla klucza ' . $k . ' jest pusta.');
                                                        }
                                                    }
                                                },
                                            ])
                                            ->label('Kolumna 11 -> Sterowanie'),
                                        KeyValue::make('en_configurator.14')
                                            ->required()
                                            ->rules([
                                                fn(): \Closure => function (string $attribute, $value, \Closure $fail) {
                                                    foreach ($value as $k => $v) {
                                                        if (empty($v)) {
                                                            $fail('Wartość dla klucza ' . $k . ' jest pusta.');
                                                        }
                                                    }
                                                },
                                            ])
                                            ->label('Kolumna 14 -> Modyfikacje'),
                                        TextInput::make(name: 'en_configurator.13')
                                            ->required()
                                            ->label('Kolumna 13'),
                                    ]),
                                ])
                            ]),
                        Tab::make("Ikony")
                            ->schema([
                                FileUpload::make('icons.logo')
                                    ->preserveFilenames()
                                    ->directory('assets/img')
                                    ->visibility('public')
                                    ->label("Logo")
                                    ->image(),
                                FileUpload::make("icons.IND")
                                    ->preserveFilenames()
                                    ->directory('assets/img')
                                    ->visibility('public')
                                    ->label("IND")
                                    ->image(),
                                Repeater::make('icons.header')
                                    ->label('Nagłówki')
                                    ->schema([
                                        TextInput::make('title')
                                            ->label("Wartość z tabeli"),
                                        FileUpload::make('image')
                                            ->preserveFilenames()
                                            ->directory('assets/img')
                                            ->visibility('public')
                                            ->label("Obrazek")
                                            ->image(),
                                    ])->columns(2),

                                Repeater::make('icons.icons')
                                    ->label('Nagłówki')
                                    ->schema([
                                        TextInput::make('title')
                                            ->label("Tytuł"),
                                        Repeater::make('values')
                                            ->schema([
                                                TextInput::make('value')
                                                    ->label("Wartość"),
                                                FileUpload::make('icon')
                                                    ->preserveFilenames()
                                                    ->directory('assets/img')
                                                    ->visibility('public')
                                                    ->label("Obrazek")
                                                    ->image(),
                                            ])
                                    ])->columns(2),
                            ])
                    ])
                    ->activeTab(request()->query('tab', 1)),
                //
            ])
            ->columns(1);
        ;

    }
}
