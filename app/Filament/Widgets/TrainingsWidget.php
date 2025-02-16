<?php

namespace App\Filament\Widgets;

use App\Models\CalendarOfTraining;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
use Filament\Support\Enums\ActionSize;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Support\HtmlString;
use Torgodly\Html2Media\Tables\Actions\Html2MediaAction;

class TrainingsWidget extends BaseWidget
{
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
                    }),
                TextColumn::make('status')
                ->badge()
            ])
//            view('filament::components.icon', ['icon' => 'heroicon-o-arrow-down'])->render()
            ->actions([
                Tables\Actions\Action::make('update_status')
                    ->label('Update Status')
                    ->icon('heroicon-s-hand-thumb-up')
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
                    ->button()
                    ->size(ActionSize::ExtraSmall)
                    ->outlined(),
                Html2MediaAction::make('print')
                    ->label('Certificates')
                    ->icon('heroicon-o-printer')
                    ->outlined()
                    ->button()
                    ->size(ActionSize::ExtraSmall)
                    ->color('success')
                    ->orientation('portrait')
                    ->format('a4', 'mm')
                    ->content(function() {
                        return view('filament.reports.certificate');
                    }),
            ])
//            ->poll(3)
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
