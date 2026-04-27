<?php

namespace App\Filament\Resources\CalendarOfTrainingResource\RelationManagers;

use App\Filament\Resources\EmployeeResource;
use App\Filament\Resources\ParticipantResource;
use Filament\Actions;
use Filament\Actions\Action as ActionsAction;
use Filament\Actions\StaticAction;
use Filament\Forms\Components\Select;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class ParticipantsRelationManager extends RelationManager
{
    protected static string $relationship = 'participants';

    public function form(Schema $form): Schema
    {
        return $form
            ->schema([
                Select::make('employee_id')
                    ->relationship(
                        'employee',
                        'full_name',
                        function ($query) {
                            $query->whereNotIn('employee_type', ['TWG', 'Division Employee']);
                        })
                    ->preload()
                    ->searchable()
                    ->createOptionForm(fn (Schema $form): array => EmployeeResource::form($form)->extraAttributes(['class' => 'w-full'])->getComponents()),
            ])
            ->columns(1);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns(ParticipantResource::table($table)->getColumns())
            ->filters([
                //
            ])
            ->headerActions([
                Actions\CreateAction::make()
                    ->modalWidth('sm')
                    ->modalSubmitAction(fn (StaticAction $action) => $action->hidden())
                    ->modalFooterActions(function (ActionsAction $action) {
                        return [
                            $action->makeModalSubmitAction('createAnother', ['another' => true]),
                            $action->modalCancelAction()->label('Cancel')->color('gray'),
                        ];
                    }),
            ])
            ->actions([
                Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Actions\BulkActionGroup::make([
                    Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
