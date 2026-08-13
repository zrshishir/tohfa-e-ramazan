<?php

namespace App\Filament\Resources\DoaCategoryResource\Pages;

use App\Filament\Resources\DoaCategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewDoaCategory extends ViewRecord
{
    protected static string $resource = DoaCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
