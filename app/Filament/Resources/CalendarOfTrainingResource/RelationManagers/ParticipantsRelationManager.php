<?php

namespace App\Filament\Resources\CalendarOfTrainingResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Forms\Components\Select;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\EmployeeResource;
use App\Filament\Resources\ParticipantResource;
use Filament\Actions\Action;
use Filament\Actions\StaticAction;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Actions\Action as ActionsAction;

class ParticipantsRelationManager extends RelationManager
{
    protected static string $relationship = 'participants';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('employee_id')
                        ->relationship(
                            'employee', 
                            'full_name',
                            function($query) {
                                $query->whereNotIn('employee_type', ['TWG', 'Division Employee'])
                                    ->where;
                            })
                        ->preload()
                        ->searchable()
                        ->createOptionForm(fn(Form $form) => EmployeeResource::form($form)->extraAttributes(['class' => 'w-full']))
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
                Tables\Actions\CreateAction::make()
                    ->modalWidth('sm')
                    ->modalSubmitAction(fn(StaticAction $action) => $action->hidden())
                    ->modalFooterActions(function(ActionsAction $action) {
                        return [
                            $action->makeModalSubmitAction('createAnother', ['another' => true]),
                            $action->modalCancelAction()->label('Cancel')->color('gray')
                        ];
                    }),
            ])
            ->actions([
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
