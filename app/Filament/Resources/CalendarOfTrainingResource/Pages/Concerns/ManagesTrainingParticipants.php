<?php

namespace App\Filament\Resources\CalendarOfTrainingResource\Pages\Concerns;

use App\Models\CalendarOfTraining;
use App\Models\Employee;
use App\Models\Participant;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

trait ManagesTrainingParticipants
{
    public string $addedParticipantSearch = '';

    public string $participantSearch = '';

    /**
     * @var array<int, int|string>
     */
    public array $selectedParticipantIds = [];

    public function resetAddParticipantsState(): void
    {
        $this->addedParticipantSearch = '';
        $this->participantSearch = '';
        $this->selectedParticipantIds = [];
    }

    /**
     * @return Collection<int, Participant>
     */
    public function getAddedParticipantsForAddParticipants(CalendarOfTraining $training): Collection
    {
        return $training->participants()
            ->with('employee.school:id,school')
            ->when(filled($this->addedParticipantSearch), function (Builder $query): void {
                $search = trim($this->addedParticipantSearch);

                $query->whereHas('employee', function (Builder $query) use ($search): void {
                    $query
                        ->where('full_name', 'like', "%{$search}%")
                        ->orWhere('first_name', 'like', "%{$search}%")
                        ->orWhere('middle_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhereHas('school', function (Builder $query) use ($search): void {
                            $query->where('school', 'like', "%{$search}%");
                        });
                });
            })
            ->get()
            ->sortBy('employee.full_name')
            ->values();
    }

    /**
     * @return Collection<int, Employee>
     */
    public function getAvailableParticipantsForAddParticipants(CalendarOfTraining $training): Collection
    {
        $attachedEmployeeIds = $training->participants()
            ->select('employee_id');

        return Employee::query()
            ->select(['id', 'full_name', 'school_id'])
            ->with('school:id,school')
            ->whereNotIn('id', $attachedEmployeeIds)
            ->whereNotIn('employee_type', ['TWG', 'Division Employee'])
            ->when(filled($this->participantSearch), function (Builder $query): void {
                $search = trim($this->participantSearch);

                $query->where(function (Builder $query) use ($search): void {
                    $query
                        ->where('full_name', 'like', "%{$search}%")
                        ->orWhere('first_name', 'like', "%{$search}%")
                        ->orWhere('middle_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhereHas('school', function (Builder $query) use ($search): void {
                            $query->where('school', 'like', "%{$search}%");
                        });
                });
            })
            ->orderBy('full_name')
            ->limit(25)
            ->get();
    }

    public function addSelectedParticipantsToTraining(CalendarOfTraining $training): void
    {
        $participantIds = collect($this->selectedParticipantIds)
            ->filter(fn (int|string|null $participantId): bool => filled($participantId))
            ->map(fn (int|string $participantId): int => (int) $participantId)
            ->unique()
            ->values()
            ->all();

        Validator::make(
            ['participant_ids' => $participantIds],
            [
                'participant_ids' => ['required', 'array', 'min:1'],
                'participant_ids.*' => [
                    'integer',
                    'distinct',
                    Rule::exists('employees', 'id')->where(function ($query): void {
                        $query->whereNotIn('employee_type', ['TWG', 'Division Employee']);
                    }),
                    Rule::unique('participants', 'employee_id')
                        ->where('calendar_of_training_id', $training->getKey()),
                ],
            ],
            [
                'participant_ids.required' => 'Select at least one participant.',
                'participant_ids.*.unique' => 'One or more selected participants are already attached to this training.',
            ],
        )->validate();

        $attachedEmployeeIds = Participant::query()
            ->where('calendar_of_training_id', $training->getKey())
            ->whereIn('employee_id', $participantIds)
            ->pluck('employee_id');

        $newParticipantIds = collect($participantIds)
            ->diff($attachedEmployeeIds)
            ->values();

        if ($newParticipantIds->isEmpty()) {
            Notification::make()
                ->warning()
                ->title('Participants already added')
                ->body('The selected participants are already attached to this training.')
                ->send();

            return;
        }

        $now = now();

        DB::transaction(function () use ($newParticipantIds, $training, $now): void {
            Participant::query()->insertOrIgnore(
                $newParticipantIds
                    ->map(fn (int $employeeId): array => [
                        'calendar_of_training_id' => $training->getKey(),
                        'employee_id' => $employeeId,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ])
                    ->all()
            );
        });

        $this->resetAddParticipantsState();

        Notification::make()
            ->success()
            ->title('Participants added')
            ->body($newParticipantIds->count().' participant(s) added to the training.')
            ->send();
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function createEmployeeParticipantForTraining(CalendarOfTraining $training, array $data): Employee
    {
        return DB::transaction(function () use ($data, $training): Employee {
            $employee = Employee::query()->create($data);

            Participant::query()->insertOrIgnore([
                [
                    'calendar_of_training_id' => $training->getKey(),
                    'employee_id' => $employee->getKey(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            ]);

            $this->resetAddParticipantsState();

            if (method_exists($this, 'forceRender')) {
                $this->forceRender();
            }

            return $employee->refresh();
        });
    }
}
