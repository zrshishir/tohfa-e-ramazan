<?php

namespace App\Filament\Resources\TasbihResource\Pages;

use App\Filament\Resources\TasbihResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewTasbih extends ViewRecord
{
    protected static string $resource = TasbihResource::class;

    protected function getActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
