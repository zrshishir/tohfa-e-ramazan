<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Hash;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-user';
    protected static ?string $navigationGroup = 'User Management';

    public static function form( Form $form ): Form
    {
        return $form
            ->schema( [
                Forms\Components\TextInput::make( 'country_id' ),
                Forms\Components\TextInput::make( 'name' )
                    ->required()
                    ->maxLength( 255 ),
                Forms\Components\TextInput::make( 'email' )
                    ->email()
                    ->required()
                    ->maxLength( 255 ),
                // Not required: the API registration flow does not collect a phone
                // number, so demanding one here made every API-registered user
                // impossible to save from the admin.
                Forms\Components\TextInput::make( 'phone' )
                    ->tel()
                    ->maxLength( 255 ),
                Forms\Components\DateTimePicker::make( 'email_verified_at' ),
                // Passwords are hashed on the way in and never on the way out.
                //
                // Previously this field was ->required() with no dehydration, which broke
                // in both directions: the hash is $hidden on the model so the field always
                // loaded empty, making every edit fail validation; and anything typed here
                // was written to the column verbatim, storing a plaintext password that
                // Hash::check() could never match on login.
                Forms\Components\TextInput::make( 'password' )
                    ->password()
                    ->revealable()
                    ->required( fn ( string $operation ): bool => $operation === 'create' )
                    ->dehydrated( fn ( ?string $state ): bool => filled( $state ) )
                    ->dehydrateStateUsing( fn ( string $state ): string => Hash::make( $state ) )
                    ->helperText( 'Leave blank to keep the current password.' )
                    ->maxLength( 255 ),
                Forms\Components\TextInput::make( 'role' )
                    ->maxLength( 255 ),
            ] );
    }

    public static function table( Table $table ): Table
    {
        return $table
            ->columns( [
                Tables\Columns\TextColumn::make( 'country.nice_name' ),
                Tables\Columns\TextColumn::make( 'name' ),
                Tables\Columns\TextColumn::make( 'email' ),
                Tables\Columns\TextColumn::make( 'phone' ),
                Tables\Columns\TextColumn::make( 'email_verified_at' )
                    ->dateTime(),
                Tables\Columns\TextColumn::make( 'role' ),
                Tables\Columns\TextColumn::make( 'created_at' )
                    ->dateTime(),
                Tables\Columns\TextColumn::make( 'updated_at' )
                    ->dateTime(),
            ] )
            ->filters( [
                Tables\Filters\TrashedFilter::make(),
            ] )
            ->actions( [
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ] )
            ->bulkActions( [
                Tables\Actions\DeleteBulkAction::make(),
                Tables\Actions\ForceDeleteBulkAction::make(),
                Tables\Actions\RestoreBulkAction::make(),
            ] );
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
            'index' => Pages\ListUsers::route( '/' ),
            'create' => Pages\CreateUser::route( '/create' ),
            'view' => Pages\ViewUser::route( '/{record}' ),
            'edit' => Pages\EditUser::route( '/{record}/edit' ),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes( [
                SoftDeletingScope::class,
            ] );
    }
}
