<?php

namespace App\Filament\Resources\DistrictWiseScheduleSettingResource\Pages;

use App\Filament\Resources\DistrictWiseScheduleSettingResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDistrictWiseScheduleSettings extends ListRecords
{
    protected static string $resource = DistrictWiseScheduleSettingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
