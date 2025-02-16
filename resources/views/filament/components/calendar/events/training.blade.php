<div class="flex flex-col">
    <!-- <span x-text="timeText"></span> -->
{{--    <span x-text="event.extendedProps.with_accreditation"></span>--}}
    <span x-text="event.title" :class="event.extendedProps.with_accreditation ? 'text-white' : 'text-indigo-900'" class="font-bold uppercase"></span>
    <template x-show="event.extendedProps.participants > 0">
        <span><span x-text="event.extendedProps.participants" ></span> participants</span>
    </template>
</div>
