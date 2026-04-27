<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SchoolResource\Pages;
use App\Models\Barangay;
use App\Models\Municipal;
use App\Models\School;
use Filament\Actions;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Schema as SchemaFacade;
use Throwable;

class SchoolResource extends Resource
{
    protected static ?string $model = School::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-building-library';

    protected static string|\UnitEnum|null $navigationGroup = 'Libraries';

    public static function form(Schema $form): Schema
    {
        $schema = [
            Forms\Components\TextInput::make('no')
                ->label('No.')
                ->required(static::isRequiredSchoolColumn('no'))
                ->maxLength(255)
                ->visible(static::schoolColumnExists('no')),
            Forms\Components\TextInput::make('school')
                ->required(static::isRequiredSchoolColumn('school'))
                ->maxLength(255),
        ];

        if (SchemaFacade::hasColumn('schools', 'school_code')) {
            array_unshift(
                $schema,
                Forms\Components\TextInput::make('school_code')
                    ->label('School ID')
                    ->required(static::isRequiredSchoolColumn('school_code'))
                    ->maxLength(255),
            );
        }

        if (SchemaFacade::hasColumn('schools', 'brgyCode')) {
            $municipalityField = SchemaFacade::hasColumn('schools', 'citymunCode')
                ? 'citymunCode'
                : 'selected_citymunCode';

            $schema[] = Select::make($municipalityField)
                ->label('Municipality')
                ->placeholder('Search municipality or province')
                ->dehydrated($municipalityField === 'citymunCode')
                ->live()
                ->afterStateUpdated(fn ($set): mixed => $set('brgyCode', null))
                ->getSearchResultsUsing(fn (?string $search): array => static::getMunicipalitySearchResults($search))
                ->getOptionLabelUsing(fn (?string $value): ?string => static::getMunicipalityOptionLabel($value))
                ->searchable()
                ->required(static::isRequiredSchoolColumn('citymunCode'));

            $schema[] = Select::make('brgyCode')
                ->label('Barangay')
                ->placeholder('Search barangay')
                ->getSearchResultsUsing(fn (?string $search, $get): array => static::getBarangaySearchResults($search, $get($municipalityField)))
                ->getOptionLabelUsing(fn (?string $value): ?string => static::getBarangayOptionLabel($value))
                ->searchable()
                ->required(static::isRequiredSchoolColumn('brgyCode'));
        }

        if (SchemaFacade::hasColumn('schools', 'district')) {
            $schema[] = Select::make('district')
                ->options(fn () => School::query()
                    ->select('district')
                    ->whereNotNull('district')
                    ->distinct()
                    ->pluck('district', 'district'))
                ->searchable();
        }

        if (SchemaFacade::hasColumn('schools', 'municipality')) {
            $schema[] = Select::make('municipality')
                ->options(fn ($get) => School::query()
                    ->select('municipality')
                    ->when(
                        SchemaFacade::hasColumn('schools', 'district') && filled($get('district')),
                        fn ($query) => $query->where('district', $get('district')),
                    )
                    ->whereNotNull('municipality')
                    ->distinct()
                    ->pluck('municipality', 'municipality'))
                ->searchable()
                ->required(static::isRequiredSchoolColumn('municipality'));
        }

        if (SchemaFacade::hasColumn('schools', 'barangay')) {
            $schema[] = Select::make('barangay')
                ->options(fn ($get) => School::query()
                    ->select('barangay')
                    ->when(
                        SchemaFacade::hasColumn('schools', 'municipality') && filled($get('municipality')),
                        fn ($query) => $query->where('municipality', $get('municipality')),
                    )
                    ->whereNotNull('barangay')
                    ->distinct()
                    ->pluck('barangay', 'barangay'))
                ->searchable()
                ->required(static::isRequiredSchoolColumn('barangay'));
        }

        return $form
            ->schema($schema)
            ->columns(1);
    }

