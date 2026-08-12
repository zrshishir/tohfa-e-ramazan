<?php

namespace App\Filament\Resources\DoaResource\Pages;

use App\Filament\Resources\DoaResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewDoa extends ViewRecord
{
    protected static string $resource = DoaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
