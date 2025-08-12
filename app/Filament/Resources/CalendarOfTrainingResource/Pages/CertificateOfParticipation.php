<?php

namespace App\Filament\Resources\CalendarOfTrainingResource\Pages;

use Filament\Resources\Pages\Page;
use App\Filament\Resources\CalendarOfTrainingResource;
use App\Models\School;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class CertificateOfParticipation extends Page implements HasTable
{
    use InteractsWithTable;

    use InteractsWithRecord;

    protected static string $resource = CalendarOfTrainingResource::class;

    protected static string $view = 'filament.resources.calendar-of-training-resource.pages.certificate-of-participation';

    
    public function mount(int | string $record): void
    {
        $this->record = $this->resolveRecord($record);
    }

    public function table(Table $table): Table
    {
   
        return $table
                ->query(fn() => School::whereHas('employees.trainings', function($query) {
                    $query->where('calendar_of_training_id', $this->record->id);
                })
                ->withCount([
                    'employees' => function($query) {
                        $query->whereHas('trainings', function($query) {
                            $query->where('calendar_of_training_id', $this->record->id);
                        });
                    }
                ])
                )
                ->columns([
                    TextColumn::make('school'),
                    TextColumn::make('employees_count')
                    ->label('No of participants'),
                ])
                ->bulkActions([
                    BulkAction::make('download')
                        ->label('Download  Certificate')
                        ->icon('heroicon-o-arrow-down-on-square-stack')
                        // ->url(function($records) {
                        //     if ($records) {
                        //         # code...
                        //         dd($records);
                        //     }
                        // })
                        // ->url(function ($records) {
                        //     // Generate query string of selected IDs
                        //     $ids = $records->pluck('id')->toArray();
                        //     $query = http_build_query(['ids' => $ids]);

                        //     return url('/download-certificates?' . $query);
                        // })
                        // ->action(function($records, $action) {
                        //     dd($records->pluck('id')->toArray());
                        // })
                        ->action(function ($records, $livewire) {
                            $url = '';
                            if ($records) {
                                $ids = $records->pluck('id')->toArray();
    
                                $url = route('download.school.certs', [
                                    'ids' => $ids,
                                    'training' => $this->record->id
                                ]);
                            }

                            return redirect($url);
                        })
                        ->url(function($records) {
                            $url = '';
                            if ($records) {
                                $ids = $records->pluck('id')->toArray();
    
                                $url = route('download.school.certs', [
                                    'ids' => implode(',', $ids),
                                ]);
                            }

                            return $url;
                        }, true)
                ]);
    }

}
