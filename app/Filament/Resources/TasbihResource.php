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
                Forms\Components\TextInput::make('user_id')
                    ->required(),
                Forms\Components\TextInput::make('subhanallah'),
                Forms\Components\TextInput::make('alhamdulillah'),
                Forms\Components\TextInput::make('allahuakbar'),
                Forms\Components\TextInput::make('astagfirullah'),
                Forms\Components\TextInput::make('la_ilaha_illallah'),
                Forms\Components\TextInput::make('subhanallahi_wabi_hamdihi_wa_subhanallahil_azeem'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user_id'),
                Tables\Columns\TextColumn::make('subhanallah'),
                Tables\Columns\TextColumn::make('alhamdulillah'),
                Tables\Columns\TextColumn::make('allahuakbar'),
                Tables\Columns\TextColumn::make('astagfirullah'),
                Tables\Columns\TextColumn::make('la_ilaha_illallah'),
                Tables\Columns\TextColumn::make('subhanallahi_wabi_hamdihi_wa_subhanallahil_azeem'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime(),
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
            'index' => Pages\ListTasbihs::route('/'),
            'create' => Pages\CreateTasbih::route('/create'),
            'view' => Pages\ViewTasbih::route('/{record}'),
            'edit' => Pages\EditTasbih::route('/{record}/edit'),
        ];
    }
}
