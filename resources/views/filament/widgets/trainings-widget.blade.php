<x-filament-widgets::widget class="fi-wi-table lnd-trainings-widget">
    {{ \Filament\Support\Facades\FilamentView::renderHook(\Filament\Widgets\View\WidgetsRenderHook::TABLE_WIDGET_START, scopes: static::class) }}

    <div class="overflow-hidden rounded-lg border border-[#0038A8]/10 bg-white shadow-sm ring-1 ring-black/5 dark:border-white/10 dark:bg-gray-900">
        <div class="border-b border-[#0038A8]/10 bg-[#0038A8]/5 px-4 py-3 dark:border-white/10 dark:bg-white/5">
            <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 class="text-sm font-semibold text-gray-950 dark:text-white">
                        Training Records
                    </h2>
                    <p class="mt-0.5 text-xs text-gray-600 dark:text-gray-400">
                        Search schedules, update approval status, and prepare certificates.
                    </p>
                </div>

                <div class="inline-flex w-fit items-center gap-1.5 rounded-lg border border-[#0038A8]/15 bg-white px-2.5 py-1.5 text-xs font-semibold text-[#0038A8] dark:border-blue-300/20 dark:bg-gray-950 dark:text-blue-300">
                    <x-filament::icon icon="heroicon-o-table-cells" class="h-3.5 w-3.5" />
                    Live table
                </div>
            </div>
        </div>

        <div class="p-1.5 sm:p-2">
            {{ $this->table ?? null }}
        </div>
    </div>

    {{ \Filament\Support\Facades\FilamentView::renderHook(\Filament\Widgets\View\WidgetsRenderHook::TABLE_WIDGET_END, scopes: static::class) }}
</x-filament-widgets::widget>
