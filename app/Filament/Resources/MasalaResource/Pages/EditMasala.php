<?php

namespace App\Filament\Resources\MasalaResource\Pages;

use App\Filament\Resources\MasalaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMasala extends EditRecord
{
    protected static string $resource = MasalaResource::class;


    protected function getHeaderActions(): array
    {
        return [Actions\DeleteAction::make()];
    }
}
