<div class="flex flex-col">
    <!-- <span x-text="timeText"></span> -->
    <span x-text="event.title" class="text-indigo-900 font-bold"></span>
    <template x-show="event.extendedProps.participants > 0">
        <span><span x-text="event.extendedProps.participants" ></span> participants</span>
    </template>
</div>