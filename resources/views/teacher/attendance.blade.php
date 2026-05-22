<x-layouts.app title="Attendance">
    <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <p class="page-kicker">{{ $assignment->subject->name }}</p>
            <h1 class="page-title">{{ $assignment->schoolClass->name }} attendance</h1>
        </div>
        <a class="btn-secondary" href="{{ route('teacher.dashboard') }}">Back to classes</a>
    </div>

    <form class="panel" method="POST" action="{{ route('teacher.attendance.store', $assignment) }}">
        @csrf
        <div class="grid gap-4 sm:grid-cols-3">
            <label class="form-label">Session date
                <input class="form-input" type="date" name="session_date" value="{{ now()->toDateString() }}" required>
            </label>
            <label class="form-label sm:col-span-2">Topic
                <input class="form-input" type="text" name="topic" placeholder="Lesson topic">
            </label>
        </div>
        <div class="mt-5 table-shell shadow-none">
            <table class="data-table">
                <thead><tr><th>Student</th><th>Status</th></tr></thead>
                <tbody>
                    @foreach($students as $student)
                        <tr>
                            <td class="font-medium">{{ $student->name }}</td>
                            <td>
                                <select class="form-input max-w-xs" name="statuses[{{ $student->id }}]">
                                    <option value="present">Present</option>
                                    <option value="late">Late</option>
                                    <option value="absent">Absent</option>
                                </select>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <button class="btn-primary mt-5" type="submit">Save session</button>
    </form>

    <div class="mt-8 grid gap-4">
        @foreach($sessions as $session)
            <form class="panel" method="POST" action="{{ route('teacher.attendance.update', $session) }}">
                @csrf
                @method('PUT')
                <div class="mb-4 flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between">
                    <h2 class="text-xl font-bold">{{ $session->session_date->format('M j, Y') }}</h2>
                    <p class="text-sm text-zinc-500">{{ $session->topic ?: 'No topic' }}</p>
                </div>
                <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach($students as $student)
                        @php($record = $session->records->firstWhere('student_id', $student->id))
                        <label class="form-label">{{ $student->name }}
                            <select class="form-input" name="statuses[{{ $student->id }}]">
                                @foreach(['present' => 'Present', 'late' => 'Late', 'absent' => 'Absent'] as $value => $label)
                                    <option value="{{ $value }}" @selected(($record?->status ?? 'absent') === $value)>{{ $label }}</option>
                                @endforeach
                            </select>
                        </label>
                    @endforeach
                </div>
                <button class="btn-secondary mt-4" type="submit">Update session</button>
            </form>
        @endforeach
    </div>
</x-layouts.app>
