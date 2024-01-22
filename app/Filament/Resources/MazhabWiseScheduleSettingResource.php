<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MazhabWiseScheduleSettingResource\Pages;
use App\Filament\Resources\MazhabWiseScheduleSettingResource\RelationManagers;
use App\Models\MazhabWiseScheduleSetting;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MazhabWiseScheduleSettingResource extends Resource
{
    protected static ?string $model = MazhabWiseScheduleSetting::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    protected static ?string $navigationGroup = 'Mazhab';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('mazhab_id')
                    ->required(),
                Forms\Components\TextInput::make('sehri_time')
                    ->required(),
                Forms\Components\TextInput::make('fazr_time')
                    ->required(),
                Forms\Components\TextInput::make('ishraq_time')
                    ->required(),
                Forms\Components\TextInput::make('johr_time')
                    ->required(),
                Forms\Components\TextInput::make('asr_time')
                    ->required(),
                Forms\Components\TextInput::make('magrib_time')
                    ->required(),
                Forms\Components\TextInput::make('iftar_time')
                    ->required(),
                Forms\Components\TextInput::make('esha_time')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('mazhab.name'),
                Tables\Columns\TextColumn::make('sehri_time'),
                Tables\Columns\TextColumn::make('fazr_time'),
                Tables\Columns\TextColumn::make('ishraq_time'),
                Tables\Columns\TextColumn::make('johr_time'),
                Tables\Columns\TextColumn::make('asr_time'),
                Tables\Columns\TextColumn::make('magrib_time'),
                Tables\Columns\TextColumn::make('iftar_time'),
                Tables\Columns\TextColumn::make('esha_time'),
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
            'index' => Pages\ListMazhabWiseScheduleSettings::route('/'),
            'create' => Pages\CreateMazhabWiseScheduleSetting::route('/create'),
            'edit' => Pages\EditMazhabWiseScheduleSetting::route('/{record}/edit'),
        ];
    }
}