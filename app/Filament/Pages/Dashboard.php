<?php

namespace App\Filament\Pages;

use App\Filament\Resources\CalendarOfTrainingResource;
use App\Filament\Resources\EmployeeResource;
use App\Filament\Resources\ParticipantResource;
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
     * @return array<int, array{key: string, label: string, value: string, tone: string, icon: string}>
     */
    public function getDashboardStats(): array
    {
        $statusCounts = CalendarOfTraining::query()
            ->selectRaw('status, count(*) as aggregate')
            ->groupBy('status')
            ->pluck('aggregate', 'status');

        return [
            [
                'key' => 'programs',
                'label' => 'Programs',
                'value' => number_format(CalendarOfTraining::query()->count()),
                'tone' => 'blue',
                'icon' => 'heroicon-o-academic-cap',
            ],
            [
                'key' => 'approved',
                'label' => 'Approved',
                'value' => number_format((int) $statusCounts->get('Approved', 0)),
                'tone' => 'green',
                'icon' => 'heroicon-o-check-badge',
            ],
            [
                'key' => 'pending',
                'label' => 'Pending',
                'value' => number_format((int) $statusCounts->get('Pending', 0)),
                'tone' => 'yellow',
                'icon' => 'heroicon-o-clock',
            ],
            [
                'key' => 'participants',
                'label' => 'Participants',
                'value' => number_format(Participant::query()->count()),
                'tone' => 'red',
                'icon' => 'heroicon-o-users',
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
                'url' => CalendarOfTrainingResource::getUrl(parameters: [
                    'filters' => [
                        'this_month' => [
                            'isActive' => true,
                        ],
                    ],
                ]),
            ],
        ];
    }

    protected function formatProgramDate(mixed $date): string
    {
        if ($date instanceof CarbonInterface) {
            return $date->format('M d, Y');
        }

        return filled($date) ? (string) $date : 'Date not set';
    }

    /**
     * @return array<string, array{title: string, description: string, empty: string, rows: array<int, array{title: string, meta: string, badge: string, url: string|null}>}>
     */
    public function getDashboardModalPanels(): array
    {
        return [
            'programs' => [
                'title' => 'Programs',
                'description' => 'Latest learning and development schedules.',
                'empty' => 'No programs found.',
                'rows' => $this->getProgramModalRows(),
            ],
            'approved' => [
                'title' => 'Approved Programs',
                'description' => 'Only approved training schedules are listed here.',
                'empty' => 'No approved programs found.',
                'rows' => $this->getProgramModalRows('Approved'),
            ],
            'pending' => [
                'title' => 'Pending Programs',
                'description' => 'Only pending training schedules are listed here.',
                'empty' => 'No pending programs found.',
                'rows' => $this->getProgramModalRows('Pending'),
            ],
            'participants' => [
                'title' => 'Participants',
                'description' => 'Recently added participant records.',
                'empty' => 'No participants found.',
                'rows' => $this->getParticipantModalRows(),
            ],
        ];
    }

    /**
     * @return array<int, array{title: string, meta: string, badge: string, url: string|null}>
     */
    private function getProgramModalRows(?string $status = null): array
    {
        return CalendarOfTraining::query()
            ->with(['training', 'venue'])
            ->when($status, fn ($query) => $query->where('status', $status))
            ->latest('start_date')
            ->limit(10)
            ->get()
            ->map(fn (CalendarOfTraining $program): array => [
                'title' => $program->training?->training_name ?? 'Untitled program',
                'meta' => $this->formatProgramDate($program->start_date)
                    .' - '.($program->venue?->venue ?? 'Venue not set'),
                'badge' => (string) ($program->status?->value ?? $program->status ?? 'Pending'),
                'url' => CalendarOfTrainingResource::getUrl('edit', ['record' => $program]),
            ])
            ->all();
    }

    /**
     * @return array<int, array{title: string, meta: string, badge: string, url: string|null}>
     */
    private function getParticipantModalRows(): array
    {
        return Participant::query()
            ->with('employee')
            ->latest()
            ->limit(10)
            ->get()
            ->map(fn (Participant $participant): array => [
                'title' => $participant->employee?->full_name ?? 'Unnamed participant',
                'meta' => 'Participant record #'.$participant->getKey(),
                'badge' => 'Participant',
                'url' => ParticipantResource::getUrl('edit', ['record' => $participant]),
            ])
            ->all();
    }
}
