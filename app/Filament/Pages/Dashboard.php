<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\calendarTrainingWidget;
use App\Filament\Widgets\TrainingsWidget;
use Filament\Pages\Page;
use Guava\Calendar\Widgets\CalendarWidget;
use Illuminate\Contracts\Support\Htmlable;

class Dashboard extends \Filament\Pages\Dashboard
{
    protected static ?string $navigationIcon = 'heroicon-o-home';

    protected static ?string $navigationLabel = 'Home';

    public function getTitle(): string|Htmlable
    {
        return 'Home';
    }

    /**
     * @return array
     */
    public function getExtraBodyAttributes(): array
    {
        return ['id' => 'dashboard'];
    }

    public function getWidgets(): array
    {
        return [
            calendarTrainingWidget::make(),
            TrainingsWidget::make()
        ];
    }

}
