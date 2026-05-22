<x-layouts.app title="My Progress">
    <div class="mb-6">
        <p class="page-kicker">Student dashboard</p>
        <h1 class="page-title">My progress</h1>
        <p class="page-subtitle">{{ $student?->schoolClass?->name ?? 'No class assigned yet' }}</p>
    </div>

    @if(! $student)
        <section class="panel">
            <p class="text-zinc-600">Your student profile has not been created yet.</p>
        </section>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($subjects as $row)
                <div class="panel flex flex-col">
                    <div class="mb-4 border-b border-zinc-100 pb-3">
                        <h3 class="font-bold text-lg text-zinc-900">{{ $row['assignment']->subject->name }}</h3>
                        <p class="text-sm text-zinc-500">{{ $row['assignment']->teacher->name }}</p>
                    </div>
                    
                    <div class="space-y-3 flex-grow">
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-zinc-600">Attendance</span>
                            <span class="font-medium">{{ $row['attendance'] }}%</span>
                        </div>
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-zinc-600">Midterm</span>
                            <span class="font-medium">{{ $row['midterm'] }}/100</span>
                        </div>
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-zinc-600">Quiz Average</span>
                            <span class="font-medium">{{ $row['quizAverage'] }}%</span>
                        </div>

                        @if(count($row['quizzes']) > 0)
                            <div class="mt-4 pt-4 border-t border-zinc-100">
                                <h4 class="text-xs font-bold uppercase tracking-wider text-zinc-500 mb-3">Quiz Sessions</h4>
                                <ul class="space-y-2">
                                    @foreach($row['quizzes'] as $quiz)
                                        <li class="flex justify-between items-center text-sm">
                                            <span class="text-zinc-600 truncate mr-2" title="{{ $quiz['title'] }}">{{ $quiz['title'] }}</span>
                                            <div class="flex items-center gap-2 whitespace-nowrap">
                                                <span class="font-medium {{ $quiz['score'] !== null ? 'text-zinc-900' : 'text-zinc-400' }}">{{ $quiz['score'] !== null ? $quiz['score'].'%' : 'N/A' }}</span>
                                                @if($quiz['progress'] !== null)
                                                    <span class="text-xs font-medium {{ $quiz['progress'] > 0 ? 'text-green-600' : ($quiz['progress'] < 0 ? 'text-red-600' : 'text-zinc-400') }}">
                                                        @if($quiz['progress'] > 0)
                                                            <svg class="inline w-3 h-3 mr-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path></svg>{{ $quiz['progress'] }}%
                                                        @elseif($quiz['progress'] < 0)
                                                            <svg class="inline w-3 h-3 mr-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path></svg>{{ abs($quiz['progress']) }}%
                                                        @else
                                                            <svg class="inline w-3 h-3 mr-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14"></path></svg>0%
                                                        @endif
                                                    </span>
                                                @endif
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    </div>
                </div>
            @empty
                <div class="col-span-full panel text-center py-12 text-zinc-500">
                    No subjects assigned to your class yet.
                </div>
            @endforelse
        </div>

        @if($chart)
            <section class="panel mt-6">
                <p class="page-kicker">Evaluation</p>
                <h2 class="mt-1 text-xl font-bold">Personal evaluation graph</h2>
                <p class="mt-1 text-sm text-zinc-500">Showing the first assigned subject. Each subject summary is listed above.</p>
                <div class="mt-4 grid gap-6 lg:grid-cols-2">
                    <canvas id="studentQuizTrend" height="220"></canvas>
                    <canvas id="studentAttendanceTrend" height="220"></canvas>
                </div>
            </section>

            <script>
                new Chart(document.getElementById('studentQuizTrend'), {
                    type: 'line',
                    data: {
                        labels: @json($chart['quizLabels']),
                        datasets: [{ label: 'Quiz %', data: @json($chart['quizScores']), borderColor: '#4f79c9', backgroundColor: '#eef3ff', tension: 0.3 }]
                    },
                    options: { scales: { y: { min: 0, max: 100 } } }
                });

                new Chart(document.getElementById('studentAttendanceTrend'), {
                    type: 'line',
                    data: {
                        labels: @json($chart['attendanceLabels']),
                        datasets: [{ label: 'Attendance %', data: @json($chart['attendanceScores']), borderColor: '#dc4e98', backgroundColor: '#fdeef6', tension: 0.3 }]
                    },
                    options: { scales: { y: { min: 0, max: 100 } } }
                });
            </script>
        @endif
    @endif
</x-layouts.app>
