<?php

namespace App\Http\Controllers;

use App\Models\AttendanceRecord;
use App\Models\AttendanceSession;
use App\Models\Quiz;
use App\Models\QuizScore;
use App\Models\Student;
use App\Models\TeacherAssignment;
use App\Support\PerformanceMetrics;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class TeacherWorkspaceController extends Controller
{
    public function dashboard(): View
    {
        $assignments = TeacherAssignment::with(['schoolClass.students', 'subject', 'attendanceSessions', 'quizzes'])
            ->where('teacher_id', Auth::id())
            ->orderBy('school_class_id')
            ->get();

        return view('teacher.dashboard', compact('assignments'));
    }

    public function attendance(TeacherAssignment $teacherAssignment): View
    {
        $this->authorizeAssignment($teacherAssignment);

        return view('teacher.attendance', [
            'assignment' => $teacherAssignment->load('schoolClass.students', 'subject'),
            'students' => $teacherAssignment->schoolClass->students()->orderBy('name')->get(),
            'sessions' => $teacherAssignment->attendanceSessions()->with('records')->latest('session_date')->get(),
        ]);
    }

    public function storeAttendance(Request $request, TeacherAssignment $teacherAssignment): RedirectResponse
    {
        $this->authorizeAssignment($teacherAssignment);

        $data = $request->validate([
            'session_date' => ['required', 'date'],
            'topic' => ['nullable', 'string', 'max:255'],
            'statuses' => ['array'],
            'statuses.*' => ['in:present,absent,late'],
        ]);

        $session = AttendanceSession::create([
            'teacher_assignment_id' => $teacherAssignment->id,
            'session_date' => $data['session_date'],
            'topic' => $data['topic'] ?? null,
        ]);

        $this->syncAttendance($session, $data['statuses'] ?? []);

        return back()->with('status', 'Attendance saved.');
    }

    public function updateAttendance(Request $request, AttendanceSession $attendanceSession): RedirectResponse
    {
        $this->authorizeAssignment($attendanceSession->teacherAssignment);

        $data = $request->validate([
            'statuses' => ['array'],
            'statuses.*' => ['in:present,absent,late'],
        ]);

        $this->syncAttendance($attendanceSession, $data['statuses'] ?? []);

        return back()->with('status', 'Attendance updated.');
    }

    public function scores(TeacherAssignment $teacherAssignment): View
    {
        $this->authorizeAssignment($teacherAssignment);

        return view('teacher.scores', [
            'assignment' => $teacherAssignment->load('schoolClass.students', 'subject', 'quizzes.scores', 'midtermScores'),
            'students' => $teacherAssignment->schoolClass->students()->orderBy('name')->get(),
            'chart' => PerformanceMetrics::classChart($teacherAssignment),
        ]);
    }

    public function storeQuiz(Request $request, TeacherAssignment $teacherAssignment): RedirectResponse
    {
        $this->authorizeAssignment($teacherAssignment);

        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'quiz_date' => ['required', 'date'],
            'max_score' => ['required', 'integer', 'min:1', 'max:1000'],
        ]);

        $quiz = $teacherAssignment->quizzes()->create($data);

        foreach ($teacherAssignment->schoolClass->students as $student) {
            QuizScore::create([
                'quiz_id' => $quiz->id,
                'student_id' => $student->id,
                'score' => 0,
            ]);
        }

        return back()->with('status', 'Quiz added. Enter scores below.');
    }

    public function updateQuizScores(Request $request, TeacherAssignment $teacherAssignment, Quiz $quiz): RedirectResponse
    {
        $this->authorizeAssignment($teacherAssignment);
        abort_unless($quiz->teacher_assignment_id === $teacherAssignment->id, 404);

        $data = $request->validate([
            'scores' => ['array'],
            'scores.*' => ['numeric', 'min:0', 'max:1000'],
        ]);

        foreach ($data['scores'] ?? [] as $studentId => $score) {
            QuizScore::updateOrCreate(
                ['quiz_id' => $quiz->id, 'student_id' => $studentId],
                ['score' => min((float) $score, (float) $quiz->max_score)]
            );
        }

        return back()->with('status', 'Quiz scores updated.');
    }

    public function updateMidterms(Request $request, TeacherAssignment $teacherAssignment): RedirectResponse
    {
        $this->authorizeAssignment($teacherAssignment);

        $data = $request->validate([
            'scores' => ['array'],
            'scores.*' => ['numeric', 'min:0', 'max:100'],
        ]);

        foreach ($data['scores'] ?? [] as $studentId => $score) {
            $teacherAssignment->midtermScores()->updateOrCreate(
                ['student_id' => $studentId],
                ['score' => $score]
            );
        }

        return back()->with('status', 'Midterm scores updated.');
    }

    public function studentPerformance(TeacherAssignment $teacherAssignment, Student $student): View
    {
        $this->authorizeAssignment($teacherAssignment);
        abort_unless($student->school_class_id === $teacherAssignment->school_class_id, 404);

        return view('teacher.student-performance', [
            'assignment' => $teacherAssignment->load('subject', 'schoolClass'),
            'student' => $student,
            'chart' => PerformanceMetrics::studentChart($student, $teacherAssignment),
            'attendancePercent' => PerformanceMetrics::attendancePercent($student, $teacherAssignment),
            'quizAverage' => PerformanceMetrics::quizAverage($student, $teacherAssignment),
            'midterm' => PerformanceMetrics::midtermScore($student, $teacherAssignment),
        ]);
    }

    private function syncAttendance(AttendanceSession $session, array $statuses): void
    {
        foreach ($session->teacherAssignment->schoolClass->students as $student) {
            AttendanceRecord::updateOrCreate(
                ['attendance_session_id' => $session->id, 'student_id' => $student->id],
                ['status' => $statuses[$student->id] ?? 'absent']
            );
        }
    }

    private function authorizeAssignment(TeacherAssignment $teacherAssignment): void
    {
        abort_unless($teacherAssignment->teacher_id === Auth::id(), 403);
    }
}
