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

    protected static ?string $navigationIcon = 'heroicon-o-calendar';

    protected static ?string $navigationGroup = 'Salat Calendar';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('month_id')
                    ->relationship('month', 'name')
                    ->required(),
                Forms\Components\TextInput::make('day')
                    ->required()
                    ->maxLength(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('month.name')->label('Month')->sortable(),
                Tables\Columns\TextColumn::make('day')->label('Day')->sortable(),
                Tables\Columns\TextColumn::make('sehri_start')->label('Sehri Start')
                    ->getStateUsing(fn ($record) => data_get($record->sehri, 'start_time')),
                Tables\Columns\TextColumn::make('sehri_end')->label('Sehri End')
                    ->getStateUsing(fn ($record) => data_get($record->sehri, 'end_time')),
                Tables\Columns\TextColumn::make('fazr_start')->label('Fazr')
                    ->getStateUsing(fn ($record) => data_get($record->fazr, 'start_time')),
                Tables\Columns\TextColumn::make('sunrise_start')->label('Sunrise')
                    ->getStateUsing(fn ($record) => data_get($record->sunrise, 'start_time')),
                Tables\Columns\TextColumn::make('johr_start')->label('Johr')
                    ->getStateUsing(fn ($record) => data_get($record->johr, 'start_time')),
                Tables\Columns\TextColumn::make('asr_start')->label('Asr')
                    ->getStateUsing(fn ($record) => data_get($record->asr, 'start_time')),
                Tables\Columns\TextColumn::make('magrib_start')->label('Magrib / Iftar')
                    ->getStateUsing(fn ($record) => data_get($record->magrib, 'start_time')),
                Tables\Columns\TextColumn::make('esha_start')->label('Esha')
                    ->getStateUsing(fn ($record) => data_get($record->esha, 'start_time')),
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
