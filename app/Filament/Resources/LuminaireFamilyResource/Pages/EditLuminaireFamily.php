<?php

namespace App\Filament\Resources\LuminaireFamilyResource\Pages;

use App\Filament\Resources\LuminaireFamilyResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditLuminaireFamily extends EditRecord
{
    protected static string $resource = LuminaireFamilyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
