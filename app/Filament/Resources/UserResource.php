<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\Employee;
use App\Models\School;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\Grid;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use STS\FilamentImpersonate\Tables\Actions\Impersonate;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationGroup = 'Administration';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make([
                    'xl' => 2,
                    'default' => 1
                ])
                ->schema([
                    Forms\Components\TextInput::make('name')
                        ->datalist(fn() => Employee::select('full_name')->distinct('fullname')->get()->pluck('full_name'))
                        ->autocomplete(false)
                        ->required()
                        ->maxLength(255),
                    Forms\Components\Select::make('school_id')
                        ->label('school')
                        ->getSearchResultsUsing(fn($search) => School::where('school', 'like', "%$search%")->get()->pluck('school', 'id'))
                        ->searchable()
                ]),
                Grid::make([
                    'xl' => 2,
                    'default' => 1
                ])
                ->schema([
                    Forms\Components\TextInput::make('username')
                        ->required()
                        ->maxLength(50),
                    Forms\Components\TextInput::make('password')
                        ->dehydrated(fn($operation, $state) => $operation === 'create' || ($state !== null && strtolower($operation) === 'edit'))
                        ->required(fn($operation) => strtolower($operation) === 'create')
                        ->password()
                        ->maxLength(255),
                ]),
                Forms\Components\CheckboxList::make('roles')
                    ->relationship('roles', 'name')
                    ->searchable(),
                
            ])
            ->columns(1)
            ->extraAttributes(['class' => 'w-1/2']);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('username')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('roles.name')
                    ->badge()
                    ->listWithLineBreaks()
                    ->searchable(),
                Tables\Columns\TextColumn::make('email_verified_at')
                    ->dateTime()
                    ->sortable(),
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
                Impersonate::make()
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
