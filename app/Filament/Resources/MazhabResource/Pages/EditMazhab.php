<?php

namespace App\Filament\Resources\MazhabResource\Pages;

use App\Filament\Resources\MazhabResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMazhab extends EditRecord
{
    protected static string $resource = MazhabResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
