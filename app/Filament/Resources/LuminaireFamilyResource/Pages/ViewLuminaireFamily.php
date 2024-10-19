<?php

namespace App\Filament\Resources\LuminaireFamilyResource\Pages;

use App\Filament\Resources\LuminaireFamilyResource;
use App\Filament\Resources\LuminaireResource\Actions\GenerateIndividualCard;
use App\Infolists\Components\FileButton;
use Filament\Actions;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists;
use App\Filament\Resources\LuminaireResource\Actions\GenerateFamilyCard;
use Filament\Infolists\Infolist;

class ViewLuminaireFamily extends ViewRecord
{
    protected static string $resource = LuminaireFamilyResource::class;

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('Ogólne')
                    ->schema([
                        TextEntry::make('name')
                            ->label("Nazwa Rodziny"),
                        FileButton::make('html_filepath')
                        ->label("Ścieżka do pliku HTML PL"),
                        FileButton::make('pdf_filepath')
                            ->label("Ścieżka do pliku PDF"),


                    ])->columns(3)
            ])
            ;
    }
    public  function getActions(): array
    {
        return [
            GenerateFamilyCard::make(),
            // inne akcje...
        ];
    }
}
