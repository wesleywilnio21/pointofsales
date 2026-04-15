<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Supplier Management') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 rounded shadow-sm" role="alert">
                    <p class="font-bold">Success!</p>
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            <div class="mb-4 flex justify-end">
                <a href="{{ route('suppliers.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded shadow">
                    + Add New Supplier
                </a>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 border-b border-gray-200">
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white border border-gray-200 rounded-lg">
                            <thead class="bg-gray-50 text-gray-600 text-xs uppercase font-semibold text-left">
                                <tr>
                                    <th class="py-3 px-6 border-b">Name</th>
                                    <th class="py-3 px-6 border-b">Phone</th>
                                    <th class="py-3 px-6 border-b">Address</th>
                                    <th class="py-3 px-6 border-b text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="text-sm text-gray-700">
                                @forelse($suppliers as $supplier)
                                    <tr class="hover:bg-gray-50 border-b border-gray-100">
                                        <td class="py-3 px-6 font-medium text-gray-900">{{ $supplier->name }}</td>
                                        <td class="py-3 px-6">{{ $supplier->phone ?? '-' }}</td>
                                        <td class="py-3 px-6 truncate max-w-xs">{{ $supplier->address ?? '-' }}</td>
                                        <td class="py-3 px-6 text-center">
                                            <a href="{{ route('suppliers.edit', $supplier->id) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</a>
                                            <form action="{{ route('suppliers.destroy', $supplier->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this supplier?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="py-8 text-center text-gray-500">No suppliers found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
