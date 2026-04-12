@php
    use Filament\Support\Facades\FilamentAsset;
    use Filament\Support\Facades\FilamentColor;
    use Filament\Support\View\Components\ButtonComponent;
    use Guava\Calendar\Enums\Context;
@endphp

<x-filament-widgets::widget>
    <x-filament::section
        :after-header="$this->getCachedHeaderActionsComponent()"
        :footer="$this->getCachedFooterActionsComponent()"
        class="lnd-calendar-widget overflow-hidden"
    >
        <style>
            .lnd-calendar-widget .ec {
                border: 0;
                color: rgb(17 24 39);
            }

            .dark .lnd-calendar-widget .ec {
                color: rgb(243 244 246);
            }

            .lnd-calendar-widget .ec-toolbar {
                align-items: center;
                gap: .75rem;
                padding: .75rem;
            }

            .lnd-calendar-widget .ec-button {
                border-radius: .5rem;
                border-color: rgba(0, 56, 168, .18);
                background: white;
                color: rgb(0 56 168);
                font-weight: 700;
                text-transform: capitalize;
            }

            .lnd-calendar-widget .ec-button:hover,
            .lnd-calendar-widget .ec-button.ec-active {
                background: rgb(0 56 168);
                color: white;
            }

            .dark .lnd-calendar-widget .ec-button {
                border-color: rgba(255, 255, 255, .12);
                background: rgb(17 24 39);
                color: rgb(191 219 254);
            }

            .lnd-calendar-widget .ec-header,
            .lnd-calendar-widget .ec-day {
                border-color: rgba(0, 56, 168, .12);
            }

            .dark .lnd-calendar-widget .ec-header,
            .dark .lnd-calendar-widget .ec-day {
                border-color: rgba(255, 255, 255, .10);
            }

            .lnd-calendar-widget .ec-day-head {
                color: rgb(75 85 99);
                font-size: .75rem;
                font-weight: 800;
                letter-spacing: 0;
                text-transform: uppercase;
            }

            .dark .lnd-calendar-widget .ec-day-head {
                color: rgb(209 213 219);
            }

            .lnd-calendar-widget .ec-day.ec-today {
                background: rgba(252, 209, 22, .18);
            }

            .lnd-calendar-widget .ec-event {
                border-radius: .5rem;
                border: 0;
                box-shadow: 0 8px 18px rgba(17, 24, 39, .12);
            }

            .lnd-calendar-widget .ec-event.ec-preview,
            .lnd-calendar-widget .ec-now-indicator {
                z-index: 30;
            }
        </style>

        <x-slot name="heading">
            <div class="flex flex-col gap-2">
                <span class="text-base font-semibold text-gray-950 dark:text-white">
                    {{ $this->getHeading() }}
                </span>
                <span class="text-sm font-normal text-gray-600 dark:text-gray-400">
                    Click a date to create a program, drag across dates for a range, or open an event for actions.
                </span>
            </div>
        </x-slot>

        <div class="mb-4 grid grid-cols-1 gap-3 rounded-lg border border-[#0038A8]/10 bg-[#0038A8]/5 p-3 dark:border-white/10 dark:bg-white/5 sm:grid-cols-3">
            <div class="flex items-center gap-2 text-sm text-gray-700 dark:text-gray-300">
                <span class="h-3 w-3 rounded-sm bg-[#ffff00] ring-1 ring-gray-300"></span>
                Without accreditation
            </div>
            <div class="flex items-center gap-2 text-sm text-gray-700 dark:text-gray-300">
                <span class="h-3 w-3 rounded-sm bg-[#b37820]"></span>
                With accreditation
            </div>
            <div class="flex items-center gap-2 text-sm text-gray-700 dark:text-gray-300">
                <span class="h-3 w-3 rounded-sm bg-[#FCD116]"></span>
                Today
            </div>
        </div>

        <div
            wire:ignore
            x-load
            x-load-src="{{ FilamentAsset::getAlpineComponentSrc('calendar', 'guava/calendar') }}"
            x-data="calendar({
                view: @js($this->getCalendarView()),
                locale: @js($this->getLocale()),
                firstDay: @js($this->getFirstDay()),
                dayMaxEvents: @js($this->getDayMaxEvents()),
                eventContent: @js($this->getEventContentJs()),
                eventClickEnabled: @js($this->isEventClickEnabled()),
                eventDragEnabled: @js($this->isEventDragEnabled()),
                eventResizeEnabled: @js($this->isEventResizeEnabled()),
                noEventsClickEnabled: @js($this->isNoEventsClickEnabled()),
                dateClickEnabled: @js($this->isDateClickEnabled()),
                dateSelectEnabled: @js($this->isDateSelectEnabled()),
                datesSetEnabled: @js($this->isDatesSetEnabled()),
                viewDidMountEnabled: @js($this->isViewDidMountEnabled()),
                eventAllUpdatedEnabled: @js($this->isEventAllUpdatedEnabled()),
                hasDateClickContextMenu: @js($this->hasContextMenu(Context::DateClick)),
                hasDateSelectContextMenu: @js($this->hasContextMenu(Context::DateSelect)),
                hasEventClickContextMenu: @js($this->hasContextMenu(Context::EventClick)),
                hasNoEventsClickContextMenu: @js($this->hasContextMenu(Context::NoEventsClick)),
                resources: @js($this->getResourcesJs()),
                resourceLabelContent: @js($this->getResourceLabelContentJs()),
                theme: @js($this->getTheme()),
                options: @js($this->getOptions()),
                eventAssetUrl: @js(FilamentAsset::getAlpineComponentSrc('calendar-event', 'guava/calendar')),
            })"
            @class([...FilamentColor::getComponentClasses(ButtonComponent::class, 'primary'), 'rounded-lg border border-gray-200 bg-white p-2 dark:border-white/10 dark:bg-gray-950'])
        >
            <div data-calendar></div>

            @if ($this->hasContextMenu())
                <x-guava-calendar::context-menu />
            @endif
        </div>
    </x-filament::section>

    <x-filament-actions::modals />
</x-filament-widgets::widget>
