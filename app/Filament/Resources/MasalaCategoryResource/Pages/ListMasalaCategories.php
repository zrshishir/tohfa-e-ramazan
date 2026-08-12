<?php

namespace App\Filament\Resources\MasalaCategoryResource\Pages;

use App\Filament\Resources\MasalaCategoryResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMasalaCategories extends ListRecords
{
    protected static string $resource = MasalaCategoryResource::class;

    protected function getActions(): array
    {
        return [Actions\CreateAction::make()];
    }
}
