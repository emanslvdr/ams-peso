<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl md:text-2xl leading-tight">
            Admin
        </h2>
    </x-slot>

    <div class="flex items-center justify-center py-10">
        <div class="max-w-md w-full p-6 bg-white rounded-lg shadow-md">
            <div class="text-center mb-8">
                <h2 class="text-2xl font-semibold text-gray-800">Create User</h2>
                <p class="mt-2 text-sm text-gray-600">Choose the type of user you want to create.</p>
            </div>
            <div class="flex flex-col space-y-4">
                <a href="{{ route('clients.index') }}" class="w-full px-6 py-3 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                    Client (HR)
                </a>
                <a href="{{route('users.index')}}" class="w-full px-6 py-3 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition-colors">
                    User (Applicant)
                </a>
            </div>
        </div>
    </div>
    
    
</x-admin-layout>
