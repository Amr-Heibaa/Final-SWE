<x-app-layout>
    <x-slot name="header">
        <h2 class="dash-title text-xl font-semibold leading-tight">
            {{ __('Admins') }}
        </h2>
    </x-slot>

    <div class="py-12 dashboard-wrap">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="dash-card overflow-hidden">
                <div class="p-6">

                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-semibold">Manage Admins</h3>

                        <a href="{{ route('admin.create') }}"
                           class="inline-flex items-center px-4 py-2 btn-brand text-xs uppercase tracking-widest">
                            Create Admin
                        </a>
                    </div>

                    @if (!isset($admins) || $admins->count() === 0)
                        <p class="text-white/70">No admins found.</p>
                    @else
                        <div class="dash-table-wrap overflow-x-auto">
                            <table class="dash-table min-w-full">
                                <thead>
                                    <tr>
                                        <th class="text-left px-4 py-3 text-sm font-semibold">#</th>
                                        <th class="text-left px-4 py-3 text-sm font-semibold">Name</th>
                                        <th class="text-left px-4 py-3 text-sm font-semibold">Email</th>
                                        <th class="text-left px-4 py-3 text-sm font-semibold">Phone</th>
                                        <th class="text-left px-4 py-3 text-sm font-semibold">Role</th>
                                        <th class="text-right px-4 py-3 text-sm font-semibold">Actions</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach ($admins as $admin)
                                        @php
                                            $role = $admin->role?->value ?? (string)($admin->role ?? 'admin');
                                        @endphp

                                        <tr>
                                            <td class="px-4 py-3 text-sm">#{{ $admin->id }}</td>
                                            <td class="px-4 py-3 text-sm">{{ $admin->name }}</td>
                                            <td class="px-4 py-3 text-sm">{{ $admin->email }}</td>
                                            <td class="px-4 py-3 text-sm">{{ $admin->phone ?? '—' }}</td>
                                            <td class="px-4 py-3 text-sm">{{ ucfirst($role) }}</td>

                                            <td class="px-4 py-3 text-sm text-right">
                                                <div class="inline-flex items-center gap-2">

                                                    <a href="{{ route('admin.edit', $admin) }}"
                                                       class="inline-flex items-center justify-center px-3 py-2 btn-brand text-xs">
                                                        Edit
                                                    </a>

                                                    <form method="POST" action="{{ route('admin.destroy', $admin) }}" class="inline-flex">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                                onclick="return confirm('Delete this admin?')"
                                                                class="inline-flex items-center justify-center px-3 py-2 btn-danger text-xs">
                                                            Delete
                                                        </button>
                                                    </form>

                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        @if(method_exists($admins, 'links'))
                            <div class="mt-6">
                                {{ $admins->links() }}
                            </div>
                        @endif
                    @endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
