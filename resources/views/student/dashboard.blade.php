<x-layouts.app title="EduPulse SMS - Student Dashboard">
    @php
        $studentName = $student?->name ?? auth()->user()->name;
        $className = $student?->schoolClass?->name ?? 'No class assigned yet';
        $attendanceAverage = $subjects->count() ? round($subjects->avg('attendance'), 1) : 0;
        $quizAverage = $subjects->count() ? round($subjects->avg('quizAverage'), 1) : 0;
        $completedCourses = $subjects->filter(fn ($row) => $row['quizAverage'] !== null || $row['midterm'] !== null)->count();
        $earnedCredits = $completedCourses * 3;
        $attendanceBars = $chart && count($chart['attendanceScores'])
            ? collect($chart['attendanceScores'])->take(6)->values()
            : $subjects->pluck('attendance')->take(6)->values();
        $attendanceLabels = $chart && count($chart['attendanceLabels'])
            ? collect($chart['attendanceLabels'])->take(6)->values()
            : $subjects->map(fn ($row) => str($row['assignment']->subject->name)->limit(8, ''))->take(6)->values();
        $upcomingQuizzes = $subjects
            ->flatMap(fn ($row) => collect($row['quizzes'])->map(fn ($quiz) => [
                'title' => $quiz['title'],
                'date' => $quiz['date'],
                'subject' => $row['assignment']->subject->name,
                'teacher' => $row['assignment']->teacher->name,
            ]))
            ->sortBy('date')
            ->take(3)
            ->values();
    @endphp

    <aside class="student-sidebar">
        <div class="px-3">
            <h1>Main Academy</h1>
            <p>Student Portal</p>
        </div>

        <nav class="student-nav">
            <a class="active" href="{{ route('student.dashboard') }}">
                <span class="material-symbols-outlined">dashboard</span>
                <span>Dashboard</span>
            </a>
            <a href="{{ route('student.dashboard') }}">
                <span class="material-symbols-outlined">menu_book</span>
                <span>Courses</span>
            </a>
            <a href="{{ route('student.dashboard') }}">
                <span class="material-symbols-outlined">event_note</span>
                <span>Quizzes</span>
            </a>
            <a href="{{ route('student.dashboard') }}">
                <span class="material-symbols-outlined">bar_chart</span>
                <span>Progress</span>
            </a>
            <a href="{{ route('student.dashboard') }}">
                <span class="material-symbols-outlined">calendar_today</span>
                <span>Schedule</span>
            </a>
        </nav>

        <div class="student-sidebar-footer">
            <a class="student-study-button" href="{{ route('student.dashboard') }}">Study Hub</a>
            <a href="{{ route('student.dashboard') }}">
                <span class="material-symbols-outlined">help</span>
                <span>Help Center</span>
            </a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit">
                    <span class="material-symbols-outlined">logout</span>
                    <span>Logout</span>
                </button>
            </form>
        </div>
    </aside>

    <main class="student-main">
        <header class="student-topbar">
            <a class="student-menu-button" href="#student-dashboard">
                <span class="material-symbols-outlined">menu</span>
            </a>
            <div>
                <span class="student-product-name">EduPulse SMS</span>
                <p>{{ $className }}</p>
            </div>
            <div class="student-top-actions">
                <label class="student-search">
                    <span class="material-symbols-outlined">search</span>
                    <input type="search" placeholder="Search courses...">
                </label>
                <button type="button" aria-label="Notifications">
                    <span class="material-symbols-outlined">notifications</span>
                    <span></span>
                </button>
                <button type="button" aria-label="Settings">
                    <span class="material-symbols-outlined">settings</span>
                </button>
                <div class="student-avatar" aria-label="{{ $studentName }}">
                    {{ strtoupper(substr($studentName, 0, 1)) }}
                </div>
            </div>
        </header>

        <div id="student-dashboard" class="student-content">
            @if(! $student)
                <section class="student-empty-state">
                    <span class="material-symbols-outlined">school</span>
                    <h2>Your student profile has not been created yet.</h2>
                    <p>Ask an administrator to connect this account to a student record.</p>
                </section>
            @else
                <section class="student-hero">
                    <div>
                        <h2>Academic Progress</h2>
                        <p>Welcome back, {{ $studentName }}. Here is your performance overview for this semester.</p>
                    </div>
                    <div class="student-hero-stats">
                        <div>
                            <span>Overall GPA</span>
                            <strong>{{ number_format(min(4, ($quizAverage / 100) * 4), 1) }} / 4.0</strong>
                        </div>
                        <div>
                            <span>Attendance</span>
                            <strong>{{ number_format($attendanceAverage, 1) }}%</strong>
                        </div>
                    </div>
                </section>

                <section class="student-grid">
                    <article class="student-card attendance-card">
                        <div class="student-card-header">
                            <h3>Attendance Overview</h3>
                            <span>Latest sessions</span>
                        </div>
                        <div class="student-bars">
                            @forelse($attendanceBars as $index => $score)
                                <div class="student-bar-group">
                                    <div>
                                        <span style="height: {{ max(6, min(100, (float) $score)) }}%"></span>
                                    </div>
                                    <small>{{ $attendanceLabels[$index] ?? 'Item' }}</small>
                                </div>
                            @empty
                                <p class="student-muted">Attendance will appear after teachers record sessions.</p>
                            @endforelse
                        </div>
                    </article>

                    <div class="student-stat-stack">
                        <article class="student-stat-card secondary">
                            <div>
                                <span>Course Completed</span>
                                <strong>{{ $completedCourses }} / {{ max(1, $subjects->count()) }}</strong>
                            </div>
                            <span class="material-symbols-outlined">check_circle</span>
                        </article>
                        <article class="student-stat-card tertiary">
                            <div>
                                <span>Credits Earned</span>
                                <strong>{{ $earnedCredits }}</strong>
                            </div>
                            <span class="material-symbols-outlined">stars</span>
                        </article>
                    </div>

                    <article class="student-card performance-card">
                        <div class="student-card-header">
                            <h3>Academic Performance</h3>
                            <div class="student-legend">
                                <span><i></i>Current</span>
                                <span><i></i>Midterm</span>
                            </div>
                        </div>
                        <div class="student-progress-list">
                            @forelse($subjects->take(4) as $row)
                                <div>
                                    <div class="student-progress-label">
                                        <strong>{{ $row['assignment']->subject->name }}</strong>
                                        <span>{{ $row['quizAverage'] }}% vs {{ $row['midterm'] }}/100</span>
                                    </div>
                                    <div class="student-progress-track">
                                        <span class="previous" style="width: {{ max(0, min(100, (float) $row['midterm'])) }}%"></span>
                                        <span style="width: {{ max(0, min(100, (float) $row['quizAverage'])) }}%"></span>
                                    </div>
                                </div>
                            @empty
                                <p class="student-muted">No subjects assigned to your class yet.</p>
                            @endforelse
                        </div>
                    </article>

                    <article class="student-card quiz-card">
                        <div class="student-card-header">
                            <h3>Upcoming Quizzes</h3>
                            <a href="{{ route('student.dashboard') }}">View Calendar</a>
                        </div>
                        <div class="student-quiz-list">
                            @forelse($upcomingQuizzes as $quiz)
                                <div class="student-quiz-item">
                                    <div class="student-date-badge">
                                        <span>{{ optional($quiz['date'])->format('M') ?? 'TBD' }}</span>
                                        <strong>{{ optional($quiz['date'])->format('d') ?? '--' }}</strong>
                                    </div>
                                    <div>
                                        <strong>{{ $quiz['title'] }}</strong>
                                        <p>{{ $quiz['subject'] }} with {{ $quiz['teacher'] }}</p>
                                    </div>
                                    <span>Quiz</span>
                                </div>
                            @empty
                                <p class="student-muted">No quizzes are scheduled yet.</p>
                            @endforelse
                        </div>
                    </article>

                    <article class="student-final-card">
                        <div>
                            <h3>Master the Finals Prep</h3>
                            <p>Review your strongest courses, revisit quizzes with lower scores, and keep attendance on track before finals.</p>
                        </div>
                        <a href="{{ route('student.dashboard') }}">Access Study Hub</a>
                    </article>
                </section>
            @endif
        </div>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('.student-bars span').forEach((bar) => {
                const height = bar.style.height;
                bar.style.height = '0';
                setTimeout(() => {
                    bar.style.height = height;
                }, 120);
            });
        });
    </script>
</x-layouts.app>
