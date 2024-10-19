<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LuminaireFamilyResource\Pages;
use App\Filament\Resources\LuminaireFamilyResource\RelationManagers;
use App\Filament\Resources\LuminaireFamilyResource\RelationManagers\LuminairesRelationManager;
use App\Filament\Resources\LuminaireResource\Actions\BulkGenerateFamilyCard;
use App\Models\LuminaireFamily;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ViewColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class LuminaireFamilyResource extends Resource
{
    protected static ?string $model = LuminaireFamily::class;
    protected static ?string $label = "Rodzina Opraw";
    protected static ?string $pluralLabel = 'Rodziny Opraw';
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-group';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Rodzina')
                    ->sortable()
                    ->searchable(),
                ViewColumn::make('html_filepath')->view('filament.column.file-button')
                    ->label("Ścieżka do pliku HTML"),
                ViewColumn::make('pdf_filepath')->view('filament.column.file-button')
                    ->label("Ścieżka do pliku PDF"),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->label("Data pobrania"),
            ])
            ->filters([
            ])
            ->headerActions([
                BulkGenerateFamilyCard::make()
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                BulkGenerateFamilyCard::make()
            ])
            ->poll('5s');
        ;
    }

    public static function getRelations(): array
    {
        return [
            LuminairesRelationManager::class,

        ];
    }

    public static function getPages(): array
    {
        return [
            'view' => Pages\ViewLuminaireFamily::route('/{record}'),
            'index' => Pages\ListLuminaireFamilies::route('/'),
        ];
    }
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('visible', '=', 1);
    }

}
