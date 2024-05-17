<?php

namespace App\Filament\Resources\SuraResource\Pages;

use App\Filament\Resources\SuraResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSuras extends ListRecords
{
    protected static string $resource = SuraResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
