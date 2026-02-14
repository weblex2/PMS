<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __("Dashboard") }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">
                    <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-200">
                        PMS Dashboard
                    </h1>
                    
                    <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-4">
                        <a href="/users" class="block p-4 bg-blue-500 text-white rounded-lg hover:bg-blue-600">
                            <div class="text-xl font-bold">Benutzer</div>
                            <div class="text-sm">User Management</div>
                        </a>
                        <a href="/reservations" class="block p-4 bg-green-500 text-white rounded-lg hover:bg-green-600">
                            <div class="text-xl font-bold">Reservierungen</div>
                            <div class="text-sm">Booking Management</div>
                        </a>
                        <a href="/rooms" class="block p-4 bg-purple-500 text-white rounded-lg hover:bg-purple-600">
                            <div class="text-xl font-bold">Räume</div>
                            <div class="text-sm">Room Management</div>
                        </a>
                    </div>
                    
                    <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-4">
                        <a href="/guests" class="block p-4 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600">
                            <div class="text-xl font-bold">Gäste</div>
                            <div class="text-sm">Guest Management</div>
                        </a>
                        <a href="/invoices" class="block p-4 bg-red-500 text-white rounded-lg hover:bg-red-600">
                            <div class="text-xl font-bold">Rechnungen</div>
                            <div class="text-sm">Invoice Management</div>
                        </a>
                        <a href="/reports" class="block p-4 bg-indigo-500 text-white rounded-lg hover:bg-indigo-600">
                            <div class="text-xl font-bold">Berichte</div>
                            <div class="text-sm">Reports</div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
