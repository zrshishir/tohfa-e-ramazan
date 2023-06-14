<?php

namespace App\Filament\Resources\DoaCategoryResource\Pages;

use App\Filament\Resources\DoaCategoryResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDoaCategory extends EditRecord
{
    protected static string $resource = DoaCategoryResource::class;

    protected function getActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
