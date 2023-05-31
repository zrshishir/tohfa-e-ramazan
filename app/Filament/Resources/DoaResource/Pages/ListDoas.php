<?php

namespace App\Filament\Resources\DoaResource\Pages;

use App\Filament\Resources\DoaResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDoas extends ListRecords
{
    protected static string $resource = DoaResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
