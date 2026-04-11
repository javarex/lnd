<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SchoolResource\Pages;
use App\Models\School;
use Filament\Actions;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;

class SchoolResource extends Resource
{
    protected static ?string $model = School::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-building-library';

    protected static string|\UnitEnum|null $navigationGroup = 'Libraries';

    public static function form(Schema $form): Schema
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('school_code')
                    ->label('School ID')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('school')
                    ->required()
                    ->maxLength(255),
                Select::make('district')
                    ->options(fn () => School::select('district')->distinct()->get()->pluck('district', 'district'))
                    ->searchable(),
                Select::make('municipality')
                    ->options(fn ($get) => School::select('municipality')
                        ->where('district', $get('district'))
                        ->distinct()
                        ->get()
                        ->pluck('municipality', 'municipality')
                    )
                    ->searchable()
                    ->required(),
                Select::make('barangay')
                    ->options(fn ($get) => School::select('barangay')
                        ->where('municipality', $get('municipality'))
                        ->distinct()
                        ->get()
                        ->pluck('barangay', 'barangay')
                    )
                    ->searchable()
                    ->required(),
            ])
            ->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('school_code')
                    ->searchable()
                    ->label('School ID'),
                Tables\Columns\TextColumn::make('school')
                    ->searchable(),
                Tables\Columns\TextColumn::make('district')
                    ->searchable(),
                Tables\Columns\TextColumn::make('purok')
                    ->searchable(),
                Tables\Columns\TextColumn::make('barangay')
                    ->searchable(),
                Tables\Columns\TextColumn::make('municipality')
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
                Actions\EditAction::make(),
            ])
            ->bulkActions([
                Actions\BulkActionGroup::make([
                    // Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListSchools::route('/'),
            // 'create' => Pages\CreateSchool::route('/create'),
            // 'edit' => Pages\EditSchool::route('/{record}/edit'),
        ];
    }
}
