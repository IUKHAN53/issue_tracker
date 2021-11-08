
<!-- Notification panel -->
<!-- Backdrop -->
<div
    x-transition:enter="transition duration-300 ease-in-out"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition duration-300 ease-in-out"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    x-show="isNotificationsPanelOpen"
    @click="isNotificationsPanelOpen = false"
    class="fixed inset-0 z-10 bg-primary-darker"
    style="opacity: 0.5"
    aria-hidden="true"
></div>
<!-- Panel -->
<section
    x-transition:enter="transition duration-300 ease-in-out transform sm:duration-500"
    x-transition:enter-start="-translate-x-full"
    x-transition:enter-end="translate-x-0"
    x-transition:leave="transition duration-300 ease-in-out transform sm:duration-500"
    x-transition:leave-start="translate-x-0"
    x-transition:leave-end="-translate-x-full"
    x-ref="notificationsPanel"
    x-show="isNotificationsPanelOpen"
    @keydown.escape="isNotificationsPanelOpen = false"
    tabindex="-1"
    aria-labelledby="notificationPanelLabel"
    class="fixed inset-y-0 z-20 w-full max-w-xs bg-white dark:bg-darker dark:text-light sm:max-w-md focus:outline-none"
>
    <div class="absolute right-0 p-2 transform translate-x-full">
        <!-- Close button -->
        <button
            @click="isNotificationsPanelOpen = false"
            class="p-2 text-white rounded-md focus:outline-none focus:ring"
        >
            <svg
                class="w-5 h-5"
                xmlns="http://www.w3.org/2000/svg"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
            >
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>
</section>
