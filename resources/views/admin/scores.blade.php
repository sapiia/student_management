<x-layouts.app title="Student Scores">
    <section class="admin-card">
        <div class="flex items-start justify-between">
            <div>
                <h1 class="text-2xl font-black text-[#171326]">Student Scores Dashboard</h1>
                <p class="mt-1 text-sm text-zinc-500">View average scores for all students</p>
            </div>
            <a href="{{ route('admin.upload') }}" class="btn-primary">Upload CSV</a>
        </div>

        @if(session('success'))
            <div class="mt-4 rounded-md bg-green-50 p-4 text-sm text-green-800">
                {{ session('success') }}
            </div>
        @endif

        <div class="mt-5 grid gap-4 sm:grid-cols-3">
            <div class="admin-stat-card">
                <div><span>Total Students</span><strong>{{ number_format($totalStudents) }}</strong></div>
                <div class="admin-stat-icon">S</div>
            </div>
            <div class="admin-stat-card">
                <div><span>Quiz Average</span><strong>{{ $quizAverage ? number_format($quizAverage, 2) : 'N/A' }}</strong></div>
                <div class="admin-stat-icon">Q</div>
            </div>
            <div class="admin-stat-card">
                <div><span>Midterm Average</span><strong>{{ $midtermAverage ? number_format($midtermAverage, 2) : 'N/A' }}</strong></div>
                <div class="admin-stat-icon">M</div>
            </div>
        </div>

        @if($overallAverage)
            <div class="mt-5 admin-stat-card">
                <div><span>Overall Average (Quiz + Midterm)</span><strong>{{ number_format($overallAverage, 2) }}</strong></div>
                <div class="admin-stat-icon">%</div>
            </div>
        @endif
    </section>

    <section class="admin-card mt-6">
        <h2 class="text-xl font-black">All Students</h2>
        <div class="mt-4 overflow-x-auto">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Quiz Score</th>
                        <th>Midterm Score</th>
                        <th>Average</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($students as $student)
                        <tr>
                            <td>{{ $student->id }}</td>
                            <td class="font-semibold text-zinc-900">{{ $student->name }}</td>
                            <td>{{ $student->quiz_score ? number_format($student->quiz_score, 2) : '-' }}</td>
                            <td>{{ $student->midterm_score ? number_format($student->midterm_score, 2) : '-' }}</td>
                            <td>
                                @if($student->quiz_score && $student->midterm_score)
                                    {{ number_format(($student->quiz_score + $student->midterm_score) / 2, 2) }}
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="py-8 text-center text-zinc-500">No students yet. Upload a CSV file to get started.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
</x-layouts.app>
