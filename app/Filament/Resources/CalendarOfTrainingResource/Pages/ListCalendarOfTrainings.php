<?php

namespace App\Filament\Resources\CalendarOfTrainingResource\Pages;

use App\Filament\Resources\CalendarOfTrainingResource;
use App\Filament\Resources\CalendarOfTrainingResource\Pages\Concerns\ManagesTrainingParticipants;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCalendarOfTrainings extends ListRecords
{
    use ManagesTrainingParticipants;

    protected static string $resource = CalendarOfTrainingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
