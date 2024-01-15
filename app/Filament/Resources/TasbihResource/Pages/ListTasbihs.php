<?php

namespace App\Filament\Resources\TasbihResource\Pages;

use App\Filament\Resources\TasbihResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTasbihs extends ListRecords
{
    protected static string $resource = TasbihResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
