<div>
    <div x-cloak class="flex flex-col min-h-screen">
        <div class="flex-1">
            <div class="bg-cover bg-center bg-no-repeat bg-primary-100 dark:bg-primary">
                <div class="container mx-auto px-4 pt-4 md:pt-10 pb-40"></div>
            </div>
            <div class="container mx-auto px-4 py-4 -mt-40">
                <div>
                    <div>
                        <div class="py-4 md:py-8">
                            <div class="flex -mx-4 block overflow-x-auto pb-2" wire:sortable-group="updateTaskLabel">
                                @foreach($boards as $board)
                                    <div class="w-1/2 md:w-1/4 px-4 flex-shrink-0" wire:key="group-{{ $board }}">
                                        <div
                                            class="bg-gray-100 border-primary-dark dark:border-primary-darker pb-4 rounded-lg shadow overflow-y-auto overflow-x-hidden border-t-8"
                                            style="min-height: 100px">
                                            <div
                                                class="flex justify-between items-center px-4 py-2 bg-gray-100 sticky top-0">
                                                <h2 class="font-medium text-gray-800">{{$board}}</h2>
                                            </div>
                                            <div class="px-4 pb-20" wire:sortable-group.item-group="{{ $board }}">
                                                @foreach($tasks as $task)
                                                    @if($task['boardName'] == $board)
                                                        <div wire:sortable-group.item="{{ $task['id'] }}" wire:key="{{$task['id']}}">
                                                            <div class="pt-2 rounded-lg">
                                                                <div class="bg-white rounded-lg shadow mb-3 p-2">
                                                                    <div class="text-gray-800">{{$task['name']}}</div>
                                                                    <div class="text-gray-500 text-xs">{{$task['date']}}</div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endif
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

