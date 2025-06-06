<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EmployeeResource\Pages;
use App\Filament\Resources\EmployeeResource\RelationManagers;
use App\Models\Employee;
use App\Models\School;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;
use Filament\Actions\StaticAction;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class EmployeeResource extends Resource implements HasShieldPermissions
{
    protected static ?string $model = Employee::class;

    protected static ?string $navigationGroup = 'Libraries';

    protected static ?string $navigationIcon = 'heroicon-o-users';

    public static function getPermissionPrefixes(): array
    {
        $permissionPrefixes = [
            'add_twg'
        ];
        return array_merge(config('filament-shield.permission_prefixes.resource'), $permissionPrefixes);
    }
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()->schema([

                    Forms\Components\TextInput::make('first_name')
                        ->required()
                        ->maxLength(255),
                    Forms\Components\TextInput::make('middle_name')
                        ->maxLength(255),
                    Forms\Components\TextInput::make('last_name')
                        ->required()
                        ->maxLength(255),
                    Select::make('school_id')
                        ->label('School or Agency')
                        ->relationship('school', 'school')
                        ->getOptionLabelsUsing(fn ($value) => School::find($value)->school)
                        ->preload()
                        ->searchable()
                        ->createOptionForm(fn(Form $form) => SchoolResource::form($form))
                        ->createOptionAction(function(StaticAction $action) {
                            return $action->modalHeading('Create new school');
                        }),
                    // Forms\Components\TextInput::make('school')
                    //     ->required()
                    //     ->maxLength(255),
                    Forms\Components\CheckboxList::make('employee_type')
                        ->label('Employee Type')
                        ->options(function () {
                            $types = [
                                'Teaching' => 'Teaching',
                                'Non-teaching' => 'Non-teaching',
                                'Teaching Related / School Head' => 'Teaching Related / School Head'
                            ];
                            if(auth()->user()->can('addTwg', Employee::class)) {
                               $types = array_merge($types, ['TWG' => 'Technical Working Group', 'Division Employee' => 'Division Employee']);
                            }
                            return $types;
                        })
                        ->required(),
                ])
            ])
            ->columns(1)
            ->extraAttributes(['class' => 'w-1/2']);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('full_name')
                    ->label('Name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('school.school')
                    ->placeholder('Not Applicable')
                    ->searchable(),
                Tables\Columns\TextColumn::make('employee_type')
                    ->searchable(),
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
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEmployees::route('/'),
            'create' => Pages\CreateEmployee::route('/create'),
            'edit' => Pages\EditEmployee::route('/{record}/edit'),
        ];
    }
}
