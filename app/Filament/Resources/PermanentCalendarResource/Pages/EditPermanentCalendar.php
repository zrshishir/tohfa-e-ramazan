<?php

namespace App\Filament\Resources\PermanentCalendarResource\Pages;

use App\Filament\Resources\PermanentCalendarResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPermanentCalendar extends EditRecord
{
    protected static string $resource = PermanentCalendarResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
