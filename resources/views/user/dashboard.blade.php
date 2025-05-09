<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Calendar') }}
        </h2>
    </x-slot>

    <div class="py-12 hidden">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{ __('Logged in to BISU Event Calendar System') }}
                </div>
            </div>
        </div>
    </div>

    <div class="py-12 max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <!-- Add this notification section -->
                <div id="notificationPrompt" class="mb-6 p-4 bg-blue-50 rounded-lg flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-500" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                        <p class="text-blue-700">Get notified about upcoming events! Enable push notifications to stay
                            updated.</p>
                    </div>
                    <button onclick="askForNotificationPermission()"
                        class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md transition-colors duration-200">
                        Enable Notifications
                    </button>
                </div>

                <div id='calendar' class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-8"></div>
            </div>
        </div>
    </div>



    <!-- Modal for Viewing Event Details -->
    <div id="eventModal" class="fixed inset-0 flex items-center justify-center bg-gray-800 bg-opacity-75 hidden z-50">
        <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-4xl">
            <div class="flex justify-between items-center mb-4">
                <h5 class="text-2xl font-semibold" id="eventModalLabel"></h5>
                <button type="button" class="text-gray-500 hover:text-gray-700" id="closeModal">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <div class="space-y-4">
                <div class="text-gray-600">
                    <span id="eventDateTime">
                        <span id="eventStart"></span>
                        <span id="eventDateSeparator" class="hidden"> - </span>
                        <span id="eventEnd"></span>
                    </span>
                </div>
                <div class="text-gray-800">
                    <p id="eventDescription" class="whitespace-pre-wrap"></p>
                </div>
            </div>
        </div>
    </div>

    <script>
        navigator.serviceWorker.register("{{ URL::asset('service-worker.js') }}");

        function askForNotificationPermission() {
            if (Notification.permission === "default") {
                Notification.requestPermission().then((permission) => {
                    if (permission === "granted") {
                        document.getElementById('notificationPrompt').classList.add('hidden');
                        navigator.serviceWorker.ready.then((registration) => {
                            registration.pushManager.subscribe({
                                userVisibleOnly: true,
                                applicationServerKey: "{{ config('webpush.vapid.public_key') }}"
                            }).then((subscription) => {
                                saveSub(subscription.toJSON());
                                console.log("Subscribed to push notifications:", subscription);
                            }).catch((error) => {
                                console.error("Failed to subscribe to push notifications:", error);
                            });
                        });
                        console.log("Notification permission granted.");
                    } else {
                        console.log("Notification permission denied.");
                    }
                });
            }
        }

        document.addEventListener("DOMContentLoaded", function() {
            const notificationPrompt = document.getElementById('notificationPrompt');

            // Hide prompt if notifications are already enabled
            if (Notification.permission === "granted") {
                notificationPrompt.classList.add('hidden');
            }

            const urlParams = new URLSearchParams(window.location.search);
            const eventId = urlParams.get('event');
            if (eventId) {
                // Wait for calendar to load, then open modal for this event
                // You may need to adjust this depending on how you fetch events
                setTimeout(function() {
                    // Find the event in FullCalendar and trigger the modal
                    const event = calendar.getEvents().find(e => e.id == eventId);
                    if (event) {
                        // Simulate event click to open modal
                        // You may need to call your modal-opening function directly
                        // Example:
                        openEventModal(event);
                    }
                }, 500); // Adjust delay as needed
            }
        });

        function saveSub(subscription) {
            console.log(subscription);
            fetch("{{ route('save.subscription') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    body: JSON.stringify(subscription)
                })
                .then(response => response.json())
                .then(data => console.log("Subscription saved:", data))
                // .then(subscription => console.log("Subscription saved:", subscription))
                .catch(error => console.error("Error saving subscription:", error));
        }

        function sendNotification() {
            fetch("{{ route('send.notification') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({
                        title: "Test Notification",
                        body: "This is a test notification from the server.",
                        url: "{{ url('/') }}/calendar",
                    })
                })
                .then(response => response.json())
                .then(data => console.log("Notification sent:", data))
                .catch(error => console.error("Error sending notification:", error));
        }

        function openEventModal(event) {
            document.getElementById('eventModalLabel').innerText = event.title;
            document.getElementById('eventDescription').innerText = event.extendedProps.description || '';

            const startDate = formatDateTime(info.event.start);
            const endDate = formatDateTime(info.event.end);

            document.getElementById('eventStart').innerText = startDate;
            document.getElementById('eventEnd').innerText = endDate;
            document.getElementById('eventDateSeparator').classList.toggle('hidden', info.event.allDay);
            document.getElementById('eventEnd').classList.toggle('hidden', info.event.allDay);

            document.getElementById('eventModal').classList.remove('hidden');
        }

        function formatDateTime(date) {
            if (!date) return 'N/A';
            return new Date(date).toLocaleDateString('en-US', {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric',
                hour: 'numeric',
                minute: 'numeric'
            });
        }
    </script>

</x-app-layout>
