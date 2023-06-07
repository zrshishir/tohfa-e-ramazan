<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PermanentCalendarResource\Pages;
use App\Filament\Resources\PermanentCalendarResource\RelationManagers;
use App\Models\PermanentCalendar;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PermanentCalendarResource extends Resource
{
    protected static ?string $model = PermanentCalendar::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('month_id')
                    ->required(),
                Forms\Components\TextInput::make('day')
                    ->maxLength(255),
                Forms\Components\TextInput::make('sehri_time')
                    ->maxLength(255),
                Forms\Components\TextInput::make('fazr_time')
                    ->maxLength(255),
                Forms\Components\TextInput::make('sunrise_time')
                    ->maxLength(255),
                Forms\Components\TextInput::make('ishraq_time')
                    ->maxLength(255),
                Forms\Components\TextInput::make('johr_time')
                    ->maxLength(255),
                Forms\Components\TextInput::make('asr_time')
                    ->maxLength(255),
                Forms\Components\TextInput::make('magrib_and_iftar_time')
                    ->maxLength(255),
                Forms\Components\TextInput::make('esha_time')
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('month_id'),
                Tables\Columns\TextColumn::make('day'),
                Tables\Columns\TextColumn::make('sehri_time'),
                Tables\Columns\TextColumn::make('fazr_time'),
                Tables\Columns\TextColumn::make('sunrise_time'),
                // Tables\Columns\TextColumn::make('ishraq_time'),
                Tables\Columns\TextColumn::make('johr_time'),
                Tables\Columns\TextColumn::make('asr_time'),
                Tables\Columns\TextColumn::make('magrib_and_iftar_time'),
                Tables\Columns\TextColumn::make('esha_time'),
                // Tables\Columns\TextColumn::make('created_at')
                //     ->dateTime(),
                // Tables\Columns\TextColumn::make('updated_at')
                //     ->dateTime(),
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
            'index' => Pages\ListPermanentCalendars::route('/'),
            'create' => Pages\CreatePermanentCalendar::route('/create'),
            'view' => Pages\ViewPermanentCalendar::route('/{record}'),
            'edit' => Pages\EditPermanentCalendar::route('/{record}/edit'),
        ];
    }    
}
