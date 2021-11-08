<div class="antialiased sans-serif bg-gray-300">
<!-- Alert Box -->
<div class="fixed w-full z-50 flex inset-0 items-start justify-center pointer-events-none md:mt-5" x-data="{
      message: '',
      showFlashMessage(event) {
        this.message = event.detail.message;
        setTimeout(() => this.message = '', 3000)
      }
    }">
    <template x-on:flash.window="showFlashMessage(event)"></template>
    <template x-if="message">
        <div role="alert" x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="-translate-y-5 opacity-0" x-transition:enter-end="translate-y-0 opacity-100" x-transition:leave="transition ease-in duration-100 transform" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0 -translate-y-5" class="w-full px-4 py-4 w-full md:max-w-sm bg-gray-900 md:rounded-lg shadow-lg">
            <div class="flex items-center">
                <div class="flex-shrink-0 mr-3">
                    <svg class="h-6 w-6 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" /></svg>
                </div>
                <div class="text-gray-200 text-base" x-text="message"></div>
            </div>
        </div>
    </template>
</div>
<!-- /Alert Box -->

<div x-data="app()" x-init="loadTasksFromDB();getTasks();" x-cloak class="flex flex-col min-h-screen border-t-8" :class="`border-${colorSelected.value}-700`">
    <div class="flex-1">

        <!-- Header -->
        <div class="bg-cover bg-center bg-no-repeat" :class="`bg-${colorSelected.value}-900`" :style="`background-image: url(${bannerImage})`">
            <div class="container mx-auto px-4 pt-4 md:pt-10 pb-40"></div>
        </div>
        <!-- /Header -->

        <div class="container mx-auto px-4 py-4 -mt-40">
            <!-- Settings Page -->
            <div x-show.immediate="showSettingsPage == true">
                <div x-show.transition="showSettingsPage == true">
                    <div class="mb-8">
                        <a href="#" @click.prevent="showSettingsPage = false" class="rounded-lg text-sm px-3 py-2 inline-flex" :class="`text-${colorSelected.value}-500 bg-${colorSelected.value}-800 hover:bg-${colorSelected.value}-700`">&larr; Go Back</a>
                    </div>
                    <div class="p-6 bg-white rounded-lg shadow-md md:max-w-4xl" style="min-height: 150px">
                        <h2 class="font-bold text-gray-800 mb-3 text-2xl">Settings</h2>
                        <div class="mb-5">
                            <label class="text-gray-800 block mb-1 font-bold text-sm">Name</label>
                            <input class="bg-gray-200 appearance-none border-2 border-gray-200 rounded-lg w-full md:w-64 py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-blue-500" type="text" x-model="username" placeholder="Enter your name">
                        </div>

                        <div class="mb-5">
                            <div class="flex items-center">
                                <div>
                                    <label for="colorSelected" class="text-gray-800 block font-bold mb-1 text-sm">Select a theme</label>

                                    <div class="px-1">
                                        <div class="flex flex-wrap -mx-2">
                                            <template x-for="(color, index) in colors" :key="index">
                                                <div class="px-2">
                                                    <template x-if="colorSelected.value === color.value">
                                                        <div class="w-8 h-8 inline-flex rounded-full cursor-pointer border-4 border-white" :style="`background: ${color.label}; box-shadow: 0 0 0 2px rgba(0, 0, 0, 0.2);`"></div>
                                                    </template>

                                                    <template x-if="colorSelected.value != color.value">
                                                        <div @click="colorSelected = color" @keydown.enter="colorSelected = color" role="checkbox" tabindex="0" :aria-checked="colorSelected" class="w-8 h-8 inline-flex rounded-full cursor-pointer border-4 border-white focus:outline-none focus:shadow-outline" :style="`background: ${color.label};`"></div>
                                                    </template>
                                                </div>
                                            </template>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-5">
                            <label class="text-gray-800 block mb-1 font-bold text-sm">Banner image <small class="text-gray-500 text-xs">(optional)</small></label>
                            <input class="bg-gray-200 appearance-none border-2 border-gray-200 rounded-lg w-full md:w-1/2 py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-blue-500" type="url" x-model="bannerImage" placeholder="eg. https://picsum.photos/1200/400?random=2">
                        </div>

                        <div class="mb-5">
                            <label class="text-gray-800 block mb-1 font-bold text-sm">Date format display</label>

                            <div class="flex">
                                <label class="flex justify-start items-center text-truncate rounded-lg bg-gray-200 pl-4 pr-6 py-2 shadow-xs mr-4">
                                    <div class="mr-3" :class="`text-${colorSelected.value}-600`">
                                        <input type="radio" x-model="dateDisplay" value="toDateString" class="form-radio focus:outline-none focus:shadow-outline" />
                                    </div>
                                    <div class="select-none text-gray-700">Thu May 28 2020</div>
                                </label>

                                <label class="flex justify-start items-center text-truncate rounded-lg bg-gray-200 pl-4 pr-6 py-2 shadow-xs mr-4">
                                    <div class="mr-3" :class="`text-${colorSelected.value}-600`">
                                        <input type="radio" x-model="dateDisplay" value="toLocaleDateString" class="form-radio focus:outline-none focus:shadow-outline" />
                                    </div>
                                    <div class="select-none text-gray-700">28/05/2020</div>
                                </label>
                            </div>
                        </div>

                        <div class="mt-8">
                            <button type="button" class="bg-white hover:bg-gray-100 text-gray-700 font-semibold py-2 px-4 border border-gray-300 rounded-lg shadow-xs mr-2" @click="showSettingsPage = false">
                                Cancel
                            </button>
                            <button type="button" @click="saveSettings" class="text-white font-semibold py-2 px-4 border border-transparent rounded-lg shadow-xs" :class="`bg-${colorSelected.value}-700 hover:bg-${colorSelected.value}-800`">
                                Save Settings
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Page -->
            <div x-show.immediate="localStorage.getItem('TG-username') && showSettingsPage == false">
                <div x-show.transition="localStorage.getItem('TG-username') && showSettingsPage == false">
                    <!-- Greetings -->
                    <div class="flex justify-between items-center mb-2">
                        <div>
                            <h1 class="text-xl md:text-2xl text-gray-300 font-semibold" x-text="greetText()"></h1>
                            <div x-text="formatDateDisplay(new Date())" class="text-sm" :class="`text-${colorSelected.value}-400`"></div>
                        </div>
                        <div>
                            <a @click.prevent="showSettingsPage = !showSettingsPage" href="#" class="rounded-lg px-3 py-2 font-medium inline-flex items-center" :class="`text-${colorSelected.value}-500 bg-${colorSelected.value}-800 hover:bg-${colorSelected.value}-700`">
                                <svg class="w-5 h-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                Settings</a>
                        </div>
                    </div>
                    <!-- /Greetings -->

                    <!-- Kanban Board -->
                    <div class="py-4 md:py-8">
                        <div class="flex -mx-4 block overflow-x-auto pb-2">
                            <template x-for="board in boards" :key="board">
                                <div class="w-1/2 md:w-1/4 px-4 flex-shrink-0">
                                    <div class="bg-gray-100 pb-4 rounded-lg shadow overflow-y-auto overflow-x-hidden border-t-8" style="min-height: 100px" :class="{
                        'border-orange-600': board === boards[0],
                        'border-yellow-600': board === boards[1],
                        'border-blue-600': board === boards[2],
                        'border-green-600': board === boards[3],
                      }">
                                        <div class="flex justify-between items-center px-4 py-2 bg-gray-100 sticky top-0">
                                            <h2 x-text="board" class="font-medium text-gray-800"></h2>
                                            <a @click.prevent="showModal(board)" href="#" class="inline-flex items-center text-sm font-medium" :class="`text-${colorSelected.value}-500 hover:text-${colorSelected.value}-600`">
                                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                                </svg>
                                                Add Task
                                            </a>
                                        </div>

                                        <div class="px-4">
                                            <div @dragover="onDragOver(event)" @drop="onDrop(event, board)" @dragenter="onDragEnter(event)" @dragleave="onDragLeave(event)" class="pt-2 pb-20 rounded-lg">
                                                <template x-for="(t, taskIndex) in tasks.filter(t => t.boardName === board)" :key="taskIndex">
                                                    <div :id="t.uuid">

                                                        <div>
                                                            <div class="bg-white rounded-lg shadow mb-3 p-2" draggable="true" @dragstart="onDragStart(event, t.uuid)" @dblclick="t.edit = true; setTimeout(() => $refs[t.uuid].focus())">
                                                                <div x-text="t.name" class="text-gray-800"></div>
                                                                <div x-text="formatDateDisplay(t.date)" class="text-gray-500 text-xs"></div>
                                                            </div>
                                                        </div>

                                                    </div>

                                                </template>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                    <!-- /Kanban Board -->
                </div>
            </div>
            <!-- /Main Page -->

        </div>

    </div>

