<div class="flex flex-col space-y-2">
    <strong class="text-md dark:text-white text-gray-700">{{$state}}</strong>
    <p class="text-sm flex gap-x-2 bg-gray-500 text-white rounded-md px-2">
         @svg('icon-s-calendar-date-range', ['class' => 'size-4 my-auto mx-0'])
        {{$record->duration}}
    </p>
    <p class="text-sm flex bg-primary-500 text-white rounded-md px-2 gap-x-2">
         @svg('icon-s-users', ['class' => 'size-4 my-auto mx-0'])
        Participants:
        {{$record->participants?->count()}}
    </p>
    @if($record->participants->count() == 0)
    <p class="text-white text-[9pt] bg-red-500 rounded-md px-2">
        Unable to update status. Please add participant/s
    </p>
    @endif

</div>
