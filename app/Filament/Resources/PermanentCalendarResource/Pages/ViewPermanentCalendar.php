<?php

namespace App\Filament\Resources\PermanentCalendarResource\Pages;

use App\Filament\Resources\PermanentCalendarResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewPermanentCalendar extends ViewRecord
{
    protected static string $resource = PermanentCalendarResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