</div>
</div>
@push('scripts')
    <script>
        function app() {
            return {
                showSettingsPage: false,
                openModal: false,
                username: '',
                bannerImage: '',
                colors: [{
                    label: '#3182ce',
                    value: 'blue'
                },
                    {
                        label: '#38a169',
                        value: 'green'
                    },
                    {
                        label: '#805ad5',
                        value: 'purple'
                    },
                    {
                        label: '#e53e3e',
                        value: 'red'
                    },
                    {
                        label: '#dd6b20',
                        value: 'orange'
                    },
                    {
                        label: '#5a67d8',
                        value: 'indigo'
                    },
                    {
                        label: '#319795',
                        value: 'teal'
                    },
                    {
                        label: '#718096',
                        value: 'gray'
                    },
                    {
                        label: '#d69e2e',
                        value: 'yellow'
                    }
                ],
                colorSelected: {
                    label: '#3182ce',
                    value: 'blue'
                },
                dateDisplay: 'toDateString',
                boards: @this.boards,
                tasks: [],
                backend_tasks: [],
                formatDateDisplay(date) {
                    if (this.dateDisplay === 'toDateString') return new Date(date).toDateString();
                    if (this.dateDisplay === 'toLocaleDateString') return new Date(date).toLocaleDateString('en-GB');
                    return new Date().toLocaleDateString('en-GB');
                },
                loadTasksFromDB() {
                    localStorage.removeItem("TG-tasks");
                    JSON.parse(@this.tasks).forEach((task)=>{
                        const taskData = {
                            uuid: this.generateUUID(),
                            id: task.id,
                            name: task.name,
                            status: task.status,
                            boardName: task.boardName,
                            date: task.date
                        };
                        this.saveDataToLocalStorage(taskData, 'TG-tasks');
                    });
                },
                getTasks() {
                    // Get Default Settings
                    const themeFromLocalStorage = JSON.parse(localStorage.getItem('TG-theme'));
                    this.dateDisplay = localStorage.getItem('TG-dateDisplay') || 'toLocaleDateString';
                    this.username = localStorage.getItem('TG-username') || '';
                    this.bannerImage = localStorage.getItem('TG-bannerImage') || '';
                    this.colorSelected = themeFromLocalStorage || {
                        label: '#3182ce',
                        value: 'blue'
                    };
                    if (localStorage.getItem('TG-tasks')) {
                        const tasksFromLocalStorage = JSON.parse(localStorage.getItem('TG-tasks'));
                        this.tasks = tasksFromLocalStorage.map(t => {
                            return {
                                id: t.id,
                                uuid: t.uuid,
                                name: t.name,
                                status: t.status,
                                boardName: t.boardName,
                                date: t.date,
                                edit: false
                            }
                        });
                    } else {
                        this.tasks = [];
                    }
                },
                saveSettings() {
                    const theme = JSON.stringify(this.colorSelected);
                    localStorage.setItem('TG-username', this.username);
                    localStorage.setItem('TG-accessKey', this.accessKey);
                    localStorage.setItem('TG-theme', theme);
                    localStorage.setItem('TG-bannerImage', this.bannerImage);
                    localStorage.setItem('TG-dateDisplay', this.dateDisplay);
                    this.dispatchCustomEvents('flash', 'Settings updated');
                    this.showSettingsPage = false;
                },
                onDragStart(event, uuid) {
                    event.dataTransfer.setData('text/plain', uuid);
                    event.target.classList.add('opacity-5');
                },
                onDragOver(event) {
                    event.preventDefault();
                    return false;
                },
                onDragEnter(event) {
                    event.target.classList.add('bg-gray-200');
                },
                onDragLeave(event) {
                    event.target.classList.remove('bg-gray-200');
                },
                onDrop(event, boardName) {
                    event.stopPropagation(); // Stops some browsers from redirecting.
                    event.preventDefault();
                    event.target.classList.remove('bg-gray-200');
                    // console.log('Dropped', this);
                    const id = event.dataTransfer.getData('text');
                    const draggableElement = document.getElementById(id);
                    const dropzone = event.target;
                    dropzone.appendChild(draggableElement);
                    // Update
                    // Get the existing data
                    let existing = JSON.parse(localStorage.getItem('TG-tasks'));
                    let taskIndex = existing.findIndex(t => t.uuid === id);
                    // Add new data to localStorage Array
                    existing[taskIndex].boardName = boardName;
                    existing[taskIndex].date = new Date();
                    // Save back to localStorage
                    localStorage.setItem('TG-tasks', JSON.stringify(existing));
                    // Get Updated Tasks
                    this.getTasks();
                    // Show flash message
                    this.dispatchCustomEvents('flash', 'Task moved to ' + boardName);
                    event.dataTransfer.clearData();
                },
                saveDataToLocalStorage(data, keyName) {
                    var a = [];
                    // Parse the serialized data back into an aray of objects
                    a = JSON.parse(localStorage.getItem(keyName)) || [];
                    // Push the new data (whether it be an object or anything else) onto the array
                    a.push(data);
                    // Re-serialize the array back into a string and store it in localStorage
                    localStorage.setItem(keyName, JSON.stringify(a));
                },
                generateUUID() {
                    return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
                        var r = Math.random() * 16 | 0,
                            v = c == 'x' ? r : (r & 0x3 | 0x8);
                        return v.toString(16);
                    });
                },
                dispatchCustomEvents(eventName, message) {
                    let customEvent = new CustomEvent(eventName, {
                        detail: {
                            message: message
                        }
                    });
                    window.dispatchEvent(customEvent);
                },
                greetText() {
                    var d = new Date();
                    var time = d.getHours();
                    // From: https://1loc.dev/ (Uppercase the first character of each word in a string)
                    const uppercaseWords = str => str.split(' ').map(w => `${w.charAt(0).toUpperCase()}${w.slice(1)}`).join(' ');
                    let name = localStorage.getItem('TG-username') || '';
                    if (time < 12) {
                        return "Good morning, " + uppercaseWords(name);
                    } else if (time < 17) {
                        return "Good afternoon, " + uppercaseWords(name);
                    } else {
                        return "Good evening, " + uppercaseWords(name);
                    }
                },
            }
        }
    </script>
@endpush
