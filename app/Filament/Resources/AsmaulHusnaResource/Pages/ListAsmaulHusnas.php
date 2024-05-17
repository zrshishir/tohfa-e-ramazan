<?php

namespace App\Filament\Resources\AsmaulHusnaResource\Pages;

use App\Filament\Resources\AsmaulHusnaResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAsmaulHusnas extends ListRecords
{
    protected static string $resource = AsmaulHusnaResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
