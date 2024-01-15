<?php

namespace App\Filament\Resources\MazhabResource\Pages;

use App\Filament\Resources\MazhabResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMazhabs extends ListRecords
{
    protected static string $resource = MazhabResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
