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
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required(),
                Forms\Components\Repeater::make('tasbih')
                    ->label('Dhikr list')
                    ->schema([
                        Forms\Components\TextInput::make('text_en')
                            ->label('English')
                            ->required(),
                        Forms\Components\TextInput::make('text_bn')->label('Bangla'),
                        Forms\Components\TextInput::make('text_ar')->label('Arabic'),
                        Forms\Components\TextInput::make('reset_on')
                            ->label('Resets at')
                            ->numeric()
                            ->minValue(0)
                            ->helperText('0 means the counter never resets.'),
                        Forms\Components\TextInput::make('count')->numeric()->minValue(0),
                        Forms\Components\TextInput::make('today_count')->numeric()->minValue(0),
                        Forms\Components\TextInput::make('monthly_count')->numeric()->minValue(0),
                        Forms\Components\TextInput::make('yearly_count')->numeric()->minValue(0),
                        Forms\Components\TextInput::make('total_count')->numeric()->minValue(0),
                    ])
                    ->columns(3)
                    ->collapsible(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name'),
                Tables\Columns\TextColumn::make('tasbih')
                    ->label('Dhikrs')
                    ->getStateUsing(fn (Tasbih $record) => count($record->tasbih ?? [])),
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
