<x-layouts.app title="Teachers">
    <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <p class="page-kicker">Faculty</p>
            <h1 class="page-title">Teachers</h1>
            <p class="page-subtitle">Manage teacher accounts and class/subject assignments.</p>
        </div>
        <a class="btn-primary" href="{{ route('admin.teachers.create') }}">Add teacher</a>
    </div>

    <div class="grid gap-6 lg:grid-cols-[1fr_380px]">
        <section class="table-shell">
            <table class="data-table">
                <thead>
                    <tr><th>Name</th><th>Email</th><th>Assignments</th><th></th></tr>
                </thead>
                <tbody>
                    @forelse($teachers as $teacher)
                        <tr>
                            <td class="font-medium">{{ $teacher->name }}</td>
                            <td>{{ $teacher->email }}</td>
                            <td>
                                <div class="flex flex-wrap gap-2">
                                    @forelse($teacher->teacherAssignments as $assignment)
                                        <form method="POST" action="{{ route('admin.assignments.destroy', $assignment) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button class="status-pill transition hover:bg-red-50 hover:text-red-700" type="submit">
                                                {{ $assignment->schoolClass->name }} / {{ $assignment->subject->code }} x
                                            </button>
                                        </form>
                                    @empty
                                        <span class="text-zinc-500">None</span>
                                    @endforelse
                                </div>
                            </td>
                            <td class="text-right">
                                <a class="btn-secondary" href="{{ route('admin.teachers.edit', $teacher) }}">Edit</a>
                                <form class="inline" method="POST" action="{{ route('admin.teachers.destroy', $teacher) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn-danger" type="submit">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="py-8 text-center text-zinc-500">No teachers yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </section>

        <form class="panel space-y-5" method="POST" action="{{ route('admin.assignments.store') }}">
            @csrf
            <div>
                <p class="page-kicker">Assignments</p>
                <h2 class="mt-1 text-xl font-bold">Assign class and subject</h2>
            </div>
            <label class="form-label">Teacher
                <select class="form-input" name="teacher_id" required>
                    @foreach($teachers as $teacher)
                        <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                    @endforeach
                </select>
            </label>
            <label class="form-label">Class
                <select class="form-input" name="school_class_id" required>
                    @foreach($classes as $class)
                        <option value="{{ $class->id }}">{{ $class->name }}</option>
                    @endforeach
                </select>
            </label>
            <label class="form-label">Subject
                <select class="form-input" name="subject_id" required>
                    @foreach($subjects as $subject)
                        <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                    @endforeach
                </select>
            </label>
            <button class="btn-primary" type="submit">Save assignment</button>
        </form>
    </div>
</x-layouts.app>
