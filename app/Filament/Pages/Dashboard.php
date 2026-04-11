<?php

namespace App\Filament\Pages;

use App\Filament\Resources\CalendarOfTrainingResource;
use App\Filament\Resources\EmployeeResource;
use App\Filament\Resources\ParticipantResource;
use App\Filament\Resources\SchoolResource;
use App\Filament\Resources\TrainingResource;
use App\Filament\Widgets\calendarTrainingWidget;
use App\Filament\Widgets\TrainingsWidget;
use App\Models\CalendarOfTraining;
use App\Models\Employee;
use App\Models\Participant;
use App\Models\Training as TrainingModel;
use Carbon\CarbonInterface;
use Illuminate\Contracts\Support\Htmlable;

class Dashboard extends \Filament\Pages\Dashboard
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-calendar-date-range';

    protected static ?string $navigationLabel = 'Trainings';

    protected string $view = 'filament.pages.dashboard';

    public function getTitle(): string|Htmlable
    {
        return 'Learning and Development';
    }

    public function getExtraBodyAttributes(): array
    {
        return ['id' => 'dashboard'];
    }

    public function getWidgets(): array
    {
        return [
            calendarTrainingWidget::make(),
            TrainingsWidget::make(),
        ];
    }

    /**
     * @return array<int, array{label: string, value: string, tone: string, icon: string, url: string}>
     */
    public function getDashboardStats(): array
    {
        $statusCounts = CalendarOfTraining::query()
            ->selectRaw('status, count(*) as aggregate')
            ->groupBy('status')
            ->pluck('aggregate', 'status');

        return [
            [
                'label' => 'Programs',
                'value' => number_format(CalendarOfTraining::query()->count()),
                'tone' => 'blue',
                'icon' => 'heroicon-o-academic-cap',
                'url' => CalendarOfTrainingResource::getUrl(),
            ],
            [
                'label' => 'Approved',
                'value' => number_format((int) $statusCounts->get('Approved', 0)),
                'tone' => 'green',
                'icon' => 'heroicon-o-check-badge',
                'url' => CalendarOfTrainingResource::getUrl(),
            ],
            [
                'label' => 'Pending',
                'value' => number_format((int) $statusCounts->get('Pending', 0)),
                'tone' => 'yellow',
                'icon' => 'heroicon-o-clock',
                'url' => CalendarOfTrainingResource::getUrl(),
            ],
            [
                'label' => 'Participants',
                'value' => number_format(Participant::query()->count()),
                'tone' => 'red',
                'icon' => 'heroicon-o-users',
                'url' => ParticipantResource::getUrl(),
            ],
        ];
    }

    /**
     * @return array<int, array{label: string, value: string, url: string}>
     */
    public function getDashboardHighlights(): array
    {
        return [
            [
                'label' => 'Training master list',
                'value' => number_format(TrainingModel::query()->count()),
                'url' => TrainingResource::getUrl(),
            ],
            [
                'label' => 'Personnel records',
                'value' => number_format(Employee::query()->count()),
                'url' => EmployeeResource::getUrl(),
            ],
            [
                'label' => 'This month',
                'value' => number_format(CalendarOfTraining::query()
                    ->whereBetween('start_date', [now()->startOfMonth(), now()->endOfMonth()])
                    ->count()),
                'url' => CalendarOfTrainingResource::getUrl(),
            ],
        ];
    }

    /**
     * @return array<int, array{label: string, description: string, icon: string, url: string, tone: string}>
     */
    public function getQuickLinks(): array
    {
        return [
            [
                'label' => 'New program',
                'description' => 'Schedule a learning and development activity',
                'icon' => 'heroicon-o-calendar-days',
                'url' => CalendarOfTrainingResource::getUrl('create'),
                'tone' => 'blue',
            ],
            [
                'label' => 'Training library',
                'description' => 'Manage the master list of programs',
                'icon' => 'heroicon-o-book-open',
                'url' => TrainingResource::getUrl(),
                'tone' => 'yellow',
            ],
            [
                'label' => 'Personnel',
                'description' => 'Open employee and participant records',
                'icon' => 'heroicon-o-user-group',
                'url' => EmployeeResource::getUrl(),
                'tone' => 'red',
            ],
            [
                'label' => 'Schools',
                'description' => 'Review school and agency entries',
                'icon' => 'heroicon-o-building-library',
                'url' => SchoolResource::getUrl(),
                'tone' => 'blue',
            ],
        ];
    }

    /**
     * @return array<int, array{title: string, date: string, venue: string, status: string, url: string}>
     */
    public function getUpcomingPrograms(): array
    {
        return CalendarOfTraining::query()
            ->with(['training', 'venue'])
            ->whereDate('start_date', '>=', today())
            ->orderBy('start_date')
            ->limit(5)
            ->get()
            ->map(fn (CalendarOfTraining $program): array => [
                'title' => $program->training?->training_name ?? 'Untitled program',
                'date' => $this->formatProgramDate($program->start_date),
                'venue' => $program->venue?->venue ?? 'Venue not set',
                'status' => (string) $program->status?->value,
                'url' => CalendarOfTrainingResource::getUrl('edit', ['record' => $program]),
            ])
            ->all();
    }

    protected function formatProgramDate(mixed $date): string
    {
        if ($date instanceof CarbonInterface) {
            return $date->format('M d, Y');
        }

        return filled($date) ? (string) $date : 'Date not set';
    }
}
