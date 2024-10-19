<?php

namespace App\Filament\Pages;

use App\Jobs\UpdateGoogleSheetJob;
use App\Settings\SheetSettings;
use Filament\Actions\Action;
use Filament\Forms;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\SettingsPage;
use Livewire\Livewire;
use Notification;

class ManageSheetSettings extends SettingsPage
{
    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';
    protected static ?string $title = "Główne ustawienia aplikacji";
    protected static string $settings = SheetSettings::class;
    protected static ?string $label = "Ustawienia";
    protected static ?string $navigationLabel = "Główne";
    protected static ?string $navigationGroup = "Ustawienia";
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Tabs::make()
                    ->tabs([
                        Tab::make('Identyfikatory Arkuszy')
                            ->schema([
                                Repeater::make('sheets_ids')
                                    ->schema([
                                        TextInput::make('sheet_id')
                                            ->label('Identyfikator Arkusza Google')
                                    ])
                                    ->collapsible()
                                    ->label("Numery Identyfikator Arkuszy Google")
                                    ->addActionLabel('Dodaj Identyfikator Arkusza')
                                    ->helperText('Po zmianie Identyfikatorów zapisz i naciśnij odśwież bazę danych'),
                            ]),
                        Tab::make("Inne")
                            ->schema([
                                KeyValue::make('sheet_headings')
                                    ->addable(false)
                                    ->deletable(false)
                                    ->editableKeys(false),
                                KeyValue::make('sheet_template_heading')
                            ]),
                    ])


            ])->columns(1);
    }

    protected function getActions(): array
    {
        return [
            Action::make('googleUpdate')
                ->label('Pobierz oprawy z API')
                ->requiresConfirmation()
                ->action(function () {
                    UpdateGoogleSheetJob::dispatch();
                    \Filament\Notifications\Notification::make()
                        ->title( 'Rozpoczęcie pobierania opraw')
                        ->info()
                        ->send();
                })
                ->color('primary'),
        ];
    }
}
