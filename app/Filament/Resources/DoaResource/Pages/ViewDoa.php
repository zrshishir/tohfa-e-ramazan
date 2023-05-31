<?php

namespace App\Filament\Resources\DoaResource\Pages;

use App\Filament\Resources\DoaResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewDoa extends ViewRecord
{
    protected static string $resource = DoaResource::class;

    protected function getActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
