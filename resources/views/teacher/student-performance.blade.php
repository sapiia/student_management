<x-layouts.app title="Student Performance">
    <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <p class="page-kicker">{{ $assignment->schoolClass->name }} / {{ $assignment->subject->name }}</p>
            <h1 class="page-title">{{ $student->name }}</h1>
        </div>
        <a class="btn-secondary" href="{{ route('teacher.scores', $assignment) }}">Back to scores</a>
    </div>

    <div class="grid gap-4 sm:grid-cols-3">
        <div class="metric-card"><span>Attendance</span><strong>{{ $attendancePercent }}%</strong></div>
        <div class="metric-card"><span>Quiz average</span><strong>{{ $quizAverage }}%</strong></div>
        <div class="metric-card"><span>Midterm</span><strong>{{ $midterm }}/100</strong></div>
    </div>

    <section class="panel mt-6">
        <p class="page-kicker">Evaluation</p>
        <h2 class="mt-1 text-xl font-bold">Evaluation graph</h2>
        <div class="mt-4 grid gap-6 lg:grid-cols-2">
            <canvas id="quizTrend" height="220"></canvas>
            <canvas id="attendanceTrend" height="220"></canvas>
        </div>
    </section>

    <script>
        new Chart(document.getElementById('quizTrend'), {
            type: 'line',
            data: {
                labels: @json($chart['quizLabels']),
                datasets: [{ label: 'Quiz %', data: @json($chart['quizScores']), borderColor: '#4f79c9', backgroundColor: '#eef3ff', tension: 0.3 }]
            },
            options: { scales: { y: { min: 0, max: 100 } } }
        });

        new Chart(document.getElementById('attendanceTrend'), {
            type: 'line',
            data: {
                labels: @json($chart['attendanceLabels']),
                datasets: [{ label: 'Attendance %', data: @json($chart['attendanceScores']), borderColor: '#dc4e98', backgroundColor: '#fdeef6', tension: 0.3 }]
            },
            options: { scales: { y: { min: 0, max: 100 } } }
        });
    </script>
</x-layouts.app>
