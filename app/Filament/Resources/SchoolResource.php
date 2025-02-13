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

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('school')
                    ->required()
                    ->maxLength(255),
                Select::make('citymunCode')
                    ->label('Municipal')
                    ->getSearchResultsUsing(fn($search) => Municipal::where('citymunDesc', 'like', "%$search%")->pluck('citymunDesc', 'citymunCode'))
                    ->getOptionLabelUsing(fn(Get $get, $value) => Municipal::findOrFail($value)->citymunDesc)
                    // ->afterStateHydrated(fn($state) => dd($state))
                    // ->getOptionLabelUsing(fn($value) => ('test'))
                    ->searchable()
                    ->required(),
                Select::make('brgyCode')
                    ->label('Barangay')
                    ->options(fn(Get $get) => Barangay::where('citymunCode', $get('citymunCode'))->pluck('brgyDesc', 'brgyCode'))
                    ->getOptionLabelUsing(fn($value) => Barangay::findOrFail($value)->brgyDesc)
                    // ->afterStateHydrated(fn($record, Set $set) => $set('citymunCode', $record->load('barangay')->citymunCode))
                    // ->getSearchResultsUsing(fn($search, Get $get) => Barangay::where('citymunCode', $get('citymunCode'))->pluck('brgyDesc', 'brgyCode'))
                    ->searchable()
                    ->required()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('school')
                    ->searchable(),
                Tables\Columns\TextColumn::make('brgyCode')
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
            'index' => Pages\ListSchools::route('/'),
            'create' => Pages\CreateSchool::route('/create'),
            'edit' => Pages\EditSchool::route('/{record}/edit'),
        ];
    }
}
