<?php

namespace App\Filament\Resources\CalendarOfTrainingResource\Pages;

use App\Filament\Resources\CalendarOfTrainingResource;
use App\Filament\Resources\CalendarOfTrainingResource\Pages\Concerns\ManagesTrainingParticipants;
use Filament\Resources\Pages\EditRecord;

class EditCalendarOfTraining extends EditRecord
{
    use ManagesTrainingParticipants;

    protected static string $resource = CalendarOfTrainingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CalendarOfTrainingResource::addParticipantsAction()
                ->record($this->record),
            // Actions\DeleteAction::make(),
        ];
    }

    protected function getFormActions(): array
    {
        return [];
    }
}
