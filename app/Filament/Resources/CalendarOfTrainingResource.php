<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CalendarOfTrainingResource\Pages;
use App\Filament\Resources\CalendarOfTrainingResource\RelationManagers;
use App\Filament\Resources\CalendarOfTrainingResource\RelationManagers\ParticipantsRelationManager;
use App\Models\CalendarOfTraining;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CalendarOfTrainingResource extends Resource
{
    protected static ?string $model = CalendarOfTraining::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static bool $shouldRegisterNavigation = false;

    public static function form(Form $form): Form
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
            ParticipantsRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCalendarOfTrainings::route('/'),
            'create' => Pages\CreateCalendarOfTraining::route('/create'),
            'edit' => Pages\EditCalendarOfTraining::route('/{record}/edit'),
            'school' => Pages\CertificateOfParticipation::route('/{record}/schools')
        ];
    }
}
