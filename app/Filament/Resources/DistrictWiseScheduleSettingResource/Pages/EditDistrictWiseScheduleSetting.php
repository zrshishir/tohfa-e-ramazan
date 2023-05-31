<?php

namespace App\Filament\Resources\DistrictWiseScheduleSettingResource\Pages;

use App\Filament\Resources\DistrictWiseScheduleSettingResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDistrictWiseScheduleSetting extends EditRecord
{
    protected static string $resource = DistrictWiseScheduleSettingResource::class;

    protected function getActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }
}
