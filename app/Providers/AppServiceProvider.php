<?php

namespace App\Providers;

use Filament\Forms\Components\Field;
use Filament\Forms\Components\FileUpload;
use Filament\Infolists\Components\ImageEntry;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Table;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        FileUpload::configureUsing(fn (FileUpload $fileUpload) => $fileUpload->visibility('public'));
        ImageColumn::configureUsing(fn (ImageColumn $imageColumn) => $imageColumn->visibility('public'));
        ImageEntry::configureUsing(fn (ImageEntry $imageEntry) => $imageEntry->visibility('public'));

        Table::configureUsing(fn (Table $table) => $table
            ->deferFilters(false)
            ->paginationPageOptions([5, 10, 25, 50, 'all']));

        Grid::configureUsing(fn (Grid $grid) => $grid->columnSpanFull());
        Section::configureUsing(fn (Section $section) => $section->columnSpanFull());
        Fieldset::configureUsing(fn (Fieldset $fieldset) => $fieldset->columnSpanFull());

        Field::configureUsing(fn (Field $field) => $field->uniqueValidationIgnoresRecordByDefault(false));
    }
}
