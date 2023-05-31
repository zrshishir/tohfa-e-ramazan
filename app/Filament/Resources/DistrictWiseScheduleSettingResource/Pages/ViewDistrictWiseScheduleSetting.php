<?php

namespace App\Filament\Resources\DistrictWiseScheduleSettingResource\Pages;

use App\Filament\Resources\DistrictWiseScheduleSettingResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewDistrictWiseScheduleSetting extends ViewRecord
{
    protected static string $resource = DistrictWiseScheduleSettingResource::class;

    protected function getActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
