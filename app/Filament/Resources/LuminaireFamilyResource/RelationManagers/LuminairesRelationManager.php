<?php

namespace App\Filament\Resources\LuminaireFamilyResource\RelationManagers;

use App\Filament\Resources\LuminaireResource\Actions\BulkGenerateIndividualCard;
use App\Filament\Resources\LuminaireResource\Actions\GenerateIndividualCard;
use App\Infolists\Components\FileButton;
use App\Models\Luminaire;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Columns\ViewColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class LuminairesRelationManager extends RelationManager
{
    protected static string $relationship = 'luminaires';
    protected static ?string $title = "Poszczególne lampy";
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),

            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label("Nazwa")
                    ->searchable(),
                ViewColumn::make('html_filepath')->view('filament.column.file-button')
                    ->label("Ścieżka do pliku HTML"),
                ViewColumn::make('pdf_filepath')->view('filament.column.file-button')
                    ->label("Ścieżka do pliku PDF")
            ])
            ->filters([
                //
            ])
            ->headerActions([
                //
                BulkGenerateIndividualCard::make(),
            ])
            ->actions([
                GenerateIndividualCard::make()
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    // Tables\Actions\DeleteBulkAction::make(),
                    BulkGenerateIndividualCard::make(),
                ]),
            ])
            ->poll('5s');
        ;
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('visible', '=', 1);
    }

}
