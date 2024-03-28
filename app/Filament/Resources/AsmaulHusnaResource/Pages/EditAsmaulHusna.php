<?php

namespace App\Filament\Resources\AsmaulHusnaResource\Pages;

use App\Filament\Resources\AsmaulHusnaResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAsmaulHusna extends EditRecord
{
    protected static string $resource = AsmaulHusnaResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
