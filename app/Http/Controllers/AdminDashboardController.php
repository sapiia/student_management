<?php

namespace App\Http\Controllers;

use App\Models\AttendanceRecord;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\TeacherAssignment;
use App\Models\User;
use App\Support\PerformanceMetrics;
use Illuminate\View\View;

class AdminDashboardController extends Controller
{
    public function __invoke(): View
    {
        $assignments = TeacherAssignment::with(['schoolClass.students', 'subject', 'teacher'])->get();
        $classAverages = $assignments->map(fn (TeacherAssignment $assignment) => [
            'label' => $assignment->schoolClass->name.' / '.$assignment->subject->name,
            'teacher' => $assignment->teacher->name,
            'average' => PerformanceMetrics::classAverage($assignment->schoolClass->students, $assignment),
        ]);
        $attendanceCounts = AttendanceRecord::selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');
        $topStudents = Student::with(['schoolClass', 'quizScores.quiz'])
            ->orderBy('name')
            ->take(4)
            ->get()
            ->map(function (Student $student) {
                $scores = $student->quizScores;
                $average = $scores->isEmpty()
                    ? 0
                    : round($scores->avg(fn ($score) => ($score->score / max(1, $score->quiz?->max_score ?? 100)) * 100));

                return [
                    'name' => $student->name,
                    'id' => $student->student_number ?? 'STU-'.$student->id,
                    'marks' => (int) round($scores->sum('score')),
                    'percent' => $average,
                    'class' => $student->schoolClass?->name ?? 'Missed the class',
                ];
            });

        return view('admin.dashboard', [
            'totalStudents' => Student::count(),
            'totalTeachers' => User::where('role', 'teacher')->count(),
            'totalClasses' => SchoolClass::count(),
            'overallAverage' => round($classAverages->avg('average') ?? 0, 1),
            'classAverages' => $classAverages,
            'topStudents' => $topStudents,
            'attendanceChart' => [
                'labels' => ['Present', 'Late', 'Absent'],
                'values' => [
                    $attendanceCounts->get('present', 0),
                    $attendanceCounts->get('late', 0),
                    $attendanceCounts->get('absent', 0),
                ],
            ],
        ]);
    }
}
