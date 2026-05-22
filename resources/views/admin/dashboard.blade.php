<x-layouts.app title="Admin Dashboard">
    <section class="admin-card">
        <h1 class="text-2xl font-black text-[#171326]">Admin Dashboard</h1>

        <div class="mt-5 grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            <div class="admin-stat-card">
                <div><span>Students</span><strong>{{ number_format($totalStudents) }}</strong></div>
                <div class="admin-stat-icon">S</div>
            </div>
            <div class="admin-stat-card">
                <div><span>Teachers</span><strong>{{ number_format($totalTeachers) }}</strong></div>
                <div class="admin-stat-icon">T</div>
            </div>
            <div class="admin-stat-card">
                <div><span>Classes</span><strong>{{ number_format($totalClasses) }}</strong></div>
                <div class="admin-stat-icon">C</div>
            </div>
            <div class="admin-stat-card">
                <div><span>Avg Score</span><strong>{{ $overallAverage }}%</strong></div>
                <div class="admin-stat-icon">%</div>
            </div>
        </div>
    </section>

    <div class="mt-6 grid gap-6 xl:grid-cols-[minmax(0,1fr)_330px]">
        <section class="admin-card">
            <div class="flex items-start justify-between">
                <div>
                    <h2 class="text-xl font-black">All Exam Result</h2>
                    <p class="mt-1 text-sm text-zinc-500">Students and teacher performance</p>
                </div>
                <div class="flex items-center gap-4 text-xs font-semibold text-zinc-500">
                    <span><span class="mr-1 inline-block h-2 w-2 rounded-full bg-[#4f79c9]"></span>Teacher</span>
                    <span><span class="mr-1 inline-block h-2 w-2 rounded-full bg-[#dc4e98]"></span>Student</span>
                </div>
            </div>
            <canvas class="mt-4" id="examResultChart" height="220"></canvas>
        </section>

        <section class="admin-card">
            <div class="flex items-start justify-between">
                <h2 class="text-xl font-black">Students</h2>
                <span class="text-xl font-black text-zinc-400">...</span>
            </div>
            <div class="mx-auto mt-4 max-w-[230px]">
                <canvas id="studentsChart" height="230"></canvas>
            </div>
            <div class="mt-4 flex justify-center gap-7 text-sm font-semibold">
                <span><span class="mr-2 inline-block h-3 w-3 rounded-full bg-[#4f79c9]"></span>Present</span>
                <span><span class="mr-2 inline-block h-3 w-3 rounded-full bg-[#dc4e98]"></span>Late</span>
            </div>
        </section>
    </div>

    <div class="mt-6 grid gap-6 xl:grid-cols-[minmax(0,1fr)_330px]">
        <section class="admin-card">
            <div class="flex items-start justify-between">
                <h2 class="text-xl font-black">Star Students</h2>
                <span class="text-xl font-black text-zinc-400">...</span>
            </div>
            <div class="mt-4 overflow-x-auto">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th></th>
                            <th>Name</th>
                            <th>ID</th>
                            <th>Marks</th>
                            <th>Percent</th>
                            <th>Class</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($topStudents as $student)
                            <tr>
                                <td><span class="inline-flex h-4 w-4 rounded border border-zinc-300 {{ $loop->iteration === 2 ? 'bg-[#dc4e98]' : 'bg-white' }}"></span></td>
                                <td class="font-semibold text-zinc-900">{{ $student['name'] }}</td>
                                <td>{{ $student['id'] }}</td>
                                <td>{{ $student['marks'] }}</td>
                                <td>{{ $student['percent'] }}%</td>
                                <td>{{ $student['class'] }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="py-8 text-center text-zinc-500">No students yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        <section class="admin-card">
            <div class="flex items-start justify-between">
                <h2 class="text-xl font-black">All Exam Results</h2>
                <span class="text-xl font-black text-zinc-400">...</span>
            </div>
            <div class="mt-4 space-y-3">
                @forelse($classAverages->take(3) as $row)
                    <div class="flex items-center gap-3 border-b border-zinc-100 pb-3">
                        <div class="flex h-10 w-10 items-center justify-center rounded-md bg-[#eef3ff] text-sm font-black text-[#4f79c9]">{{ $loop->iteration }}</div>
                        <div class="min-w-0 flex-1">
                            <p class="truncate text-sm font-bold text-zinc-900">{{ $row['label'] }}</p>
                            <p class="truncate text-xs text-zinc-400">{{ $row['teacher'] }}</p>
                        </div>
                        <p class="text-xs font-semibold text-zinc-500">{{ $row['average'] }}%</p>
                    </div>
                @empty
                    <p class="py-8 text-center text-sm text-zinc-500">No exam results yet.</p>
                @endforelse
            </div>
            <a class="btn-primary mt-4 w-full" href="{{ route('admin.students.index') }}">View All</a>
        </section>
    </div>

    <script>
        const resultLabels = @json($classAverages->pluck('label')->take(8)->values());
        const resultValues = @json($classAverages->pluck('average')->take(8)->values());
        const attendanceValues = @json($attendanceChart['values']);

        new Chart(document.getElementById('examResultChart'), {
            type: 'bar',
            data: {
                labels: resultLabels.length ? resultLabels : ['Jan', 'Feb', 'Mar', 'Apr'],
                datasets: [
                    {
                        label: 'Teacher',
                        data: resultValues.length ? resultValues : [65, 52, 78, 60],
                        backgroundColor: '#4f79c9',
                        borderRadius: 6,
                        barPercentage: 0.45
                    },
                    {
                        label: 'Student',
                        data: resultValues.length ? resultValues.map((value) => Math.max(0, value - 12)) : [48, 50, 60, 42],
                        backgroundColor: '#dc4e98',
                        borderRadius: 6,
                        barPercentage: 0.45
                    }
                ]
            },
            options: {
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, max: 100, grid: { color: '#eef3ff', borderDash: [4, 4] } },
                    x: { grid: { display: false }, ticks: { maxRotation: 0, autoSkip: true } }
                }
            }
        });

        new Chart(document.getElementById('studentsChart'), {
            type: 'doughnut',
            data: {
                labels: @json($attendanceChart['labels']),
                datasets: [{
                    data: attendanceValues.some((value) => value > 0) ? attendanceValues : [65, 20, 15],
                    backgroundColor: ['#4f79c9', '#dc4e98', '#f3f5fb'],
                    borderColor: '#ffffff',
                    borderWidth: 8,
                    cutout: '64%'
                }]
            },
            options: {
                plugins: {
                    legend: { display: false },
                    tooltip: { enabled: true }
                }
            },
            plugins: [{
                id: 'centerTotal',
                afterDraw(chart) {
                    const { ctx, chartArea } = chart;
                    ctx.save();
                    ctx.textAlign = 'center';
                    ctx.fillStyle = '#71717a';
                    ctx.font = '13px Instrument Sans, sans-serif';
                    ctx.fillText('Total', (chartArea.left + chartArea.right) / 2, (chartArea.top + chartArea.bottom) / 2 - 8);
                    ctx.fillStyle = '#171326';
                    ctx.font = 'bold 24px Instrument Sans, sans-serif';
                    ctx.fillText('{{ number_format($totalStudents) }}', (chartArea.left + chartArea.right) / 2, (chartArea.top + chartArea.bottom) / 2 + 20);
                    ctx.restore();
                }
            }]
        });
    </script>
</x-layouts.app>
