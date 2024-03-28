<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AyatResource\Pages;
use App\Filament\Resources\AyatResource\RelationManagers;
use App\Models\Ayat;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AyatResource extends Resource
{
    protected static ?string $model = Ayat::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    protected static ?string $navigationGroup = 'Quran';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('sura_id')
                    ->required(),
                Forms\Components\TextInput::make('ayat_no')
                    ->required(),
                Forms\Components\TextInput::make('arabic_text')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('bangla_text')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('english_text')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('meaning')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('reference')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('notes')
                    ->required()
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
                Tables\Columns\TextColumn::make('sura.name'),
                Tables\Columns\TextColumn::make('ayat_no'),
                Tables\Columns\TextColumn::make('arabic_text'),
                Tables\Columns\TextColumn::make('bangla_text'),
                Tables\Columns\TextColumn::make('english_text'),
                Tables\Columns\TextColumn::make('meaning'),
                Tables\Columns\TextColumn::make('reference'),
                Tables\Columns\TextColumn::make('notes'),
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
            'index' => Pages\ListAyats::route('/'),
            'create' => Pages\CreateAyat::route('/create'),
            'edit' => Pages\EditAyat::route('/{record}/edit'),
        ];
    }
}
