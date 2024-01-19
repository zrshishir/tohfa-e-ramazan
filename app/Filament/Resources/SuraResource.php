<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SuraResource\Pages;
use App\Filament\Resources\SuraResource\RelationManagers;
use App\Models\Sura;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SuraResource extends Resource
{
    protected static ?string $model = Sura::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    protected static ?string $navigationGroup = 'Quran';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('arabic_name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('english_name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('meaning')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('ayat_count')
                    ->maxLength(255),
                Forms\Components\TextInput::make('type')
                    ->maxLength(255),
                Forms\Components\TextInput::make('revelation_order')
                    ->maxLength(255),
                Forms\Components\TextInput::make('rukus')
                    ->maxLength(255),
                Forms\Components\TextInput::make('place_of_revelation')
                    ->maxLength(255),
                Forms\Components\TextInput::make('status')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('audio')
                    ->maxLength(255),
                Forms\Components\TextInput::make('video')
                    ->maxLength(255),
                Forms\Components\TextInput::make('image')
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('arabic_name'),
                Tables\Columns\TextColumn::make('english_name'),
                Tables\Columns\TextColumn::make('meaning'),
                Tables\Columns\TextColumn::make('ayat_count'),
                Tables\Columns\TextColumn::make('type'),
                Tables\Columns\TextColumn::make('revelation_order'),
                Tables\Columns\TextColumn::make('rukus'),
                Tables\Columns\TextColumn::make('place_of_revelation'),
                Tables\Columns\TextColumn::make('status'),
                Tables\Columns\TextColumn::make('audio'),
                Tables\Columns\TextColumn::make('video'),
                Tables\Columns\TextColumn::make('image'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime(),
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
            'index' => Pages\ListSuras::route('/'),
            'create' => Pages\CreateSura::route('/create'),
            'edit' => Pages\EditSura::route('/{record}/edit'),
        ];
    }
}