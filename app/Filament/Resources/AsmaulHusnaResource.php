<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AsmaulHusnaResource\Pages;
use App\Models\AsmaulHusna;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;

class AsmaulHusnaResource extends Resource
{
    protected static ?string $model = AsmaulHusna::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    protected static ?string $navigationGroup = 'Asmaul Husna';

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
                //
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListAsmaulHusnas::route('/'),
            'create' => Pages\CreateAsmaulHusna::route('/create'),
            'edit'   => Pages\EditAsmaulHusna::route('/{record}/edit'),
        ];
    }
}
