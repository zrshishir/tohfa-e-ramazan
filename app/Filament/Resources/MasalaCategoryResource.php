<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MasalaCategoryResource\Pages;
use App\Models\MasalaCategory;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Support\Str;

class MasalaCategoryResource extends Resource
{
    protected static ?string $model = MasalaCategory::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    protected static ?string $navigationGroup = 'Content';

    protected static ?string $pluralModelLabel = 'Masala categories';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name_en')
                    ->label('English')
                    ->required()
                    // Keeps the slug in step without making the user think about it.
                    ->afterStateUpdated(fn ($state, callable $set) => $set('slug', Str::slug($state ?? '')))
                    ->reactive(),
                Forms\Components\TextInput::make('name_bn')->label('Bangla'),
                Forms\Components\TextInput::make('name_ar')->label('Arabic'),
                Forms\Components\TextInput::make('slug')->required()->unique(ignoreRecord: true),
                Forms\Components\TextInput::make('sort_order')->numeric()->default(0),
                Forms\Components\Toggle::make('status')->label('Published')->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('sort_order')->label('Order')->sortable(),
                Tables\Columns\TextColumn::make('name_en')->label('English')->searchable(),
                Tables\Columns\TextColumn::make('name_bn')->label('Bangla'),
                Tables\Columns\TextColumn::make('masalas_count')->counts('masalas')->label('Masa-el'),
                Tables\Columns\IconColumn::make('status')->label('Published')->boolean(),
            ])
            ->defaultSort('sort_order')
            ->actions([Tables\Actions\EditAction::make()])
            ->bulkActions([Tables\Actions\DeleteBulkAction::make()]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListMasalaCategories::route('/'),
            'create' => Pages\CreateMasalaCategory::route('/create'),
            'edit'   => Pages\EditMasalaCategory::route('/{record}/edit'),
        ];
    }
}
