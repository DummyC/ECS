<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">User Management</h2>
    </x-slot>

    <div class="py-6 max-w-4xl mx-auto">
        <form method="GET" class="mb-4">
            <input type="text" name="search" value="{{ $search }}" placeholder="Search users..." class="border rounded px-2 py-1">
            <button type="submit" class="bg-blue-500 text-white px-3 py-1 rounded">Search</button>
        </form>
        <div class="bg-white shadow rounded">
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr>
                        <th class="px-4 py-2 text-left">Name</th>
                        <th class="px-4 py-2 text-left">Email</th>
                        <th class="px-4 py-2 text-left">Admin</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                    <tr>
                        <td class="px-4 py-2">{{ $user->name }}</td>
                        <td class="px-4 py-2">{{ $user->email }}</td>
                        <td class="px-4 py-2">
                            <input type="checkbox" {{ $user->is_admin ? 'checked' : '' }}
                                onclick="toggleAdmin({{ $user->id }}, this.checked)">
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="p-4">
                {{ $users->withQueryString()->links() }}
            </div>
        </div>
    </div>

    <script>
        function toggleAdmin(userId, isChecked) {
            fetch(`/admin/users/${userId}/toggle-admin`, {
                method: 'PATCH',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            })
            .then(res => res.json())
            .then(data => {
                if (!data.success) {
                    alert('Failed to update admin status.');
                }
            });
        }
    </script>
</x-app-layout>
