<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SchoolResource\Pages;
use App\Filament\Resources\SchoolResource\RelationManagers;
use App\Models\Barangay;
use App\Models\Municipal;
use App\Models\School;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SchoolResource extends Resource
{
    protected static ?string $model = School::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-library';

    protected static ?string $navigationGroup = 'Libraries';

    public static function form(Form $form): Form
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
                Forms\Components\Select::make('district')
                    ->options(fn() => School::select('district')->distinct()->get()->pluck('district', 'district'))
                    ->searchable(),
                Forms\Components\Select::make('municipality')
                    ->options(fn($get) => School::select('municipality')
                                                ->where('district', $get('district'))
                                                ->distinct()
                                                ->get()
                                                ->pluck('municipality', 'municipality')
                    )
                    ->searchable()
                    ->required(),
                Forms\Components\Select::make('barangay')
                    ->options(fn($get) => School::select('barangay')
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
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    // Tables\Actions\DeleteBulkAction::make(),
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
