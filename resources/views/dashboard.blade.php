<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>


        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 py-12">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div id='calendar' class="max-w-full mx-auto px-4 sm:px-6 lg:px-8 mt-8"></div>
                </div>
            </div>
        </div>



        <!-- Modal for Creating/Editing Events -->
        <div id="eventModal" class="fixed inset-0 flex items-center justify-center bg-gray-800 bg-opacity-75 hidden z-50">
            <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-md">
                <h5 class="text-xl font-bold mb-4" id="eventModalLabel">Event Details</h5>
                <form id="eventForm">
                    <input type="hidden" id="eventId" name="id">
                    <div class="mb-4">
                        <label for="eventTitle" class="block text-gray-700 font-bold mb-2">Title</label>
                        <input type="text" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="eventTitle" name="title" required>
                    </div>
                    <div class="mb-4">
                        <label for="eventDescription" class="block text-gray-700 font-bold mb-2">Description</label>
                        <textarea class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="eventDescription" name="description"></textarea>
                    </div>
                    <div class="mb-4">
                        <label for="eventStart" class="block text-gray-700 font-bold mb-2">Start</label>
                        <input type="datetime-local" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="eventStart" name="start" required>
                    </div>
                    <div class="mb-4">
                        <label for="eventEnd" class="block text-gray-700 font-bold mb-2">End</label>
                        <input type="datetime-local" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="eventEnd" name="end">
                    </div>
                    <div class="mb-4">
                        <label class="inline-flex items-center">
                            <input type="checkbox" class="form-checkbox h-5 w-5 text-blue-600" id="allDay" name="allDay">
                            <span class="ml-2 text-gray-700">All Day</span>
                        </label>
                    </div>
                    <div class="flex justify-end">
                        <button type="button" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded mr-2" id="deleteEvent">Delete</button>
                        <button type="button" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded mr-2" id="closeModal">Close</button>
                        <button type="button" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" id="saveEvent">Save changes</button>
                    </div>
                </form>
            </div>
        </div>

</x-app-layout>
