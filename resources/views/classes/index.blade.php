<x-layouts.app title="Classes">
    <div class="admin-record-header mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <p class="page-kicker">Academic</p>
            <h1 class="page-title">Classes</h1>
            <p class="page-subtitle">Manage class names and academic groupings.</p>
        </div>
        <a class="btn-primary" href="{{ route('admin.classes.create') }}">Add class</a>
    </div>

    <div class="table-shell admin-record-table">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($classes as $class)
                    <tr>
                        <td class="font-medium">{{ $class->name }}</td>
                        <td class="admin-actions">
                            <a class="admin-action-btn edit" href="{{ route('admin.classes.edit', $class) }}">Edit</a>
                            <form class="inline" method="POST" action="{{ route('admin.classes.destroy', $class) }}">
                                @csrf
                                @method('DELETE')
                                <button class="admin-action-btn delete" type="submit">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="2" class="py-8 text-center text-zinc-500">No classes yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-layouts.app>
