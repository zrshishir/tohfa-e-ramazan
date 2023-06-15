<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DistrictWiseScheduleSettingResource\Pages;
use App\Filament\Resources\DistrictWiseScheduleSettingResource\RelationManagers;
use App\Models\DistrictWiseScheduleSetting;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DistrictWiseScheduleSettingResource extends Resource
{
    protected static ?string $model = DistrictWiseScheduleSetting::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';
    protected static ?string $navigationGroup = 'Settings';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('district_id')
                    ->required(),
                Forms\Components\TextInput::make('time_addition_subtraction')
                    ->required(),
                Forms\Components\TextInput::make('am_pm')
                    ->maxLength(255),
                Forms\Components\Toggle::make('is_active')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('district_id'),
                Tables\Columns\TextColumn::make('time_addition_subtraction'),
                Tables\Columns\TextColumn::make('am_pm'),
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean(),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->dateTime(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime(),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
                Tables\Actions\ForceDeleteBulkAction::make(),
                Tables\Actions\RestoreBulkAction::make(),
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
            'index' => Pages\ListDistrictWiseScheduleSettings::route('/'),
            'create' => Pages\CreateDistrictWiseScheduleSetting::route('/create'),
            'view' => Pages\ViewDistrictWiseScheduleSetting::route('/{record}'),
            'edit' => Pages\EditDistrictWiseScheduleSetting::route('/{record}/edit'),
        ];
    }    
    
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
