<?php

namespace App\Filament\Resources\PermanentCalendarResource\Pages;

use App\Filament\Resources\PermanentCalendarResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewPermanentCalendar extends ViewRecord
{
    protected static string $resource = PermanentCalendarResource::class;

    protected function getActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
