<x-layouts.app title="Subjects">
    <div class="admin-record-header mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <p class="page-kicker">Academic</p>
            <h1 class="page-title">Subjects</h1>
            <p class="page-subtitle">Manage academic subjects and courses.</p>
        </div>
        <a class="btn-primary" href="{{ route('admin.subjects.create') }}">Add subject</a>
    </div>

    <div class="table-shell admin-record-table">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Code</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($subjects as $subject)
                    <tr>
                        <td class="font-medium">{{ $subject->name }}</td>
                        <td class="text-zinc-500">{{ $subject->code ?? '-' }}</td>
                        <td class="admin-actions">
                            <a class="admin-action-btn edit" href="{{ route('admin.subjects.edit', $subject) }}">Edit</a>
                            <form class="inline" method="POST" action="{{ route('admin.subjects.destroy', $subject) }}">
                                @csrf
                                @method('DELETE')
                                <button class="admin-action-btn delete" type="submit">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="3" class="py-8 text-center text-zinc-500">No subjects yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-layouts.app>
