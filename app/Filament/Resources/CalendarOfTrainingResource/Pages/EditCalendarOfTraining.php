<?php

namespace App\Filament\Resources\CalendarOfTrainingResource\Pages;

use App\Filament\Resources\CalendarOfTrainingResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCalendarOfTraining extends EditRecord
{
    protected static string $resource = CalendarOfTrainingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\DeleteAction::make(),
        ];
    }

    protected function getFormActions(): array
    {
        return [];
    }
}
