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
                    {{ __("Logged in to BISU Event Calendar System") }}
                </div>
            </div>
        </div>
    </div>

        <div class="py-12 max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div id='calendar' class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-8"></div>
                </div>
            </div>
        </div>



        <!-- Modal for Viewing Event Details -->
<div id="eventModal" class="fixed inset-0 flex items-center justify-center bg-gray-800 bg-opacity-75 hidden z-50">
    <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-lg">
        <div class="flex justify-between items-center mb-4">
            <h5 class="text-2xl font-semibold" id="eventModalLabel">Event Details</h5>
            <button type="button" class="text-gray-500 hover:text-gray-700" id="closeModal">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <div class="mb-4">
            <h6 class="text-lg font-bold text-gray-700">Title</h6>
            <p class="text-gray-900" id="eventTitle">N/A</p>
        </div>
        <div class="mb-4">
            <h6 class="text-lg font-bold text-gray-700">Description</h6>
            <p class="text-gray-900" id="eventDescription">N/A</p>
        </div>
        <div class="mb-4">
            <h6 class="text-lg font-bold text-gray-700">Start</h6>
            <p class="text-gray-900" id="eventStart">N/A</p>
        </div>
        <div class="mb-4">
            <h6 class="text-lg font-bold text-gray-700">End</h6>
            <p class="text-gray-900" id="eventEnd">N/A</p>
        </div>
        <div class="mb-4">
            <h6 class="text-lg font-bold text-gray-700">All Day</h6>
            <p class="text-gray-900" id="allDay">N/A</p>
        </div>
        <div class="flex justify-end hidden">
            <button type="button" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" id="closeModal">Close</button>
        </div>
    </div>
</div>

</x-app-layout>
