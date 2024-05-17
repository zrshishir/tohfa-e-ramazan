<?php

namespace App\Filament\Resources\AyatResource\Pages;

use App\Filament\Resources\AyatResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListAyats extends ListRecords
{
    protected static string $resource = AyatResource::class;

    public function getTabs(): array
    {
        return [
            'all' => Tab::make(),
            'active' => Tab::make()
                        ->modifyQueryUsing(fn (Builder $query) => $query->where('status', '=', 'active')),
            'inactive' => Tab::make()
                        ->modifyQueryUsing(fn (Builder $query) => $query->where('status', '!=', 'active')),
        ];
    }

    public function getDefaultActiveTab(): string | int | null
    {
        return 'active';
    }

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}