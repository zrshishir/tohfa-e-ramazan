<?php

namespace App\Filament\Resources\PermanentCalendarResource\Pages;

use App\Filament\Resources\PermanentCalendarResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPermanentCalendar extends EditRecord
{
    protected static string $resource = PermanentCalendarResource::class;

    protected function getActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
