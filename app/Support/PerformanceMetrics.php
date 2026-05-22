<?php

namespace App\Support;

use App\Models\AttendanceRecord;
use App\Models\AttendanceSession;
use App\Models\Quiz;
use App\Models\Student;
use App\Models\TeacherAssignment;
use Illuminate\Support\Collection;

class PerformanceMetrics
{
    public static function attendancePercent(Student $student, TeacherAssignment $assignment): float
    {
        $sessionIds = AttendanceSession::where('teacher_assignment_id', $assignment->id)->pluck('id');

        if ($sessionIds->isEmpty()) {
            return 0;
        }

        $records = AttendanceRecord::whereIn('attendance_session_id', $sessionIds)
            ->where('student_id', $student->id)
            ->get();

        $earned = $records->sum(fn (AttendanceRecord $record) => match ($record->status) {
            'present' => 1,
            'late' => 0.5,
            default => 0,
        });

        return round(($earned / $sessionIds->count()) * 100, 1);
    }

    public static function quizAverage(Student $student, TeacherAssignment $assignment): float
    {
        $scores = $student->quizScores()
            ->whereHas('quiz', fn ($query) => $query->where('teacher_assignment_id', $assignment->id))
            ->with('quiz')
            ->get();

        if ($scores->isEmpty()) {
            return 0;
        }

        return round($scores->avg(fn ($score) => ($score->score / max(1, $score->quiz->max_score)) * 100), 1);
    }

    public static function midtermScore(Student $student, TeacherAssignment $assignment): float
    {
        return (float) ($student->midtermScores()
            ->where('teacher_assignment_id', $assignment->id)
            ->value('score') ?? 0);
    }

    public static function studentChart(Student $student, TeacherAssignment $assignment): array
    {
        $quizzes = Quiz::where('teacher_assignment_id', $assignment->id)
            ->with(['scores' => fn ($query) => $query->where('student_id', $student->id)])
            ->orderBy('quiz_date')
            ->get();

        $sessions = AttendanceSession::where('teacher_assignment_id', $assignment->id)
            ->with(['records' => fn ($query) => $query->where('student_id', $student->id)])
            ->orderBy('session_date')
            ->get();

        $earned = 0;

        return [
            'quizLabels' => $quizzes->map(fn (Quiz $quiz) => $quiz->quiz_date->format('M j'))->values(),
            'quizScores' => $quizzes->map(function (Quiz $quiz) {
                $score = $quiz->scores->first();

                return $score ? round(($score->score / max(1, $quiz->max_score)) * 100, 1) : 0;
            })->values(),
            'attendanceLabels' => $sessions->map(fn (AttendanceSession $session) => $session->session_date->format('M j'))->values(),
            'attendanceScores' => $sessions->map(function (AttendanceSession $session, int $index) use (&$earned) {
                $record = $session->records->first();
                $earned += match ($record?->status) {
                    'present' => 1,
                    'late' => 0.5,
                    default => 0,
                };

                return round(($earned / ($index + 1)) * 100, 1);
            })->values(),
            'midterm' => self::midtermScore($student, $assignment),
        ];
    }

    public static function classChart(TeacherAssignment $assignment): array
    {
        $students = $assignment->schoolClass->students()->orderBy('name')->get();
        $quizzes = $assignment->quizzes()->with('scores')->orderBy('quiz_date')->get();
        $sessionIds = $assignment->attendanceSessions()->pluck('id');
        $distribution = ['present' => 0, 'late' => 0, 'absent' => 0];

        if ($sessionIds->isNotEmpty()) {
            AttendanceRecord::whereIn('attendance_session_id', $sessionIds)
                ->get()
                ->each(function (AttendanceRecord $record) use (&$distribution) {
                    $distribution[$record->status] = ($distribution[$record->status] ?? 0) + 1;
                });
        }

        return [
            'studentNames' => $students->pluck('name')->values(),
            'quizLabels' => $quizzes->pluck('title')->values(),
            'quizAverages' => $quizzes->map(function (Quiz $quiz) {
                if ($quiz->scores->isEmpty()) {
                    return 0;
                }

                return round($quiz->scores->avg(fn ($score) => ($score->score / max(1, $quiz->max_score)) * 100), 1);
            })->values(),
            'midtermScores' => $students->map(fn (Student $student) => self::midtermScore($student, $assignment))->values(),
            'attendanceDistribution' => array_values($distribution),
            'classAverage' => self::classAverage($students, $assignment),
        ];
    }

    public static function classAverage(Collection $students, TeacherAssignment $assignment): float
    {
        if ($students->isEmpty()) {
            return 0;
        }

        return round($students->avg(function (Student $student) use ($assignment) {
            return collect([
                self::attendancePercent($student, $assignment),
                self::quizAverage($student, $assignment),
                self::midtermScore($student, $assignment),
            ])->avg();
        }), 1);
    }
}
