<x-layouts.app title="Teacher Dashboard">
    <div class="admin-record-header mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <p class="page-kicker">Teacher dashboard</p>
            <h1 class="page-title">My classes</h1>
            <p class="page-subtitle">Open a class to record attendance, manage scores, and review student progress.</p>
        </div>
    </div>

    <div class="grid gap-4 sm:grid-cols-3">
        <div class="metric-card">
            <span>Classes</span>
            <strong>{{ number_format($assignments->count()) }}</strong>
        </div>
        <div class="metric-card">
            <span>Students</span>
            <strong>{{ number_format($assignments->sum(fn ($assignment) => $assignment->schoolClass->students->count())) }}</strong>
        </div>
        <div class="metric-card">
            <span>Quizzes</span>
            <strong>{{ number_format($assignments->sum(fn ($assignment) => $assignment->quizzes->count())) }}</strong>
        </div>
    </div>

    <div class="table-shell admin-record-table teacher-record-table mt-6">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Subject</th>
                    <th>Class</th>
                    <th>Students</th>
                    <th>Sessions</th>
                    <th>Quizzes</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($assignments as $assignment)
                    <tr>
                        <td class="font-medium">{{ $assignment->subject->name }}</td>
                        <td>{{ $assignment->schoolClass->name }}</td>
                        <td>{{ number_format($assignment->schoolClass->students->count()) }}</td>
                        <td>{{ number_format($assignment->attendanceSessions->count()) }}</td>
                        <td>{{ number_format($assignment->quizzes->count()) }}</td>
                        <td class="admin-actions">
                            <a class="admin-action-btn edit" href="{{ route('teacher.attendance', $assignment) }}">Attendance</a>
                            <a class="admin-action-btn secondary" href="{{ route('teacher.scores', $assignment) }}">Scores</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="py-8 text-center text-zinc-500">No classes assigned yet. Ask an admin to assign you to a class and subject.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-layouts.app>
