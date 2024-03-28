<?php

namespace App\Filament\Resources\AyatResource\Pages;

use App\Filament\Resources\AyatResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAyat extends EditRecord
{
    protected static string $resource = AyatResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
