<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('User Management') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded shadow-sm" role="alert">
                    <p class="font-bold">Success!</p>
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            @if($errors->any())
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded shadow-sm" role="alert">
                    <p class="font-bold">Error!</p>
                    <ul class="list-disc pl-5">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Create User Form --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">Add New User</h3>
                    <form method="POST" action="{{ route('users.store') }}">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Full Name <span class="text-red-500">*</span></label>
                                <input type="text" name="name" value="{{ old('name') }}" required class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="e.g. Siti Aminah">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Email <span class="text-red-500">*</span></label>
                                <input type="email" name="email" value="{{ old('email') }}" required class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="email@example.com">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Role <span class="text-red-500">*</span></label>
                                <select name="role" required class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    <option value="staff" {{ old('role') == 'staff' ? 'selected' : '' }}>Staff (Kasir)</option>
                                    <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Password <span class="text-red-500">*</span></label>
                                <input type="password" name="password" required class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="Min. 8 characters">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Confirm Password <span class="text-red-500">*</span></label>
                                <input type="password" name="password_confirmation" required class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            </div>
                            <div class="flex items-end">
                                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg shadow transition">
                                    + Create User
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Users Table --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-bold text-gray-700 mb-4">All Users</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white border border-gray-200 rounded-lg">
                            <thead class="bg-gray-50 text-gray-600 text-xs uppercase font-semibold text-left">
                                <tr>
                                    <th class="py-3 px-6 border-b">Name</th>
                                    <th class="py-3 px-6 border-b">Email</th>
                                    <th class="py-3 px-6 border-b">Role</th>
                                    <th class="py-3 px-6 border-b text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="text-sm text-gray-700">
                                @forelse($users as $user)
                                    <tr class="hover:bg-gray-50 border-b border-gray-100 {{ $user->id === auth()->id() ? 'bg-blue-50' : '' }}" x-data="{ editing: false }">
                                        {{-- View Mode --}}
                                        <td class="py-3 px-6 font-medium text-gray-900" x-show="!editing">
                                            {{ $user->name }}
                                            @if($user->id === auth()->id())
                                                <span class="ml-1 text-xs text-blue-600 font-normal">(You)</span>
                                            @endif
                                        </td>
                                        <td class="py-3 px-6" x-show="!editing">{{ $user->email }}</td>
                                        <td class="py-3 px-6" x-show="!editing">
                                            <span class="px-2 py-1 rounded-full text-xs font-bold {{ $user->role === 'admin' ? 'bg-purple-100 text-purple-700' : 'bg-gray-100 text-gray-600' }}">
                                                {{ ucfirst($user->role) }}
                                            </span>
                                        </td>
                                        <td class="py-3 px-6 text-center" x-show="!editing">
                                            <button @click="editing = true" class="text-indigo-600 hover:text-indigo-900 mr-3 text-sm">Edit</button>
                                            @if($user->id !== auth()->id())
                                                <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="inline" onsubmit="return confirm('Delete {{ $user->name }}? This cannot be undone.');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900 text-sm">Delete</button>
                                                </form>
                                            @else
                                                <span class="text-gray-400 text-xs italic">Can't delete self</span>
                                            @endif
                                        </td>

                                        {{-- Inline Edit Mode --}}
                                        <td colspan="4" class="py-4 px-6" x-show="editing" x-cloak>
                                            <form method="POST" action="{{ route('users.update', $user->id) }}">
                                                @csrf
                                                @method('PUT')
                                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-3 items-end">
                                                    <div>
                                                        <label class="block text-xs font-medium text-gray-600 mb-1">Name</label>
                                                        <input type="text" name="name" value="{{ $user->name }}" required class="w-full rounded-md border-gray-300 shadow-sm text-sm focus:border-blue-500 focus:ring-blue-500">
                                                    </div>
                                                    <div>
                                                        <label class="block text-xs font-medium text-gray-600 mb-1">Email</label>
                                                        <input type="email" name="email" value="{{ $user->email }}" required class="w-full rounded-md border-gray-300 shadow-sm text-sm focus:border-blue-500 focus:ring-blue-500">
                                                    </div>
                                                    <div>
                                                        <label class="block text-xs font-medium text-gray-600 mb-1">Role</label>
                                                        <select name="role" required class="w-full rounded-md border-gray-300 shadow-sm text-sm focus:border-blue-500 focus:ring-blue-500">
                                                            <option value="staff" {{ $user->role === 'staff' ? 'selected' : '' }}>Staff</option>
                                                            <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Admin</option>
                                                        </select>
                                                    </div>
                                                    <div>
                                                        <label class="block text-xs font-medium text-gray-600 mb-1">New Password <span class="text-gray-400">(optional)</span></label>
                                                        <input type="password" name="password" class="w-full rounded-md border-gray-300 shadow-sm text-sm focus:border-blue-500 focus:ring-blue-500" placeholder="Leave blank to keep">
                                                    </div>
                                                    <div>
                                                        <label class="block text-xs font-medium text-gray-600 mb-1">Confirm Password</label>
                                                        <input type="password" name="password_confirmation" class="w-full rounded-md border-gray-300 shadow-sm text-sm focus:border-blue-500 focus:ring-blue-500" placeholder="Repeat if changing">
                                                    </div>
                                                    <div class="flex gap-2 col-span-full justify-end">
                                                        <button type="button" @click="editing = false" class="px-3 py-1.5 text-sm text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-100">Cancel</button>
                                                        <button type="submit" class="px-3 py-1.5 text-sm font-bold text-white bg-blue-600 hover:bg-blue-700 rounded-lg shadow">Save Changes</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="py-8 text-center text-gray-500">No users found.</td>
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
