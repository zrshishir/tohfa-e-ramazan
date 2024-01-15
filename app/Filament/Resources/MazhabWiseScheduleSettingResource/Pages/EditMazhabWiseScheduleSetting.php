<?php

namespace App\Filament\Resources\MazhabWiseScheduleSettingResource\Pages;

use App\Filament\Resources\MazhabWiseScheduleSettingResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMazhabWiseScheduleSetting extends EditRecord
{
    protected static string $resource = MazhabWiseScheduleSettingResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