    public static function table(Table $table): Table
    {
        $columns = [
            Tables\Columns\TextColumn::make('school')
                ->searchable(),
        ];

        if (SchemaFacade::hasColumn('schools', 'school_code')) {
            array_unshift(
                $columns,
                Tables\Columns\TextColumn::make('school_code')
                    ->searchable()
                    ->label('School ID'),
            );
        }

        foreach (['district', 'purok', 'barangay', 'municipality'] as $column) {
            if (! SchemaFacade::hasColumn('schools', $column)) {
                continue;
            }

            $columns[] = Tables\Columns\TextColumn::make($column)
                ->searchable();
        }

        $columns = [
            ...$columns,
            Tables\Columns\TextColumn::make('created_at')
                ->dateTime()
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
            Tables\Columns\TextColumn::make('updated_at')
                ->dateTime()
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
        ];

        return $table
            ->columns($columns)
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

    /**
     * @return array<string, string>
     */
    private static function getMunicipalitySearchResults(?string $search): array
    {
        if (! SchemaFacade::hasTable('municipals')) {
            return [];
        }

        $search = trim((string) $search);

        return Municipal::query()
            ->select(['citymunCode', 'citymunDesc', 'provCode'])
            ->when($search !== '', function ($query) use ($search): void {
                $provinceCodes = collect(static::getProvinceLabels())
                    ->filter(fn (string $province): bool => str($province)->contains($search, ignoreCase: true))
                    ->keys()
                    ->all();

                $query->where(function ($query) use ($provinceCodes, $search): void {
                    $query
                        ->where('citymunDesc', 'like', "%{$search}%")
                        ->orWhere('provCode', 'like', "%{$search}%")
                        ->orWhereIn('provCode', $provinceCodes);
                });
            })
            ->orderBy('citymunDesc')
            ->limit(50)
            ->get()
            ->mapWithKeys(fn (Municipal $municipal): array => [
                $municipal->citymunCode => static::formatMunicipalityLabel($municipal),
            ])
            ->all();
    }

    private static function getMunicipalityOptionLabel(?string $value): ?string
    {
        if (blank($value) || ! SchemaFacade::hasTable('municipals')) {
            return null;
        }

        $municipal = Municipal::query()
            ->select(['citymunCode', 'citymunDesc', 'provCode'])
            ->where('citymunCode', $value)
            ->first();

        return $municipal ? static::formatMunicipalityLabel($municipal) : null;
    }

    private static function formatMunicipalityLabel(Municipal $municipal): string
    {
        return collect([
            str($municipal->citymunDesc)->upper()->toString(),
            str(static::getProvinceLabels()[$municipal->provCode] ?? $municipal->provCode)->upper()->toString(),
        ])
            ->filter()
            ->join(', ');
    }

    /**
     * @return array<string, string>
     */
    private static function getBarangaySearchResults(?string $search, ?string $citymunCode = null): array
    {
        if (! SchemaFacade::hasTable('barangays')) {
            return [];
        }

        $search = trim((string) $search);

        return Barangay::query()
            ->select(['brgyCode', 'brgyDesc', 'municipal', 'provCode'])
            ->when(filled($citymunCode), fn ($query) => $query->where('citymunCode', $citymunCode))
            ->when($search !== '', function ($query) use ($search): void {
                $provinceCodes = collect(static::getProvinceLabels())
                    ->filter(fn (string $province): bool => str($province)->contains($search, ignoreCase: true))
                    ->keys()
                    ->all();

                $query->where(function ($query) use ($provinceCodes, $search): void {
                    $query
                        ->where('brgyDesc', 'like', "%{$search}%")
                        ->orWhere('municipal', 'like', "%{$search}%")
                        ->orWhere('provCode', 'like', "%{$search}%")
                        ->orWhereIn('provCode', $provinceCodes);
                });
            })
            ->orderBy('municipal')
            ->orderBy('brgyDesc')
            ->limit(50)
            ->get()
            ->mapWithKeys(fn (Barangay $barangay): array => [
                $barangay->brgyCode => static::formatBarangayLabel($barangay),
            ])
            ->all();
    }

    private static function getBarangayOptionLabel(?string $value): ?string
    {
        if (blank($value) || ! SchemaFacade::hasTable('barangays')) {
            return null;
        }

        $barangay = Barangay::query()
            ->select(['brgyCode', 'brgyDesc', 'municipal', 'provCode'])
            ->where('brgyCode', $value)
            ->first();

        return $barangay ? static::formatBarangayLabel($barangay) : null;
    }

    private static function formatBarangayLabel(Barangay $barangay): string
    {
        return $barangay->brgyDesc;
    }

    /**
     * @return array<string, string>
     */
    private static function getProvinceLabels(): array
    {
        return [
            '1123' => 'Davao del Norte',
            '1124' => 'Davao del Sur',
            '1125' => 'Davao Oriental',
            '1182' => 'Davao de Oro',
            '1186' => 'Davao Occidental',
            '0' => 'No province',
        ];
    }

    private static function schoolColumnExists(string $column): bool
    {
        return array_key_exists($column, static::getSchoolColumns());
    }

    private static function isRequiredSchoolColumn(string $column): bool
    {
        if (in_array($column, ['id', 'created_at', 'updated_at'], true)) {
            return false;
        }

        $columnDefinition = static::getSchoolColumns()[$column] ?? null;

        if (! $columnDefinition) {
            return false;
        }

        return ! ((bool) ($columnDefinition['nullable'] ?? true))
            && ! ((bool) ($columnDefinition['auto_increment'] ?? false))
            && ($columnDefinition['default'] ?? null) === null;
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    private static function getSchoolColumns(): array
    {
        try {
            return collect(SchemaFacade::getColumns('schools'))
                ->keyBy('name')
                ->all();
        } catch (Throwable) {
            return [];
        }
    }
}
