<?php

namespace App\Filament\Resources\TasbihResource\Pages;

use App\Filament\Resources\TasbihResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTasbih extends EditRecord
{
    protected static string $resource = TasbihResource::class;

    protected function getActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
