<div class="flex min-w-0 flex-col gap-1 px-1 py-0.5">
    <span
        x-text="event.title"
        :class="event.extendedProps.with_accreditation ? 'text-white' : 'text-indigo-950'"
        class="truncate text-xs font-bold uppercase"
    ></span>

    <div class="flex items-center gap-1 text-[11px] font-semibold" :class="event.extendedProps.with_accreditation ? 'text-white/90' : 'text-indigo-800'">
        <x-filament::icon icon="heroicon-m-users" class="h-3 w-3" />
        <span x-text="event.extendedProps.participants"></span>
        <span>participants</span>
    </div>
</div>
