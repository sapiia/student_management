<x-layouts.app title="Teacher Dashboard">
    @if($primaryAssignment)
        <section class="teacher-dashboard">
            <div class="teacher-hero-grid">
                <article class="teacher-welcome-card">
                    <div>
                        <h1>Welcome back, {{ auth()->user()->name }}!</h1>
                        <p>
                            Your {{ $primaryAssignment->schoolClass->name }} class has shown a 12% improvement in average test scores this month.
                        </p>
                        <div>
                            <a href="{{ route('teacher.scores', $primaryAssignment) }}">View Monthly Analytics</a>
                            <a href="{{ route('teacher.attendance', $primaryAssignment) }}">Daily Schedule</a>
                        </div>
                    </div>
                    <span class="material-symbols-outlined">school</span>
                </article>

                <div class="teacher-stat-stack">
                    <article class="teacher-stat-card secondary">
                        <div>
                            <span>Total Students</span>
                            <strong>{{ number_format($totalStudents) }}</strong>
                            <small><span class="material-symbols-outlined">trending_up</span> +{{ number_format(max(1, $primaryAssignment->schoolClass->students->count() - 31)) }} this term</small>
                        </div>
                        <span class="material-symbols-outlined">person</span>
                    </article>
                    <article class="teacher-stat-card tertiary">
                        <div>
                            <span>Avg. Attendance</span>
                            <strong>{{ number_format($averageAttendance, 1) }}%</strong>
                            <small><span class="material-symbols-outlined">check_circle</span> Target: 95%</small>
                        </div>
                        <span class="material-symbols-outlined">event_available</span>
                    </article>
                </div>
            </div>

            <div class="teacher-bento-grid">
                <article class="teacher-upload-card">
                    <div class="teacher-section-header">
                        <div>
                            <span class="material-symbols-outlined">upload_file</span>
                            <h2>Excel Score Upload</h2>
                        </div>
                        <a href="{{ route('teacher.scores', $primaryAssignment) }}">Download Template</a>
                    </div>
                    <div class="teacher-drop-zone group" id="teacher-drop-zone">
                        <span class="material-symbols-outlined">cloud_upload</span>
                        <strong>Drag and drop your spreadsheet here</strong>
                        <p>Supports .xlsx and .csv files up to 10MB</p>
                        <a href="{{ route('teacher.scores', $primaryAssignment) }}">Or Browse Files</a>
                    </div>
                </article>

                <article class="teacher-summary-card">
                    <h2>Class Performance Summary</h2>
                    <div class="teacher-progress-list">
                        @foreach($classSummaries->take(4) as $summary)
                            @php($average = $summary['average'])
                            <div class="teacher-progress-item">
                                <span class="material-symbols-outlined">{{ $loop->iteration === 1 ? 'functions' : ($loop->iteration === 2 ? 'square_foot' : 'calculate') }}</span>
                                <div>
                                    <div>
                                        <strong>{{ $summary['assignment']->subject->name }} ({{ $summary['assignment']->schoolClass->name }})</strong>
                                        <small>{{ number_format($average, 1) }}%</small>
                                    </div>
                                    <p><span style="width: {{ min(100, max(0, $average)) }}%"></span></p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <a href="{{ route('teacher.scores', $primaryAssignment) }}">
                        View Detailed Gradebook
                        <span class="material-symbols-outlined">arrow_forward</span>
                    </a>
                </article>
            </div>

            <article class="teacher-classes-card" id="teacher-classes">
                <div class="teacher-section-header">
                    <div>
                        <span class="material-symbols-outlined">class</span>
                        <h2>All Classes</h2>
                    </div>
                    <span>{{ number_format($assignments->count()) }} active</span>
                </div>
                <div class="teacher-class-grid">
                    @foreach($assignments as $assignment)
                        @php($summary = $classSummaries->firstWhere('assignment.id', $assignment->id))
                        @php($average = $assignment->id === $primaryAssignment->id ? round($roster->avg('grade') ?? 0, 1) : ($summary['average'] ?? 0))
                        <section class="{{ $assignment->id === $primaryAssignment->id ? 'current' : '' }}">
                            <div>
                                <span class="material-symbols-outlined">{{ $assignment->id === $primaryAssignment->id ? 'star' : 'menu_book' }}</span>
                                <div>
                                    <strong>{{ $assignment->schoolClass->name }}</strong>
                                    <small>{{ $assignment->subject->name }} / {{ number_format($assignment->schoolClass->students->count()) }} students</small>
                                </div>
                            </div>
                            <p><span style="width: {{ min(100, max(0, $average)) }}%"></span></p>
                            <footer>
                                <small>{{ number_format($average, 1) }}% avg</small>
                                <div>
                                    <a href="{{ route('teacher.attendance', $assignment) }}">Attendance</a>
                                    <a href="{{ route('teacher.scores', $assignment) }}">Scores</a>
                                </div>
                            </footer>
                        </section>
                    @endforeach
                </div>
            </article>

            <article class="teacher-roster-card" id="teacher-students">
                <div class="teacher-roster-header">
                    <div>
                        <h2>All Assigned Students</h2>
                        <p>{{ number_format($allStudentsRoster->count()) }} students across {{ number_format($assignments->count()) }} assigned classes</p>
                    </div>
                    <div>
                        <a href="{{ route('teacher.attendance', $primaryAssignment) }}">
                            <span class="material-symbols-outlined">filter_list</span>
                            Filter
                        </a>
                        <a href="{{ route('teacher.scores', $primaryAssignment) }}">
                            <span class="material-symbols-outlined">download</span>
                            Export
                        </a>
                    </div>
                </div>

                <div class="teacher-table-wrap">
                    <table class="teacher-table">
                        <thead>
                            <tr>
                                <th>Student Name</th>
                                <th>Student ID</th>
                                <th>Class</th>
                                <th>Latest Grade</th>
                                <th>Attendance</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($allStudentsRoster as $row)
                                @php($student = $row['student'])
                                @php($assignment = $row['assignment'])
                                <tr>
                                    <td>
                                        <div class="teacher-student-cell">
                                            <span>{{ strtoupper(substr($student->name, 0, 1)) }}</span>
                                            <div>
                                                <strong>{{ $student->name }}</strong>
                                                <small>{{ $student->user?->email ?? 'Student record' }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>#{{ $student->student_number }}</td>
                                    <td>
                                        <strong class="teacher-class-name">{{ $assignment->schoolClass->name }}</strong>
                                        <small class="teacher-class-subject">{{ $assignment->subject->name }}</small>
                                    </td>
                                    <td>
                                        <span class="teacher-grade-pill {{ $row['grade'] < 65 ? 'danger' : ($row['grade'] >= 85 ? 'success' : '') }}">
                                            {{ $row['letter'] }} ({{ number_format($row['grade'], 1) }}%)
                                        </span>
                                    </td>
                                    <td>
                                        <div class="teacher-attendance-meter">
                                            <p><span style="width: {{ min(100, max(0, $row['attendance'])) }}%"></span></p>
                                            <small>{{ number_format($row['attendance'], 1) }}%</small>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="teacher-status-pill {{ $row['status'] === 'At Risk' ? 'danger' : '' }}">{{ $row['status'] }}</span>
                                    </td>
                                    <td>
                                        <a href="{{ route('teacher.students.performance', [$assignment, $student]) }}" aria-label="View {{ $student->name }} performance">
                                            <span class="material-symbols-outlined">more_vert</span>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="teacher-empty-row">No students are enrolled in this class yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <footer>
                    <p>Showing {{ number_format($allStudentsRoster->count()) }} students from all assigned classes</p>
                    <div>
                        <button type="button" disabled><span class="material-symbols-outlined">chevron_left</span></button>
                        <button type="button" class="active">1</button>
                        <button type="button"><span class="material-symbols-outlined">chevron_right</span></button>
                    </div>
                </footer>
            </article>

            <footer class="teacher-page-footer">
                <p>&copy; 2024 EduPulse Student Management Systems. Empowering educators worldwide.</p>
            </footer>
        </section>

        <button class="teacher-fab" type="button" aria-label="Add new record">
            <span class="material-symbols-outlined">add</span>
        </button>

        <script>
            const teacherDropZone = document.getElementById('teacher-drop-zone');

            if (teacherDropZone) {
                ['dragenter', 'dragover', 'dragleave', 'drop'].forEach((eventName) => {
                    teacherDropZone.addEventListener(eventName, (event) => {
                        event.preventDefault();
                        event.stopPropagation();
                    });
                });

                ['dragenter', 'dragover'].forEach((eventName) => {
                    teacherDropZone.addEventListener(eventName, () => teacherDropZone.classList.add('is-dragging'));
                });

                ['dragleave', 'drop'].forEach((eventName) => {
                    teacherDropZone.addEventListener(eventName, () => teacherDropZone.classList.remove('is-dragging'));
                });

                teacherDropZone.addEventListener('drop', () => {
                    window.location.href = @json(route('teacher.scores', $primaryAssignment));
                });
            }
        </script>
    @else
        <section class="teacher-empty-state">
            <span class="material-symbols-outlined">school</span>
            <h1>No classes available yet</h1>
            <p>Once classes and subjects are created in the system, they will appear here automatically for teachers.</p>
        </section>
    @endif
</x-layouts.app>
