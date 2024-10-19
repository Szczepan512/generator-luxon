<?php

namespace App\Filament\Resources\LuminaireFamilyResource\Pages;

use App\Filament\Resources\LuminaireFamilyResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListLuminaireFamilies extends ListRecords
{
    protected static string $resource = LuminaireFamilyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
