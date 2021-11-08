<div>
    <div class="fixed w-full z-50 flex inset-0 items-start justify-center pointer-events-none md:mt-5" x-data="{
      message: '',
      showFlashMessage(event) {
        this.message = event.detail.message;
        setTimeout(() => this.message = '', 3000)
      }
    }">
        <template x-on:flash.window="showFlashMessage(event)"></template>
        <template x-if="message">
            <div role="alert" x-transition:enter="transition ease-out duration-300 transform"
                 x-transition:enter-start="-translate-y-5 opacity-0" x-transition:enter-end="translate-y-0 opacity-100"
                 x-transition:leave="transition ease-in duration-100 transform" x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0 -translate-y-5"
                 class="w-full px-4 py-4 w-full md:max-w-sm bg-gray-900 md:rounded-lg shadow-lg">
                <div class="flex items-center">
                    <div class="flex-shrink-0 mr-3">
                        <svg class="h-6 w-6 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                  d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                  clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="text-gray-200 text-base" x-text="message"></div>
                </div>
            </div>
        </template>
    </div>

    <div x-data="app()" x-init="getTasks()" x-cloak class="flex flex-col min-h-screen border-t-8"
         :class="`border-${colorSelected.value}-700`">
        <div class="flex-1">
            <!-- Header -->
            <div class="bg-cover bg-center bg-no-repeat" :class="`bg-${colorSelected.value}-900`"
                 :style="`background-image: url(${bannerImage})`">
                <div class="container mx-auto px-4 pt-4 md:pt-10 pb-40"></div>
            </div>
            <!-- /Header -->
            <div class="container mx-auto px-4 py-4 -mt-40">
                <!-- Main Page -->
                <div x-show.immediate="localStorage.getItem('TG-username') && showSettingsPage == false">
                    <div x-show.transition="localStorage.getItem('TG-username') && showSettingsPage == false">
                        <!-- Kanban Board -->
                        <div class="py-4 md:py-8">
                            <div class="flex -mx-4 block overflow-x-auto pb-2">
                                <template x-for="board in boards" :key="board">
                                    <div class="w-1/2 md:w-1/4 px-4 flex-shrink-0">
                                        <div
                                            class="bg-gray-100 pb-4 rounded-lg shadow overflow-y-auto overflow-x-hidden border-t-8"
                                            style="min-height: 100px" :class="{
                        'border-orange-600': board === boards[0],
                        'border-yellow-600': board === boards[1],
                        'border-blue-600': board === boards[2],
                        'border-green-600': board === boards[3],
                      }">
                                            <div
                                                class="flex justify-between items-center px-4 py-2 bg-gray-100 sticky top-0">
                                                <h2 x-text="board" class="font-medium text-gray-800"></h2>
                                            </div>

                                            <div class="px-4">
                                                <div @dragover="onDragOver(event)" @drop="onDrop(event, board)"
                                                     @dragenter="onDragEnter(event)" @dragleave="onDragLeave(event)"
                                                     class="pt-2 pb-20 rounded-lg">
                                                    <template
                                                        x-for="(t, taskIndex) in tasks.filter(t => t.boardName === board)"
                                                        :key="taskIndex">
                                                        <div :id="t.uuid">

                                                            <div x-show="t.edit == false">
                                                                <div x-show="t.edit == false"
                                                                     class="bg-white rounded-lg shadow mb-3 p-2"
                                                                     draggable="true"
                                                                     @dragstart="onDragStart(event, t.uuid)"
                                                                     @dblclick="t.edit = true; setTimeout(() => $refs[t.uuid].focus())">
                                                                    <div x-text="t.name" class="text-gray-800"></div>
                                                                    <div x-text="formatDateDisplay(t.date)"
                                                                         class="text-gray-500 text-xs"></div>
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
                    </div>
                </div>
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
                task: @this.tasks,
                editTask: {},
                tasks: [],
                formatDateDisplay(date) {
                    if (this.dateDisplay === 'toDateString') return new Date(date).toDateString();
                    if (this.dateDisplay === 'toLocaleDateString') return new Date(date).toLocaleDateString('en-GB');
                    return new Date().toLocaleDateString('en-GB');
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
                generateUUID() {
                    return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function (c) {
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
            }
        }
    </script>
@endpush

