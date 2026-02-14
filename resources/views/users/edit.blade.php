@extends("app")

@section("title", "Edit User")

@section("content")
<div class="bg-white overflow-hidden shadow rounded-lg">
    <div class="px-4 py-5 sm:px-6">
        <h3 class="text-lg leading-6 font-medium text-gray-900">Edit User: {{ ->username }}</h3>
    </div>
    <div class="border-t border-gray-200">
        <form method="POST" action="{{ route("users.update", ) }}" class="p-6">
            @csrf
            @method("PUT")

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Username *</label>
                    <input type="text" name="username" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 @error("username") border-red-300 @enderror" value="{{ old("username", ->username) }}" required>
                    @error("username")<p class="mt-1 text-sm text-red-600">{{  }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Email *</label>
                    <input type="email" name="email" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 @error("email") border-red-300 @enderror" value="{{ old("email", ->email) }}" required>
                    @error("email")<p class="mt-1 text-sm text-red-600">{{  }}</p>@enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">New Password (leave blank to keep current)</label>
                    <input type="password" name="password" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 @error("password") border-red-300 @enderror" minlength="8">
                    @error("password")<p class="mt-1 text-sm text-red-600">{{  }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Confirm New Password</label>
                    <input type="password" name="password_confirmation" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">First Name</label>
                    <input type="text" name="first_name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" value="{{ old("first_name", ->first_name) }}">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Last Name</label>
                    <input type="text" name="last_name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" value="{{ old("last_name", ->last_name) }}">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Phone</label>
                    <input type="text" name="phone" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" value="{{ old("phone", ->phone) }}">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Security Level *</label>
                    <select name="security_level" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 @error("security_level") border-red-300 @enderror" required>
                        <option value="1" {{ old("security_level", ->security_level) == 1 ? "selected" : "" }}>User (1)</option>
                        <option value="2" {{ old("security_level", ->security_level) == 2 ? "selected" : "" }}>Manager (2)</option>
                        <option value="3" {{ old("security_level", ->security_level) == 3 ? "selected" : "" }}>Admin (3)</option>
                    </select>
                    @error("security_level")<p class="mt-1 text-sm text-red-600">{{  }}</p>@enderror
                </div>
            </div>

            <div class="mt-4">
                <label class="flex items-center">
                    <input type="checkbox" name="is_active" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" id="is_active" value="1" {{ old("is_active", ->is_active) ? "checked" : "" }}>
                    <span class="ml-2 text-sm text-gray-700">Active Account</span>
                </label>
            </div>

            @if(->last_login)
            <div class="mt-4 text-gray-500 text-sm">
                Last login: {{ ->last_login->format("d.m.Y H:i") }}
            </div>
            @endif

            <div class="flex gap-2 mt-6">
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700">Update User</button>
                <a href="{{ route("users.index") }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
