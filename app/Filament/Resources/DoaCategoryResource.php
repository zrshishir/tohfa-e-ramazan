<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DoaCategoryResource\Pages;
use App\Filament\Resources\DoaCategoryResource\RelationManagers;
use App\Models\DoaCategory;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DoaCategoryResource extends Resource
{
    protected static ?string $model = DoaCategory::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

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
                Tables\Actions\ViewAction::make(),
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
            'index' => Pages\ListDoaCategories::route('/'),
            'create' => Pages\CreateDoaCategory::route('/create'),
            'view' => Pages\ViewDoaCategory::route('/{record}'),
            'edit' => Pages\EditDoaCategory::route('/{record}/edit'),
        ];
    }    
}
