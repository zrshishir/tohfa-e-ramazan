<?php

namespace App\Filament\Resources\AyatResource\Pages;

use App\Filament\Resources\AyatResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAyats extends ListRecords
{
    protected static string $resource = AyatResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
