<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MasalaResource\Pages;
use App\Models\Masala;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;

/**
 * Masa-el had no admin resource at all, so content could only be added by editing a
 * seeder. This is the intended way to enter them.
 */
class MasalaResource extends Resource
{
    protected static ?string $model = Masala::class;

    protected static ?string $navigationIcon = 'heroicon-o-question-mark-circle';

    protected static ?string $navigationGroup = 'Content';

    protected static ?string $modelLabel = 'Masala';

    protected static ?string $pluralModelLabel = 'Masa-el';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('masala_category_id')
                    ->label('Category')
                    ->relationship('category', 'name_en')
                    ->searchable()
                    ->required(),
                Forms\Components\Textarea::make('question')
                    ->required()
                    ->rows(2)
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('answer')
                    ->required()
                    ->rows(8)
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('reference')
                    ->helperText('The source this ruling is taken from, e.g. Fatawa Alamgiri.'),
                Forms\Components\TextInput::make('sort_order')
                    ->numeric()
                    ->default(0)
                    ->helperText('Lower numbers appear first within the category.'),
                Forms\Components\Toggle::make('status')
                    ->label('Published')
                    ->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('category.name_en')->label('Category')->sortable(),
                Tables\Columns\TextColumn::make('question')->limit(60)->searchable(),
                Tables\Columns\TextColumn::make('reference')->limit(25),
                Tables\Columns\TextColumn::make('sort_order')->label('Order')->sortable(),
                Tables\Columns\IconColumn::make('status')->label('Published')->boolean(),
            ])
            ->defaultSort('sort_order')
            ->filters([
                Tables\Filters\SelectFilter::make('masala_category_id')
                    ->label('Category')
                    ->relationship('category', 'name_en'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListMasalas::route('/'),
            'create' => Pages\CreateMasala::route('/create'),
            'edit'   => Pages\EditMasala::route('/{record}/edit'),
        ];
    }
}
