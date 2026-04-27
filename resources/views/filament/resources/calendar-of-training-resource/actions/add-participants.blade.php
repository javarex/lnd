@php
    $selectedParticipantIds = $this->selectedParticipantIds ?? [];
@endphp

<div class="grid gap-5 lg:grid-cols-[0.9fr_1.1fr]">
    <section class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-gray-800 dark:bg-gray-950">
        <div class="border-b border-gray-100 bg-gray-50 px-5 py-4 dark:border-gray-800 dark:bg-gray-900">
            <div class="flex items-start justify-between gap-3">
                <div>
                    <h3 class="text-sm font-semibold text-gray-950 dark:text-white">
                        Added participants
                    </h3>

                    <p class="text-xs text-gray-500 dark:text-gray-400">
                        Current participants attached to this training.
                    </p>
                </div>

                <span class="rounded-full bg-gray-200 px-3 py-1 text-xs font-semibold text-gray-700 dark:bg-gray-800 dark:text-gray-200">
                    {{ $addedParticipants->count() }}
                </span>
            </div>

            <div class="relative mt-4">
                <input
                    type="search"
                    wire:model.live.debounce.300ms="addedParticipantSearch"
                    placeholder="Search added participants"
                    class="block w-full rounded-xl border-gray-300 bg-white py-2.5 pl-4 pr-10 text-sm text-gray-950 shadow-sm transition focus:border-primary-500 focus:ring-primary-500 dark:border-gray-700 dark:bg-gray-950 dark:text-white"
                />

                <div class="pointer-events-none absolute inset-y-0 right-3 flex items-center text-gray-400">
                    <x-filament::icon icon="heroicon-m-magnifying-glass" class="h-5 w-5" />
                </div>
            </div>
        </div>

        <div class="max-h-[34rem] overflow-y-auto p-3">
            <div class="grid gap-3">
                @forelse ($addedParticipants as $participant)
                    <div class="flex items-center gap-3 rounded-xl border border-gray-200 bg-gray-50 p-3 dark:border-gray-800 dark:bg-gray-900">
                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-primary-100 text-sm font-bold text-primary-700 dark:bg-primary-950 dark:text-primary-300">
                            {{ str($participant->employee?->full_name)->trim()->substr(0, 1)->upper() }}
                        </div>

                        <div class="min-w-0">
                            <p class="truncate text-sm font-semibold text-gray-950 dark:text-white">
                                {{ $participant->employee?->full_name ?? 'Unnamed participant' }}
                            </p>

                            <p class="truncate text-xs text-gray-500 dark:text-gray-400">
                                {{ $participant->employee?->school?->school ?: 'No school recorded' }}
                            </p>
                        </div>
                    </div>
                @empty
                    <div class="rounded-xl border border-dashed border-gray-300 p-8 text-center dark:border-gray-700">
                        <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-gray-100 text-gray-500 dark:bg-gray-900 dark:text-gray-400">
                            <x-filament::icon icon="heroicon-o-user-group" class="h-6 w-6" />
                        </div>

                        <h3 class="mt-3 text-sm font-semibold text-gray-950 dark:text-white">
                            No participants yet
                        </h3>

                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                            Use the panel on the right to add participants.
                        </p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <section class="space-y-4">
        <div class="rounded-2xl border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-800 dark:bg-gray-950">
            <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
                <div class="min-w-0 flex-1">
                    <label for="participant-search" class="text-sm font-semibold text-gray-950 dark:text-white">
                        Search new participants
                    </label>

                    <div class="relative mt-2">
                        <input
                            id="participant-search"
                            type="search"
                            wire:model.live.debounce.300ms="participantSearch"
                            placeholder="Search by name or school"
                            class="block w-full rounded-xl border-gray-300 bg-gray-50 py-3 pl-4 pr-10 text-sm text-gray-950 shadow-sm transition focus:border-primary-500 focus:bg-white focus:ring-primary-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white dark:focus:bg-gray-950"
                        />

                        <div class="pointer-events-none absolute inset-y-0 right-3 flex items-center text-gray-400">
                            <x-filament::icon icon="heroicon-m-magnifying-glass" class="h-5 w-5" />
                        </div>
                    </div>
                </div>

                <div class="rounded-xl bg-primary-50 px-4 py-3 text-sm text-primary-700 dark:bg-primary-950 dark:text-primary-300">
                    <span class="font-semibold">{{ count($selectedParticipantIds) }}</span>
                    selected
                </div>

                @if ($createParticipantAction)
                    <div class="shrink-0">
                        {!! $createParticipantAction->toHtml() !!}
                    </div>
                @endif
            </div>
        </div>

        <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-gray-800 dark:bg-gray-950">
            <div class="border-b border-gray-100 px-4 py-3 dark:border-gray-800">
                <h3 class="text-sm font-semibold text-gray-950 dark:text-white">
                    Available to add
                </h3>

                <p class="text-xs text-gray-500 dark:text-gray-400">
                    Already-added participants are hidden from this list.
                </p>
            </div>

            <div class="max-h-96 overflow-y-auto p-3">
                <div class="grid gap-3">
                    @forelse ($participants as $participant)
                        <label
                            wire:key="add-participant-{{ $record->getKey() }}-{{ $participant->getKey() }}"
                            class="group flex cursor-pointer items-center gap-4 rounded-xl border border-gray-200 bg-gray-50 p-4 transition hover:border-primary-300 hover:bg-primary-50 dark:border-gray-800 dark:bg-gray-900 dark:hover:border-primary-700 dark:hover:bg-primary-950"
                        >
                            <input
                                type="checkbox"
                                value="{{ $participant->getKey() }}"
                                wire:model.live="selectedParticipantIds"
                                class="h-5 w-5 rounded border-gray-300 text-primary-600 shadow-sm focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-950"
                            />

                            <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-white text-sm font-bold text-primary-700 ring-1 ring-gray-200 group-hover:ring-primary-200 dark:bg-gray-950 dark:text-primary-300 dark:ring-gray-800">
                                {{ str($participant->full_name)->trim()->substr(0, 1)->upper() }}
                            </div>

                            <div class="min-w-0 flex-1">
                                <p class="truncate text-sm font-semibold text-gray-950 dark:text-white">
                                    {{ $participant->full_name }}
                                </p>

                                <p class="truncate text-xs text-gray-500 dark:text-gray-400">
                                    {{ $participant->school?->school ?: 'No school recorded' }}
                                </p>
                            </div>
                        </label>
                    @empty
                        <div class="rounded-xl border border-dashed border-gray-300 p-8 text-center dark:border-gray-700">
                            <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-gray-100 text-gray-500 dark:bg-gray-900 dark:text-gray-400">
                                <x-filament::icon icon="heroicon-o-users" class="h-6 w-6" />
                            </div>

                            <h3 class="mt-3 text-sm font-semibold text-gray-950 dark:text-white">
                                No participants found
                            </h3>

                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                Try a different search term or add participants from the employee records first.
                            </p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        @error('participant_ids')
            <p class="text-sm text-danger-600 dark:text-danger-400">{{ $message }}</p>
        @enderror

        @error('participant_ids.*')
            <p class="text-sm text-danger-600 dark:text-danger-400">{{ $message }}</p>
        @enderror

        <div class="rounded-2xl border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-800 dark:bg-gray-950">
            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Choose one or more participants, then add them to this training.
                </p>

                <x-filament::button
                    type="button"
                    icon="heroicon-o-user-plus"
                    wire:click="callMountedAction"
                    wire:loading.attr="disabled"
                    wire:target="callMountedAction"
                >
                    Add selected participants
                </x-filament::button>
            </div>
        </div>
    </section>
</div>
