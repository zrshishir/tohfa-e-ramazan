<?php

namespace App\Filament\Resources\AyatResource\Pages;

use App\Filament\Resources\AyatResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAyat extends EditRecord
{
    protected static string $resource = AyatResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
