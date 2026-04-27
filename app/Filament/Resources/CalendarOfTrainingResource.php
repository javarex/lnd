<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CalendarOfTrainingResource\Pages;
use App\Filament\Resources\CalendarOfTrainingResource\RelationManagers\ParticipantsRelationManager;
use App\Models\CalendarOfTraining;
use App\Models\Employee;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\HtmlString;
use Livewire\Component;

class CalendarOfTrainingResource extends Resource
{
    protected static ?string $model = CalendarOfTraining::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static bool $shouldRegisterNavigation = false;

    public static function form(Schema $form): Schema
    {
        return $form
            ->schema([]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('training.id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('venue.id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('start_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('end_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('accreditation_number')
                    ->searchable(),
                Tables\Columns\TextColumn::make('approved_credit_units')
                    ->searchable(),
                Tables\Columns\TextColumn::make('user_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Filter::make('this_month')
                    ->label('This month')
                    ->query(fn (Builder $query): Builder => $query->whereBetween('start_date', [
                        now()->startOfMonth()->toDateString(),
                        now()->endOfMonth()->toDateString(),
                    ])),
            ])
            ->actions([
                static::addParticipantsAction(),
                Actions\EditAction::make(),
            ])
            ->bulkActions([
                Actions\BulkActionGroup::make([
                    Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            ParticipantsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCalendarOfTrainings::route('/'),
            'create' => Pages\CreateCalendarOfTraining::route('/create'),
            'edit' => Pages\EditCalendarOfTraining::route('/{record}/edit'),
            'school' => Pages\CertificateOfParticipation::route('/{record}/schools'),
        ];
    }

    public static function addParticipantsAction(): Action
    {
        return Action::make('addParticipants')
            ->label('Add participants')
            ->icon('heroicon-o-user-plus')
            ->modalWidth('7xl')
            ->modalSubmitAction(false)
            ->modalHeading(fn (CalendarOfTraining $record): HtmlString => new HtmlString(
                '<div>Add participants</div><div class="text-sm font-normal text-gray-500 dark:text-gray-400">'
                .e($record->training?->training_name ?? 'Training')
                .'</div>'
            ))
            ->mountUsing(function (Component $livewire): void {
                $livewire->resetAddParticipantsState();
            })
            ->modalContent(fn (CalendarOfTraining $record, Component $livewire) => view('filament.resources.calendar-of-training-resource.actions.add-participants', [
                'record' => $record,
                'addedParticipants' => $livewire->getAddedParticipantsForAddParticipants($record),
                'createParticipantAction' => $livewire->getMountedAction()?->getModalAction('createEmployeeParticipant'),
                'participants' => $livewire->getAvailableParticipantsForAddParticipants($record),
            ]))
            ->registerModalActions([
                Action::make('createEmployeeParticipant')
                    ->label('New employee participant')
                    ->icon('heroicon-o-user-plus')
                    ->modalHeading('Add new participant')
                    ->modalWidth('lg')
                    ->model(Employee::class)
                    ->record(fn () => null)
                    ->schema(fn (Schema $schema): array => EmployeeResource::form($schema)
                        ->columns(1)
                        ->extraAttributes(['class' => 'w-full max-w-none'])
                        ->getComponents())
                    ->action(function (Action $action, array $data, Component $livewire): void {
                        $record = $action->getParentAction()?->getRecord();

                        if (! $record instanceof CalendarOfTraining) {
                            return;
                        }

                        $employee = $livewire->createEmployeeParticipantForTraining($record, $data);

                        Notification::make()
                            ->success()
                            ->title('Participant created')
                            ->body($employee->full_name.' was added to this training.')
                            ->send();
                    }),
            ])
            ->action(function (Action $action, CalendarOfTraining $record, Component $livewire): void {
                $livewire->addSelectedParticipantsToTraining($record);
                $action->halt();
            });
    }
}
