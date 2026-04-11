<?php

namespace App\Filament\Widgets;

use App\Models\CalendarOfTraining;
use BezhanSalleh\FilamentShield\Traits\HasWidgetShield;
use Filament\Actions\Action as TablesActionsAction;
use Filament\Actions\ActionGroup;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Support\HtmlString;

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
                            'state' => $state,  'record' => $record,
                        ]));
                    })
                    ->wrap(),
                TextColumn::make('status')
                    ->badge(),
            ])
//            view('filament::components.icon', ['icon' => 'heroicon-o-arrow-down'])->render()
            ->actions([

                ActionGroup::make([
                    TablesActionsAction::make('update_status')
                        ->label(fn ($record) => $record->participants->count() == 0 ? 'Unable to update status' : 'Update Status')
                        ->icon(fn ($record) => $record->participants->count() == 0 ? 'heroicon-s-x-circle' : 'heroicon-s-hand-thumb-up')
                        ->requiresConfirmation()
                        ->form([
                            Select::make('status')
                                ->label('Status')
                                ->options([
                                    'Approved' => 'Approved',
                                    'Disapproved' => 'Disapproved',
                                    'Pending' => 'Pending',
                                ]),
                        ])
                        ->action(function ($data, $record) {
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
                        ->disabled(fn ($record) => $record->participants->count() == 0)
                        ->color(fn ($record) => $record->participants->count() == 0 ? 'danger' : 'primary')
                        ->outlined(),
                    TablesActionsAction::make('print_participation')
                        ->label('Download Certificate of Participation')
                        ->tooltip('Certificate of pariticipation')
                        ->color('success')
                        ->icon('heroicon-o-arrow-down-on-square-stack')
                        ->url(fn ($record) => route('report', [$record->id]), shouldOpenInNewTab: true),
                    TablesActionsAction::make('print_recognition_speaker')
                        ->label('Download Certificate of Recognition')
                        ->tooltip('Certificate of Recognition')
                        ->color('success')
                        ->icon('heroicon-o-arrow-down-on-square-stack')
                        ->url(fn ($record) => route('reportRecognition', [$record->id]), shouldOpenInNewTab: true),
                    TablesActionsAction::make('export report')
                        ->icon('heroicon-o-arrow-down-on-square-stack')
                        ->url(fn ($record) => route('filament.admin.resources.calendar-of-trainings.school', [$record])),

                ]),
            ])
            ->poll(3)
            ->paginated()
            ->paginationPageOptions([3]);
    }

    public function getColumnSpan(): int|string|array
    {
        return [
            'lg' => 2,
            'xl' => 1,
        ];
    }
}
