<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TasbihResource\Pages;
use App\Filament\Resources\TasbihResource\RelationManagers;
use App\Models\Tasbih;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TasbihResource extends Resource
{
    protected static ?string $model = Tasbih::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    protected static ?string $navigationGroup = 'Tasbeeh';

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
            'index' => Pages\ListTasbihs::route('/'),
            'create' => Pages\CreateTasbih::route('/create'),
            'edit' => Pages\EditTasbih::route('/{record}/edit'),
        ];
    }
}