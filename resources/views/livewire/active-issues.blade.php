<div>
    @if($invalid_token)
        <span class="text-lg">Provided Access Token is invalid please update in user settings</span>
    @else
        @foreach($tasks as $task)
            <div class="rounded-md bg-primary-100 dark:bg-primary-light px-4 py-4 m-4 shadow-md ">
                <div class="flex flex-col justify-start">
                    <div class="flex justify-between items-center">
                        <div class="text-lg font-semibold text-bookmark-blue flex space-x-1 items-center mb-2">
                            <span class="text-primary-500 dark:text-primary">{{$task['name']}}</span>
                        </div>
                        <span
                            class="rounded-full uppercase bg-white dark:bg-primary text-sm px-3 py-1 shadow-xl">{{$task['boardName']}}</span>
                    </div>
                    <div class="text-sm text-gray-700 flex space-x-1 items-center">
                        <span>{{$task['description']}}</span>
                    </div>
                    <div class="text-sm text-gray-500 flex space-x-1 items-center">
                        <span>Created {{\Carbon\Carbon::parse($task['date'])->diffForHumans()}}</span><span> & Updated {{\Carbon\Carbon::parse($task['updated'])->diffForHumans()}}</span>
                    </div>
{{--                    <div>--}}
{{--                        <div class="mt-5">--}}
{{--                            <button--}}
{{--                                class="mr-2 my-1 uppercase tracking-wider px-2 text-primary-dark border-primary-dark hover:text-white  hover:bg-primary-dark  border text-sm font-semibold rounded py-1 transition transform duration-500 cursor-pointer">--}}
{{--                                Apply--}}
{{--                            </button>--}}
{{--                        </div>--}}
{{--                    </div>--}}
                </div>
            </div>
        @endforeach
    @endif
</div>
