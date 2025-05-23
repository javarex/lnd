<?php

namespace App\Filament\Widgets;

use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Actions\Action;
use App\Models\CalendarOfTraining;
use Filament\Actions\StaticAction;
use Illuminate\Support\HtmlString;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Support\Enums\ActionSize;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Repeater;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\ActionGroup;
use App\Filament\Resources\VenueResource;
use Filament\Forms\Components\DatePicker;
use App\Filament\Resources\EmployeeResource;
use App\Filament\Resources\TrainingResource;
use Filament\Widgets\TableWidget as BaseWidget;
use App\Filament\Widgets\calendarTrainingWidget;
use BezhanSalleh\FilamentShield\Traits\HasWidgetShield;
use Torgodly\Html2Media\Tables\Actions\Html2MediaAction;
use Filament\Tables\Actions\Action as TablesActionsAction;
use Filament\Forms\Components\Actions\Action as ActionsAction;

class TrainingsWidget extends BaseWidget
{
    use HasWidgetShield;
    public function table(Table $table): Table
    {
        return $table
            ->heading('Table view of trainings')
            ->query(
                CalendarOfTraining::query()->with('training')
            )
            ->columns([
                TextColumn::make('training.training_name')
                    ->searchable()
                    ->label('Program Details')
                    ->extraAttributes(['class' => 'uppercase'])
                    ->formatStateUsing(function ($state, $record) {
                        return new HtmlString(view('filament.resources.calendar-of-training.tables.title-state', [
                            'state' => $state,  'record' => $record
                        ]));
                    })
                    ->wrap(),
                TextColumn::make('status')
                ->badge()
            ])
//            view('filament::components.icon', ['icon' => 'heroicon-o-arrow-down'])->render()
            ->actions([

                ActionGroup::make([
                    Tables\Actions\Action::make('update_status')
                        ->label(fn($record) => $record->participants->count() == 0 ? 'Unable to update status' : 'Update Status')
                        ->icon(fn($record) => $record->participants->count() == 0 ? 'heroicon-s-x-circle' : 'heroicon-s-hand-thumb-up')
                        ->requiresConfirmation()
                        ->form([
                            Select::make('status')
                                    ->label('Status')
                                    ->options([
                                        'Approved' => 'Approved',
                                        'Disapproved' => 'Disapproved',
                                        'Pending' => 'Pending',
                                        ])
                        ])
                        ->action(function($data, $record) {
                            try {

                                $record->status = $data['status'];
                                $record->save();
                                Notification::make()
                                    ->success()
                                    ->title('Status updated')
                                    ->send();
                            } catch (\Throwable $e) {
                                Notification::make()
                                    ->danger()
                                    ->title('Error! Please contact the administrator')
                                    ->body($e->getMessage())
                                    ->send();
                            }
                        })
                        ->disabled(fn($record) => $record->participants->count() == 0)
                        ->color(fn($record) => $record->participants->count() == 0 ?  'danger' : 'primary')
                        ->outlined(),
                    Tables\Actions\Action::make('print_participation')
                        ->label('Download CP')
                        ->tooltip('Certificate of pariticipation')
                        ->color('success')
                        ->icon('heroicon-o-arrow-down-on-square-stack')
                        ->url(fn($record) => route('report', [$record->id]),shouldOpenInNewTab: true),
                    Tables\Actions\Action::make('export report')
                        ->icon('heroicon-o-arrow-down-on-square-stack')
                        ->url(fn($record) => route('filament.admin.resources.calendar-of-trainings.school', [$record]))
                    
                ]),
            ])
           ->poll(3)
           ->paginated()
           ->paginationPageOptions([3])
            ;
    }

    public function getColumnSpan(): int|string|array
    {
        return [
            'lg' => 2,
            'xl' => 1,
        ];
    }
}
