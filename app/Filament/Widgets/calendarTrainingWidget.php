<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\CalendarOfTrainingResource;
use App\Filament\Resources\CalendarOfTrainingResource\Pages\Concerns\ManagesTrainingParticipants;
use App\Filament\Resources\EmployeeResource;
use App\Filament\Resources\TrainerResource;
use App\Filament\Resources\TrainingResource;
use App\Filament\Resources\VenueResource;
use App\Models\CalendarOfTraining;
use BezhanSalleh\FilamentShield\Traits\HasWidgetShield;
use Carbon\Carbon;
use Filament\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;
use Guava\Calendar\Contracts\ContextualInfo;
use Guava\Calendar\Enums\Context;
use Guava\Calendar\Filament\Actions\CreateAction;
use Guava\Calendar\Filament\CalendarWidget;
use Guava\Calendar\ValueObjects\DateClickInfo;
use Guava\Calendar\ValueObjects\DateSelectInfo;
use Guava\Calendar\ValueObjects\FetchInfo;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\HtmlString;

class calendarTrainingWidget extends CalendarWidget
{
    use HasWidgetShield;
    use ManagesTrainingParticipants;

    protected string $view = 'filament.widgets.calendar-training-widget';

    protected bool $eventClickEnabled = true;

    protected bool $dateClickEnabled = true;

    protected bool $dateSelectEnabled = true;

    protected bool $eventDragEnabled = true;

    protected ?string $locale = 'en';

    protected string|HtmlString|bool|null $heading = 'Calendar of trainings';

    protected int|string|array $columnSpan = 1;

    public function authorize($ability, $arguments = [])
    {
        return true;
    }

    public function getColumnSpan(): int|string|array
    {
        return [
            'lg' => 2,
            'xl' => 1,
        ];
    }

    protected function getEvents(FetchInfo $info): Collection|array|Builder
    {
        return collect()
            ->push(
                ...CalendarOfTraining::query()
                    ->dateBetween($info)
                    ->get()
            );

    }

    public function getEventContent(): null|string|array
    {
        return [
            CalendarOfTraining::class => view('filament.components.calendar.events.training'),
        ];
    }

    protected function getDateClickContextMenuActions(): array
    {
        return $this->getDateSelectContextMenuActions();
        // return [
        //     // CreateAction::make('foo')
        //     //     ->label('New Program without Accreditation')
        //     //     ->model(CalendarOfTraining::class)
        //     //     ->mountUsing(fn ($arguments, $form) => $form->fill([
        //     //         'start_date' => data_get($arguments, 'dateStr'),
        //     //         'end_date' => data_get($arguments, 'dateStr'),
        //     //     ])),

        //         CreateAction::make('without_accreditation')
        //         ->label('New Program without Accreditation')
        //         ->model(CalendarOfTraining::class)
        //         ->mountUsing(fn ($arguments, $form) => $form->fill([
        //             'start_date' => data_get($arguments, 'dateStr'),
        //             'end_date' =>  data_get($arguments, 'dateStr'),
        //         ]))
        //         ->mutateFormDataUsing(function(array $data, $operation) {
        //             $data['user_id'] = auth()->id();
        //             return $data;
        //         }),
        //     CreateAction::make('with_accreditation')
        //         ->label('New Program with Accreditation')
        //         ->model(CalendarOfTraining::class)
        //         ->mountUsing(fn ($arguments, $form) => $form->fill([
        //             'start_date' => data_get($arguments, 'dateStr'),
        //             'end_date' => data_get($arguments, 'dateStr'),
        //         ]))
        //         ->mutateFormDataUsing(function(array $data, $operation) {
        //             $data['user_id'] = auth()->id();
        //             return $data;
        //         }),
        // ];
    }

    public function participantAction()
    {
        return CalendarOfTrainingResource::addParticipantsAction()
            ->label('Participants')
            ->icon('heroicon-o-users')
            ->record(fn () => $this->getEventRecord());
        // ->modal()
        // ->slideOver()
        // ->modalWidth('lg')
        // ->modalHeading(function() {
        //     $record = $this->getEventRecord();

        //     return new HtmlString("
        //                 <div>{$record->training?->training_name}</div>
        //                 <div class='text-sm dark:text-gray-400 text-gray-700'>{$record->duration}</div>
        //             ");
        // })
        // ->form([
        //     Repeater::make('participants')
        //         ->relationship()
        //         ->simple(
        //             Select::make('employee_id')
        //                 ->relationship(
        //                     'employee',
        //                     'full_name',
        //                     function($query) {
        //                         $query->whereNotIn('employee_type', ['TWG', 'Division Employee']);
        //                     })
        //                 ->preload()
        //                 ->searchable()
        //                 ->createOptionForm(fn(Schema $form) => EmployeeResource::form($form)->extraAttributes(['class' => 'w-full']))
        //         ),
        // ])
        // ->fillForm(function($record, $data) {
        //     $data['participants'] = $record->load('participants');
        //     return $data;
        // })
    }

    protected function getEventClickContextMenuActions(): array
    {
        return [
            $this->editAction(),
            $this->participantAction(),
        ];
    }

    public function getResourceLabelContent(): null|string|array
    {
        return 'asd';
    }

