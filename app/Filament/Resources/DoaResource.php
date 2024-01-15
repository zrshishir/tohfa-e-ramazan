<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DoaResource\Pages;
use App\Filament\Resources\DoaResource\RelationManagers;
use App\Models\Doa;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DoaResource extends Resource
{
    protected static ?string $model = Doa::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    protected static ?string $navigationGroup = 'Doa';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('user_id'),
                Forms\Components\TextInput::make('doa_category_id'),
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('doa_for')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('description')
                    ->maxLength(65535),
                Forms\Components\Textarea::make('arabic_text')
                    ->maxLength(65535),
                Forms\Components\Textarea::make('bangla_text')
                    ->maxLength(65535),
                Forms\Components\Textarea::make('english_text')
                    ->maxLength(65535),
                Forms\Components\TextInput::make('reference')
                    ->maxLength(255),
                Forms\Components\Textarea::make('when_to_use')
                    ->maxLength(65535),
                Forms\Components\Textarea::make('notes')
                    ->maxLength(65535),
                Forms\Components\Textarea::make('when_not_to_use')
                    ->maxLength(65535),
                Forms\Components\Toggle::make('status')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user_id'),
                Tables\Columns\TextColumn::make('doa_category_id'),
                Tables\Columns\TextColumn::make('title'),
                Tables\Columns\TextColumn::make('doa_for'),
                Tables\Columns\TextColumn::make('description'),
                Tables\Columns\TextColumn::make('arabic_text'),
                Tables\Columns\TextColumn::make('bangla_text'),
                Tables\Columns\TextColumn::make('english_text'),
                Tables\Columns\TextColumn::make('reference'),
                Tables\Columns\TextColumn::make('when_to_use'),
                Tables\Columns\TextColumn::make('notes'),
                Tables\Columns\TextColumn::make('when_not_to_use'),
                Tables\Columns\IconColumn::make('status')
                    ->boolean(),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->dateTime(),
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
            'index' => Pages\ListDoas::route('/'),
            'create' => Pages\CreateDoa::route('/create'),
            'view' => Pages\ViewDoa::route('/{record}'),
            'edit' => Pages\EditDoa::route('/{record}/edit'),
        ];
    }
}