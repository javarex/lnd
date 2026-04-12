@php
    $participantsCount = (int) ($record->participants_count ?? $record->participants?->count() ?? 0);
@endphp

<div class="flex flex-col gap-1.5">
    <strong class="text-xs font-semibold uppercase text-gray-800 dark:text-white">
        {{ $state }}
    </strong>

    <div class="flex flex-wrap gap-1.5">
        <span class="inline-flex items-center gap-1 rounded-lg bg-[#0038A8]/10 px-1.5 py-0.5 text-xs font-medium text-[#0038A8] dark:bg-blue-300/10 dark:text-blue-300">
            @svg('icon-s-calendar-date-range', ['class' => 'size-3.5'])
            {{ $record->duration }}
        </span>

        <span class="inline-flex items-center gap-1 rounded-lg bg-emerald-50 px-1.5 py-0.5 text-xs font-medium text-emerald-700 dark:bg-emerald-950/30 dark:text-emerald-300">
            @svg('icon-s-users', ['class' => 'size-3.5'])
            {{ $participantsCount }} participants
        </span>
    </div>

    @if ($participantsCount === 0)
        <p class="rounded-lg bg-[#CE1126]/10 px-1.5 py-0.5 text-xs font-medium text-[#CE1126] dark:bg-[#CE1126]/15 dark:text-red-300">
            Add participant/s before updating status.
        </p>
    @endif
</div>
