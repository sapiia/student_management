<x-layouts.app title="Students">
    <div class="admin-record-header mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <p class="page-kicker">Records</p>
            <h1 class="page-title">Students</h1>
            <p class="page-subtitle">Create accounts, assign classes, and maintain student records.</p>
        </div>
        <a class="btn-primary" href="{{ route('admin.students.create') }}">Add student</a>
    </div>

    <div class="table-shell admin-record-table">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Class</th>
                    <th>Student No.</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($students as $student)
                    <tr>
                        <td class="font-medium">{{ $student->name }}</td>
                        <td>{{ $student->user?->email ?? 'No login yet' }}</td>
                        <td>{{ $student->schoolClass?->name ?? 'Missed the class' }}</td>
                        <td>{{ $student->student_number ?? '-' }}</td>
                        <td class="admin-actions">
                            <a class="admin-action-btn edit" href="{{ route('admin.students.edit', $student) }}">Edit</a>
                            <form class="inline" method="POST" action="{{ route('admin.students.destroy', $student) }}">
                                @csrf
                                @method('DELETE')
                                <button class="admin-action-btn delete" type="submit">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="py-8 text-center text-zinc-500">No students yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-layouts.app>
