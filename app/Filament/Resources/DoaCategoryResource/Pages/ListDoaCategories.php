<?php

namespace App\Filament\Resources\DoaCategoryResource\Pages;

use App\Filament\Resources\DoaCategoryResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDoaCategories extends ListRecords
{
    protected static string $resource = DoaCategoryResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
