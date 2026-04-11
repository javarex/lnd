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
                        <div class="px-4 py-6 text-center">
                            <div class="text-xl font-bold text-[#0038A8] dark:text-blue-300">
                                {{ $highlight['value'] }}
                            </div>
                            <div class="mt-1 text-xs font-medium text-gray-600 dark:text-gray-400">
                                {{ $highlight['label'] }}
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        <section class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
            @foreach ($this->getDashboardStats() as $stat)
                @php
                    $toneClasses = match ($stat['tone']) {
                        'green' => 'border-emerald-200 bg-emerald-50 text-emerald-700 dark:border-emerald-900/60 dark:bg-emerald-950/30 dark:text-emerald-300',
                        'yellow' => 'border-[#FCD116]/70 bg-[#FCD116]/15 text-[#806300] dark:border-[#FCD116]/40 dark:bg-[#FCD116]/10 dark:text-[#FCD116]',
                        'red' => 'border-[#CE1126]/25 bg-[#CE1126]/10 text-[#CE1126] dark:border-[#CE1126]/50 dark:bg-[#CE1126]/15 dark:text-red-300',
                        default => 'border-[#0038A8]/25 bg-[#0038A8]/10 text-[#0038A8] dark:border-[#0038A8]/60 dark:bg-[#0038A8]/20 dark:text-blue-300',
                    };
                @endphp

                <div class="rounded-lg border bg-white p-5 shadow-sm ring-1 ring-black/5 dark:border-white/10 dark:bg-gray-900">
                    <div class="flex items-center justify-between gap-4">
                        <div>
                            <div class="text-sm font-medium text-gray-600 dark:text-gray-400">
                                {{ $stat['label'] }}
                            </div>
                            <div class="mt-2 text-3xl font-bold text-gray-950 dark:text-white">
                                {{ $stat['value'] }}
                            </div>
                        </div>

                        <div @class(['flex h-12 w-12 items-center justify-center rounded-lg border', $toneClasses])>
                            <x-filament::icon :icon="$stat['icon']" class="h-6 w-6" />
                        </div>
                    </div>
                </div>
            @endforeach
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
