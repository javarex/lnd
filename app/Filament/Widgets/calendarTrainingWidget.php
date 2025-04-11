<?php

namespace App\Filament\Widgets;

use Closure;
use Carbon\Carbon;
use App\Models\User;
use Filament\Forms\Form;
use Filament\Actions\Action;
use Filament\Widgets\Widget;
use App\Models\CalendarOfTraining;
use Illuminate\Support\Collection;
use Illuminate\Support\HtmlString;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Guava\Calendar\Actions\EditAction;
use Guava\Calendar\ValueObjects\Event;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Guava\Calendar\Actions\CreateAction;
use App\Filament\Resources\VenueResource;
use Filament\Forms\Components\DatePicker;
use Guava\Calendar\ValueObjects\Resource;
use Guava\Calendar\Widgets\CalendarWidget;
use App\Filament\Resources\EmployeeResource;
use App\Filament\Resources\TrainerResource;
use App\Filament\Resources\TrainingResource;
use Filament\Forms\Components\TagsInput;

class calendarTrainingWidget extends CalendarWidget
{
    // protected static string $view = 'filament.widgets.calendar-training-widget';
    protected bool $eventClickEnabled = true;
    protected bool $dateClickEnabled = true;
    protected bool $dateSelectEnabled = true;
    protected bool $eventDragEnabled = true;
    protected ?string $locale = 'en';
    protected string | Closure | HtmlString | null $heading = 'Calendar of trainings';
    protected int | string | array $columnSpan = 1;

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

    public function getEvents(array $fetchInfo = []): Collection | array
    {

        return collect()
                ->push(
                    ...CalendarOfTraining::query()
                            ->dateBetween($fetchInfo)
                            ->get()
                            ->each(function($item) {
                                $item->start_date = Carbon::parse($item->start_date)->endOfDay();
                                $item->end_date = Carbon::parse($item->end_date)->endOfDay();
                            })
                );

    }

    public function getEventContent(): null | string | array
    {
        return [
            CalendarOfTraining::class => view('filament.components.calendar.events.training'),
        ];
    }

    public function getDateClickContextMenuActions(): array
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


    public function getEventClickContextMenuActions(): array
    {
        return [
            $this->editAction(),
            Action::make('participants')
                ->label('Participants')
                ->icon('heroicon-o-users')
                ->modal()
                ->slideOver()
                ->modalWidth('lg')
                ->modalHeading(function() {
                    $record = $this->getEventRecord();

                    return new HtmlString("
                                <div>{$record->training?->training_name}</div>
                                <div class='text-sm dark:text-gray-400 text-gray-700'>{$record->duration}</div>
                            ");
                })
                ->record(fn() => $this->getEventRecord())
                ->form([
                    Repeater::make('participants')
                        ->relationship()
                        ->simple(
                            Select::make('employee_id')
                                ->relationship('employee', 'full_name')
                                ->preload()
                                ->searchable()
                                ->createOptionForm(fn(Form $form) => EmployeeResource::form($form)->extraAttributes(['class' => 'w-full']))
                        ),
                ])
                ->fillForm(function($record, $data) {
                    $data['participants'] = $record->load('participants');
                    return $data;
                })

        ];
    }

    public function getResourceLabelContent(): null|string|array
    {
        return "asd";
    }


    public function getDateSelectContextMenuActions(): array
    {
        // dd(data_get($arguments, 'dateStr'));
        return [
            CreateAction::make('without_accreditation')
                ->label('New Program without Accreditation')
                ->model(CalendarOfTraining::class)
                ->mountUsing(fn ($arguments, $form) => $form->fill([
                    'start_date' => data_get($arguments, 'startStr') ?? data_get($arguments, 'dateStr'),
                    // 'end_date' => data_get($arguments, 'endStr') ?? data_get($arguments, 'dateStr'),
                    'end_date' => Carbon::parse(data_get($arguments, 'endStr') ?? data_get($arguments, 'dateStr'))->subDay(),
                ]))
                ->mutateFormDataUsing(function(array $data) {
                    $data['user_id'] = auth()->id();
                    return $data;
                }),
            CreateAction::make('with_accreditation')
                ->label('New Program with Accreditation')
                ->model(CalendarOfTraining::class)
                ->mountUsing(fn ($arguments, $form) => $form->fill([
                    'start_date' => data_get($arguments, 'startStr') ?? data_get($arguments, 'dateStr'),
                    'end_date' => data_get($arguments, 'endStr') ?? data_get($arguments, 'dateStr'),
                ]))
                ->mutateFormDataUsing(function(array $data) {
                    $data['user_id'] = auth()->id();
                    return $data;
                }),
        ];
    }

    public function getSchema(?string $model = null): ?array
    {
        // If you only work with one model type, you can ignore the $model parameter and simply return a schema
        return [
            Grid::make(columns: 3)
                ->schema([
                    Select::make('training_id')
                        ->label('Training')
                        ->required()
                        ->live()
                        ->searchable()
                        ->relationship('training', 'training_name')
                        ->preload()
                        ->createOptionForm(function(Form $form) {
                            return TrainingResource::form($form);
                        }),
                    Select::make('venue_id')
                        ->label('Venue')
                        ->required()
                        ->searchable()
                        ->relationship('venue', 'venue')
                        ->preload()
                        ->createOptionForm(function(Form $form) {
                            return VenueResource::form($form);
                        }),
                    ]),
            Fieldset::make('Schedule')
                ->schema([
                    DatePicker::make('start_date'),
                    DatePicker::make('end_date')
                ]),
            Fieldset::make('Accreditation')
                ->schema([
                    TextInput::make('accreditation_number')
                        ->required(),
                    TextInput::make('approved_credit_units')
                        ->required(),
                ])
                ->visible(fn($operation) => $operation === 'with_accreditation'),
            Select::make('trainers')
                ->multiple()
                ->relationship(titleAttribute:'trainers_name')
                ->preload()
                ->createOptionForm(fn($form) => TrainerResource::form($form)),
            Select::make('twg')
                ->label('Technical Working Groups')
                ->multiple()
                ->relationship(titleAttribute:'full_name')
                ->preload()
                ->createOptionForm(fn($form) => EmployeeResource::form($form)),
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
            
        ];

        // If you have multiple model types on your calendar, you can return different schemas based on the $model property
        // return match($model) {
        //     CalendarOfTraining::class => [
        //         TextInput::make('name'),
        //     ],
        // };
    }

}
