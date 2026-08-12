<?php

namespace App\Filament\Resources\DoaCategoryResource\Pages;

use App\Filament\Resources\DoaCategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDoaCategory extends EditRecord
{
    protected static string $resource = DoaCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
