<x-layouts.app title="Scores">
    <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <p class="page-kicker">{{ $assignment->subject->name }}</p>
            <h1 class="page-title">{{ $assignment->schoolClass->name }} scores</h1>
        </div>
        <a class="btn-secondary" href="{{ route('teacher.dashboard') }}">Back to classes</a>
    </div>

    <div class="grid gap-6 lg:grid-cols-[380px_1fr]">
        <form class="panel space-y-5" method="POST" action="{{ route('teacher.quizzes.store', $assignment) }}">
            @csrf
            <div>
                <p class="page-kicker">Assessment</p>
                <h2 class="mt-1 text-xl font-bold">Add quiz</h2>
            </div>
            <label class="form-label">Title
                <input class="form-input" type="text" name="title" placeholder="Quiz 4" required>
            </label>
            <label class="form-label">Date
                <input class="form-input" type="date" name="quiz_date" value="{{ now()->toDateString() }}" required>
            </label>
            <label class="form-label">Max score
                <input class="form-input" type="number" name="max_score" value="100" min="1" max="1000" required>
            </label>
            <button class="btn-primary" type="submit">Add quiz</button>
        </form>

        <section class="panel">
            <p class="page-kicker">Evaluation</p>
            <h2 class="mt-1 text-xl font-bold">Class evaluation</h2>
            <div class="mt-4 grid gap-6 lg:grid-cols-2">
                <canvas id="quizAverageChart" height="220"></canvas>
                <canvas id="attendanceDistributionChart" height="220"></canvas>
            </div>
        </section>
    </div>

    <form class="panel mt-6" method="POST" action="{{ route('teacher.midterms.update', $assignment) }}">
        @csrf
        @method('PUT')
        <h2 class="text-xl font-bold">Midterm scores</h2>
        <div class="mt-4 grid gap-3 sm:grid-cols-2 lg:grid-cols-4">
            @foreach($students as $student)
                @php($score = $assignment->midtermScores->firstWhere('student_id', $student->id))
                <label class="form-label">{{ $student->name }}
                    <input class="form-input" type="number" name="scores[{{ $student->id }}]" min="0" max="100" step="0.01" value="{{ old('scores.'.$student->id, $score?->score ?? 0) }}">
                </label>
            @endforeach
        </div>
        <button class="btn-secondary mt-4" type="submit">Save midterms</button>
    </form>

    <div class="mt-6 grid gap-6">
        @foreach($assignment->quizzes as $quiz)
            <form class="panel" method="POST" action="{{ route('teacher.quizzes.update', [$assignment, $quiz]) }}">
                @csrf
                @method('PUT')
                <div class="mb-4 flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between">
                    <h2 class="text-xl font-bold">{{ $quiz->title }}</h2>
                    <p class="text-sm text-zinc-500">{{ $quiz->quiz_date->format('M j, Y') }} / {{ $quiz->max_score }} pts</p>
                </div>
                <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-4">
                    @foreach($students as $student)
                        @php($score = $quiz->scores->firstWhere('student_id', $student->id))
                        <label class="form-label">
                            <a class="text-[#4f79c9] transition hover:text-[#dc4e98]" href="{{ route('teacher.students.performance', [$assignment, $student]) }}">{{ $student->name }}</a>
                            <input class="form-input" type="number" name="scores[{{ $student->id }}]" min="0" max="{{ $quiz->max_score }}" step="0.01" value="{{ old('scores.'.$student->id, $score?->score ?? 0) }}">
                        </label>
                    @endforeach
                </div>
                <button class="btn-secondary mt-4" type="submit">Save quiz scores</button>
            </form>
        @endforeach
    </div>

    <script>
        new Chart(document.getElementById('quizAverageChart'), {
            type: 'bar',
            data: {
                labels: @json($chart['quizLabels']),
                datasets: [{ label: 'Quiz average %', data: @json($chart['quizAverages']), backgroundColor: '#4f79c9' }]
            },
            options: { scales: { y: { min: 0, max: 100 } } }
        });

        new Chart(document.getElementById('attendanceDistributionChart'), {
            type: 'pie',
            data: {
                labels: ['Present', 'Late', 'Absent'],
                datasets: [{ data: @json($chart['attendanceDistribution']), backgroundColor: ['#4f79c9', '#dc4e98', '#f59e0b'] }]
            }
        });
    </script>
</x-layouts.app>
