<x-filament-panels::page>
    <div class="space-y-6">
        <section class="overflow-hidden rounded-lg border border-[#0038A8]/15 bg-white shadow-sm ring-1 ring-black/5 dark:border-white/10 dark:bg-gray-900">
            <div class="h-2 bg-[#0038A8]"></div>
            <div class="grid gap-0 lg:grid-cols-[1.6fr_1fr]">
                <div class="relative overflow-hidden bg-[#0038A8] px-6 py-7 text-white sm:px-8">
                    <div class="absolute inset-y-0 right-0 hidden w-28 bg-[#CE1126] lg:block"></div>
                    <div class="absolute bottom-0 right-0 hidden h-20 w-28 bg-[#FCD116] lg:block"></div>
                    <div class="relative max-w-3xl">
                        <div class="inline-flex items-center gap-2 rounded-md bg-white/10 px-3 py-1 text-xs font-semibold uppercase tracking-wide text-[#FCD116] ring-1 ring-white/20">
                            <x-filament::icon icon="heroicon-o-building-library" class="h-4 w-4" />
                            Department of Education
                        </div>
                        <h1 class="mt-4 text-2xl font-bold text-white sm:text-3xl">
                            Learning and Development Dashboard
                        </h1>
                        <p class="mt-2 max-w-2xl text-sm leading-6 text-blue-50">
                            Track programs, participants, approvals, and monthly training activity in one place.
                        </p>
                    </div>
                </div>

                <div class="grid grid-cols-3 divide-x divide-[#0038A8]/10 bg-white dark:divide-white/10 dark:bg-gray-950">
                    @foreach ($this->getDashboardHighlights() as $highlight)
                        <a
                            href="{{ $highlight['url'] }}"
                            class="group px-4 py-6 text-center outline-none transition hover:bg-[#0038A8]/5 focus-visible:bg-[#0038A8]/5 focus-visible:ring-2 focus-visible:ring-inset focus-visible:ring-[#0038A8] dark:hover:bg-white/5 dark:focus-visible:bg-white/5"
                        >
                            <div class="text-xl font-bold text-[#0038A8] dark:text-blue-300">
                                {{ $highlight['value'] }}
                            </div>
                            <div class="mt-1 text-xs font-medium text-gray-600 transition group-hover:text-[#0038A8] dark:text-gray-400 dark:group-hover:text-blue-200">
                                {{ $highlight['label'] }}
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>

        <section
            x-data="{
                selectedPanel: null,
                searchQuery: '',
                panels: @js($this->getDashboardModalPanels()),
                filteredRows() {
                    const rows = this.panels[this.selectedPanel]?.rows ?? []
                    const query = this.searchQuery.trim().toLowerCase()

                    if (! query) {
                        return rows
                    }

                    return rows.filter((row) => `${row.title} ${row.meta} ${row.badge}`.toLowerCase().includes(query))
                },
            }"
            x-on:keydown.escape.window="selectedPanel = null"
            class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4"
        >
            @foreach ($this->getDashboardStats() as $stat)
                @php
                    $toneClasses = match ($stat['tone']) {
                        'green' => 'border-emerald-200 bg-emerald-50 text-emerald-700 dark:border-emerald-900/60 dark:bg-emerald-950/30 dark:text-emerald-300',
                        'yellow' => 'border-[#FCD116]/70 bg-[#FCD116]/15 text-[#806300] dark:border-[#FCD116]/40 dark:bg-[#FCD116]/10 dark:text-[#FCD116]',
                        'red' => 'border-[#CE1126]/25 bg-[#CE1126]/10 text-[#CE1126] dark:border-[#CE1126]/50 dark:bg-[#CE1126]/15 dark:text-red-300',
                        default => 'border-[#0038A8]/25 bg-[#0038A8]/10 text-[#0038A8] dark:border-[#0038A8]/60 dark:bg-[#0038A8]/20 dark:text-blue-300',
                    };
                @endphp

                <button
                    type="button"
                    x-on:click="selectedPanel = @js($stat['key']); searchQuery = ''"
                    class="group block rounded-lg border bg-white p-5 text-left shadow-sm ring-1 ring-black/5 outline-none transition hover:-translate-y-0.5 hover:border-[#0038A8]/30 hover:shadow-md focus-visible:ring-2 focus-visible:ring-[#0038A8] dark:border-white/10 dark:bg-gray-900 dark:hover:border-white/20"
                >
                    <div class="flex items-center justify-between gap-4">
                        <div>
                            <div class="text-sm font-medium text-gray-600 transition group-hover:text-[#0038A8] dark:text-gray-400 dark:group-hover:text-blue-200">
                                {{ $stat['label'] }}
                            </div>
                            <div class="mt-2 text-3xl font-bold text-gray-950 dark:text-white">
                                {{ $stat['value'] }}
                            </div>
                            <div class="mt-3 inline-flex items-center gap-1 text-xs font-semibold text-[#0038A8] opacity-0 transition group-hover:opacity-100 group-focus-visible:opacity-100 dark:text-blue-300">
                                View details
                                <x-filament::icon icon="heroicon-m-arrow-up-right" class="h-3.5 w-3.5" />
                            </div>
                        </div>

                        <div @class(['flex h-12 w-12 shrink-0 items-center justify-center rounded-lg border transition group-hover:scale-105', $toneClasses])>
                            <x-filament::icon :icon="$stat['icon']" class="h-6 w-6" />
                        </div>
                    </div>
                </button>
            @endforeach

            <div
                x-cloak
                x-show="selectedPanel"
                x-transition.opacity.duration.150ms
                class="fixed inset-0 z-50 flex items-center justify-center bg-gray-950/60 p-4"
                role="dialog"
                aria-modal="true"
            >
                <button
                    type="button"
                    x-on:click="selectedPanel = null"
                    class="absolute inset-0 cursor-default"
                    aria-label="Close details"
                ></button>

                <div
                    x-show="selectedPanel"
                    x-transition.scale.origin.center.duration.150ms
                    class="relative max-h-[85vh] w-full max-w-2xl overflow-hidden rounded-lg bg-white shadow-2xl ring-1 ring-black/10 dark:bg-gray-900 dark:ring-white/10"
                >
                    <div class="flex items-start justify-between gap-4 border-b border-gray-200 px-5 py-4 dark:border-white/10">
                        <div>
                            <h2 class="text-base font-semibold text-gray-950 dark:text-white" x-text="panels[selectedPanel]?.title"></h2>
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400" x-text="panels[selectedPanel]?.description"></p>
                        </div>

                        <button
                            type="button"
                            x-on:click="selectedPanel = null"
                            class="rounded-lg p-2 text-gray-400 outline-none transition hover:bg-gray-100 hover:text-gray-700 focus-visible:ring-2 focus-visible:ring-[#0038A8] dark:hover:bg-white/10 dark:hover:text-gray-200"
                            aria-label="Close details"
                        >
                            <x-filament::icon icon="heroicon-m-x-mark" class="h-5 w-5" />
                        </button>
                    </div>

                    <div class="max-h-[60vh] overflow-y-auto p-5">
                        <div class="mb-4">
                            <label class="sr-only" for="dashboard-modal-search">Search records</label>
                            <div class="relative">
                                <x-filament::icon icon="heroicon-m-magnifying-glass" class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-gray-400" />
                                <input
                                    id="dashboard-modal-search"
                                    type="search"
                                    x-model.debounce.150ms="searchQuery"
                                    placeholder="Search records"
                                    class="w-full rounded-lg border border-gray-300 bg-white py-2 pl-9 pr-3 text-sm text-gray-950 outline-none transition placeholder:text-gray-400 focus:border-[#0038A8] focus:ring-2 focus:ring-[#0038A8]/20 dark:border-white/10 dark:bg-gray-950 dark:text-white dark:focus:border-blue-300"
                                />
                            </div>
                        </div>

                        <template x-if="filteredRows().length === 0">
                            <div class="rounded-lg border border-dashed border-gray-300 px-4 py-8 text-center dark:border-white/20">
                                <x-filament::icon icon="heroicon-o-inbox" class="mx-auto h-8 w-8 text-gray-400" />
                                <p class="mt-3 text-sm font-medium text-gray-950 dark:text-white" x-text="searchQuery ? 'No matching records found.' : panels[selectedPanel]?.empty"></p>
                            </div>
                        </template>

                        <div class="space-y-3">
                            <template x-for="row in filteredRows()" x-bind:key="`${selectedPanel}-${row.title}-${row.meta}`">
                                <div class="rounded-lg border border-gray-200 p-4 transition hover:border-[#0038A8]/30 hover:bg-gray-50 dark:border-white/10 dark:hover:border-white/20 dark:hover:bg-white/5">
                                    <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                                        <div class="min-w-0">
                                            <h3 class="truncate text-sm font-semibold text-gray-950 dark:text-white" x-text="row.title"></h3>
                                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400" x-text="row.meta"></p>
                                        </div>

                                        <div class="flex shrink-0 items-center gap-2">
                                            <span class="rounded-lg bg-[#0038A8]/10 px-2.5 py-1 text-xs font-semibold text-[#0038A8] dark:bg-blue-300/10 dark:text-blue-300" x-text="row.badge"></span>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="space-y-4">
            <div class="flex flex-col gap-2 border-b border-[#0038A8]/15 pb-4 dark:border-white/10 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 class="text-base font-semibold text-gray-950 dark:text-white">
                        Training Calendar and Program Status
                    </h2>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                        Schedule programs, review participants, and prepare certificates.
                    </p>
                </div>

                <div class="flex items-center gap-2 text-xs font-semibold uppercase text-gray-500 dark:text-gray-400">
                    <span class="h-2.5 w-2.5 rounded-full bg-[#0038A8]"></span>
                    <span class="h-2.5 w-2.5 rounded-full bg-[#CE1126]"></span>
                    <span class="h-2.5 w-2.5 rounded-full bg-[#FCD116]"></span>
                    DepEd Palette
                </div>
            </div>

            {{ $this->content }}
        </section>
    </div>
</x-filament-panels::page>
