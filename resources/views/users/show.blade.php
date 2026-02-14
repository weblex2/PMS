@extends("app")

@section("title", "User Details")

@section("content")
<div class="bg-white overflow-hidden shadow rounded-lg">
    <div class="px-4 py-5 sm:px-6 flex justify-between items-center">
        <h3 class="text-lg leading-6 font-medium text-gray-900">User: {{ ->username }}</h3>
        <div>
            @can("update", )
            <a href="{{ route("users.edit", ) }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 mr-2">Edit</a>
            @endcan
            <a href="{{ route("users.index") }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">Back</a>
        </div>
    </div>
    <div class="border-t border-gray-200">
        @if(session("success"))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">{{ session("success") }}</div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-6">
            <div>
                <table class="min-w-full">
                    <tr>
                        <td class="text-sm font-medium text-gray-500 w-40 py-2">ID</td>
                        <td class="text-sm text-gray-900">{{ ->id }}</td>
                    </tr>
                    <tr>
                        <td class="text-sm font-medium text-gray-500 py-2">Username</td>
                        <td class="text-sm text-gray-900">{{ ->username }}</td>
                    </tr>
                    <tr>
                        <td class="text-sm font-medium text-gray-500 py-2">Email</td>
                        <td class="text-sm text-gray-900">{{ ->email }}</td>
                    </tr>
                    <tr>
                        <td class="text-sm font-medium text-gray-500 py-2">Full Name</td>
                        <td class="text-sm text-gray-900">{{ ->name }}</td>
                    </tr>
                    <tr>
                        <td class="text-sm font-medium text-gray-500 py-2">Phone</td>
                        <td class="text-sm text-gray-900">{{ ->phone ?? "-" }}</td>
                    </tr>
                </table>
            </div>
            <div>
                <table class="min-w-full">
                    <tr>
                        <td class="text-sm font-medium text-gray-500 w-40 py-2">Security Level</td>
                        <td class="text-sm text-gray-900">
                            @if(->security_level >= 3)
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Admin</span>
                            @elseif(->security_level >= 2)
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Manager</span>
                            @else
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">User</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td class="text-sm font-medium text-gray-500 py-2">Status</td>
                        <td class="text-sm text-gray-900">
                            @if(->is_active)
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Active</span>
                            @else
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">Inactive</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td class="text-sm font-medium text-gray-500 py-2">Created</td>
                        <td class="text-sm text-gray-900">{{ ->created_at ? ->created_at->format("d.m.Y H:i") : "-" }}</td>
                    </tr>
                    <tr>
                        <td class="text-sm font-medium text-gray-500 py-2">Last Updated</td>
                        <td class="text-sm text-gray-900">{{ ->updated_at ? ->updated_at->format("d.m.Y H:i") : "-" }}</td>
                    </tr>
                    <tr>
                        <td class="text-sm font-medium text-gray-500 py-2">Last Login</td>
                        <td class="text-sm text-gray-900">{{ ->last_login ? ->last_login->format("d.m.Y H:i") : "Never" }}</td>
                    </tr>
                </table>
            </div>
        </div>

        @can("delete", )
        <div class="border-t border-gray-200 px-6 py-4">
            <form action="{{ route("users.destroy", ) }}" method="POST" onsubmit="return confirm("Are you sure you want to delete this user?")">
                @csrf
                @method("DELETE")
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-red-600 hover:bg-red-700">Delete User</button>
            </form>
        </div>
        @endcan
    </div>
</div>
@endsection
