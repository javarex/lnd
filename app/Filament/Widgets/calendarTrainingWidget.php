<?php

namespace App\Filament\Widgets;

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
use Guava\Calendar\Filament\Actions\CreateAction;
use Guava\Calendar\Filament\CalendarWidget;
use Guava\Calendar\ValueObjects\FetchInfo;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\HtmlString;

class calendarTrainingWidget extends CalendarWidget
{
    use HasWidgetShield;

    // protected static string $view = 'filament.widgets.calendar-training-widget';
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
                    ->each(function ($item) {
                        $item->start_date = Carbon::parse($item->start_date)->endOfDay();
                        $item->end_date = Carbon::parse($item->end_date)->endOfDay();
                    })
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
        return Action::make('participants')
            ->label('Participants')
            ->icon('heroicon-o-users')
            ->record(fn () => $this->getEventRecord())
            ->action(fn ($record) => redirect(route('filament.admin.resources.calendar-of-trainings.edit', [$this->getEventRecord()])));
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
        // ->record(fn() => $this->getEventRecord())
        // ->url(fn($record) => route('filament.admin.resources.calendar-of-trainings.edit', ['record' => $record?->getKey()]))
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

    // public function onEventClick(array $info = [], ?string $action = null): void
    // {
    //     if ($action == 'participants') {
    //         redirect(route('filament.admin.resources.calendar-of-trainings.edit', [$info['event']['extendedProps']['key']]));
    //     }
    // }

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

    protected function getDateSelectContextMenuActions(): array
    {
        // dd(data_get($arguments, 'dateStr'));
        // dd('test');
        return [
            CreateAction::make('without_accreditation')
                ->label('New Program without Accreditation')
                ->model(CalendarOfTraining::class)
                ->mountUsing(fn ($arguments, $form) => $form->fill([
                    // dd(data_get($arguments, 'startStr') ?? data_get($arguments, 'dateStr')),
                    'start_date' => data_get($arguments, 'startStr') ?? data_get($arguments, 'dateStr'),
                    // 'end_date' => data_get($arguments, 'endStr') ?? data_get($arguments, 'dateStr'),
                    'end_date' => data_get($arguments, 'endStr') ? Carbon::parse(data_get($arguments, 'endStr'))->subDay() : data_get($arguments, 'dateStr'),
                ]))
                ->mutateFormDataUsing(function (array $data) {
                    $data['user_id'] = auth()->id();

                    return $data;
                }),
            CreateAction::make('with_accreditation')
                ->label('New Program with Accreditation')
                ->model(CalendarOfTraining::class)
                ->mountUsing(fn ($arguments, $form) => $form->fill([
                    'start_date' => data_get($arguments, 'startStr') ?? data_get($arguments, 'dateStr'),
                    'end_date' => data_get($arguments, 'endStr') ? Carbon::parse(data_get($arguments, 'endStr'))->subDay() : data_get($arguments, 'dateStr'),
                ]))
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
}
