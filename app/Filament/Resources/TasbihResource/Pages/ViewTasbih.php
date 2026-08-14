<?php

namespace App\Filament\Resources\TasbihResource\Pages;

use App\Filament\Resources\TasbihResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewTasbih extends ViewRecord
{
    protected static string $resource = TasbihResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