    public function getContextMenuActionsUsing(Context $context, array $data = []): Collection
    {
        $this->setRawCalendarContextData($context, $data);

        $actions = match ($context) {
            Context::EventClick => $this->getCachedEventClickContextMenuActions(),
            Context::DateClick => $this->getCachedDateClickContextMenuActions(),
            Context::DateSelect => $this->getCachedDateSelectContextMenuActions(),
            Context::NoEventsClick => $this->getCachedNoEventsClickContextMenuActions(),
        };

        return collect($actions)
            ->filter(fn (Action $action): bool => $action->isVisible())
            ->map(
                fn (Action $action): string => $action($this->getRawCalendarContextData())
                    ->toHtml()
            );
    }

    protected function getDateSelectContextMenuActions(): array
    {
        // dd(data_get($arguments, 'dateStr'));
        // dd('test');
        return [
            CreateAction::make('without_accreditation')
                ->label('New Program without Accreditation')
                ->model(CalendarOfTraining::class)
                ->mountUsing(fn (Schema $form, CreateAction $action) => $form->fill(
                    $this->getCalendarDateDefaults($action->getArguments())
                ))
                ->mutateFormDataUsing(function (array $data) {
                    $data['user_id'] = auth()->id();

                    return $data;
                }),
            CreateAction::make('with_accreditation')
                ->label('New Program with Accreditation')
                ->model(CalendarOfTraining::class)
                ->mountUsing(fn (Schema $form, CreateAction $action) => $form->fill(
                    $this->getCalendarDateDefaults($action->getArguments())
                ))
                ->mutateFormDataUsing(function (array $data) {
                    $data['user_id'] = auth()->id();

                    return $data;
                }),
        ];
    }

    public function schema(Schema $schema): Schema
    {
        return $schema->schema([
            Grid::make(columns: 3)
                ->schema([
                    Select::make('training_id')
                        ->label('Training')
                        ->required()
                        ->live()
                        ->searchable()
                        ->relationship('training', 'training_name')
                        ->preload()
                        ->createOptionForm(function (Schema $form): array {
                            return TrainingResource::form($form)->getComponents();
                        }),
                    Select::make('venue_id')
                        ->label('Venue')
                        ->required()
                        ->searchable()
                        ->relationship('venue', 'venue')
                        ->preload()
                        ->createOptionForm(function (Schema $form): array {
                            return VenueResource::form($form)->getComponents();
                        }),
                ]),
            Fieldset::make('Schedule')
                ->schema([
                    DatePicker::make('start_date'),
                    DatePicker::make('end_date'),
                ]),
            Fieldset::make('Accreditation')
                ->schema([
                    TextInput::make('accreditation_number')
                        ->required(),
                    TextInput::make('approved_credit_units')
                        ->required(),
                ])
                ->visible(fn ($operation) => $operation === 'with_accreditation'),
            Select::make('trainers')
                ->multiple()
                ->relationship(titleAttribute: 'trainers_name')
                ->preload()
                ->createOptionForm(fn (Schema $form): array => TrainerResource::form($form)->getComponents()),
            TextInput::make('topic_discuss')
                ->required(),
            Select::make('twg')
                ->label('Technical Working Groups')
                ->multiple()
                ->relationship(titleAttribute: 'full_name')
                ->preload()
                ->createOptionForm(fn (Schema $form): array => EmployeeResource::form($form)->getComponents()),
            // Repeater::make('twgTrainings')
            //     ->relationship()
            //     // ->relationship(modifyQueryUsing: fn($query) => $query->dd())
            //     ->simple(
            //         Select::make('employee_id')
            //             ->relationship('employee', 'full_name')
            //             ->searchable(['first_name', 'last_name'])
            //             ->preload()
            //             ->createOptionForm(fn($form) => EmployeeResource::form($form))
            //     )
            //     ->grid(2)
            //     ->addActionLabel('Add Technical Working Group')

        ]);

        // If you have multiple model types on your calendar, you can return different schemas based on the $model property
        // return match($model) {
        //     CalendarOfTraining::class => [
        //         TextInput::make('name'),
        //     ],
        // };
    }

    /**
     * @return array{start_date: string, end_date: string}
     */
    private function getCalendarDateDefaults(ContextualInfo|array|null $calendarInfo): array
    {
        if ($calendarInfo instanceof DateSelectInfo) {
            return [
                'start_date' => $calendarInfo->start->toDateString(),
                'end_date' => $calendarInfo->end->subDay()->toDateString(),
            ];
        }

        if ($calendarInfo instanceof DateClickInfo) {
            return [
                'start_date' => $calendarInfo->date->toDateString(),
                'end_date' => $calendarInfo->date->toDateString(),
            ];
        }

        if (is_array($calendarInfo)) {
            $selectedStartDate = data_get($calendarInfo, 'data.startStr');
            $selectedEndDate = data_get($calendarInfo, 'data.endStr');
            $clickedDate = data_get($calendarInfo, 'data.dateStr');
            $startDate = $selectedStartDate ?? $clickedDate;

            if (filled($startDate)) {
                return [
                    'start_date' => Carbon::parse($startDate)->toDateString(),
                    'end_date' => filled($selectedEndDate)
                        ? Carbon::parse($selectedEndDate)->subDay()->toDateString()
                        : Carbon::parse($startDate)->toDateString(),
                ];
            }
        }

        return [
            'start_date' => today()->toDateString(),
            'end_date' => today()->toDateString(),
        ];
    }
}
