<?php

namespace App\Filament\Resources\MasalaResource\Pages;

use App\Filament\Resources\MasalaResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMasalas extends ListRecords
{
    protected static string $resource = MasalaResource::class;

    protected function getActions(): array
    {
        return [Actions\CreateAction::make()];
    }

}
