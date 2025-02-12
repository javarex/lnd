<?php

namespace App\Filament\Resources\SchoolResource\Pages;

use App\Filament\Resources\SchoolResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSchool extends EditRecord
{
    protected static string $resource = SchoolResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    // public function mount(int|string $record): void
    // {
    //     $this->record = $this->resolveRecord($record)->load('barangay');
       
    //     // dd()
    //     // $this->form->fill([
    //     //     'citymunCode' => $this->record->barangay->citymunCode,
    //     // ]);
    // }
    protected function beforeFill(): void
    {
        $this->form->fill([
            'citymunCode' => $this->record->barangay->citymunCode,
         ]);
        // Runs before the form fields are populated from the database.
    }
}
