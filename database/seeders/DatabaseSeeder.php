<?php

namespace Database\Seeders;

use App\Models\AttendanceRecord;
use App\Models\AttendanceSession;
use App\Models\MidtermScore;
use App\Models\Quiz;
use App\Models\QuizScore;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\Subject;
use App\Models\TeacherAssignment;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $admin = User::updateOrCreate(
            ['email' => 'admin@example.com'],
            ['name' => 'Admin User', 'role' => 'admin', 'password' => Hash::make('password')]
        );

        $teacher = User::updateOrCreate(
            ['email' => 'teacher@example.com'],
            ['name' => 'Maya Chen', 'role' => 'teacher', 'password' => Hash::make('password')]
        );

        $secondTeacher = User::updateOrCreate(
            ['email' => 'science@example.com'],
            ['name' => 'Noah Patel', 'role' => 'teacher', 'password' => Hash::make('password')]
        );

        $class = SchoolClass::updateOrCreate(
            ['name' => 'Grade 10 - A'],
            ['grade_level' => '10']
        );

        $math = Subject::updateOrCreate(
            ['code' => 'MATH10'],
            ['name' => 'Mathematics']
        );

        $science = Subject::updateOrCreate(
            ['code' => 'SCI10'],
            ['name' => 'Science']
        );

        $studentUsers = collect([
            ['name' => 'Ari Walker', 'email' => 'student@example.com', 'number' => 'STU-1001'],
            ['name' => 'Bella Kim', 'email' => 'bella@example.com', 'number' => 'STU-1002'],
            ['name' => 'Diego Santos', 'email' => 'diego@example.com', 'number' => 'STU-1003'],
            ['name' => 'Fatima Ali', 'email' => 'fatima@example.com', 'number' => 'STU-1004'],
        ])->map(function (array $row) use ($class) {
            $user = User::updateOrCreate(
                ['email' => $row['email']],
                ['name' => $row['name'], 'role' => 'student', 'password' => Hash::make('password')]
            );

            return Student::updateOrCreate(
                ['student_number' => $row['number']],
                [
                    'user_id' => $user->id,
                    'school_class_id' => $class->id,
                    'name' => $row['name'],
                    'guardian_name' => 'Demo Guardian',
                ]
            );
        });

        $mathAssignment = TeacherAssignment::updateOrCreate([
            'teacher_id' => $teacher->id,
            'school_class_id' => $class->id,
            'subject_id' => $math->id,
        ]);

        TeacherAssignment::updateOrCreate([
            'teacher_id' => $secondTeacher->id,
            'school_class_id' => $class->id,
            'subject_id' => $science->id,
        ]);

        $sessionDates = [
            now()->subDays(24)->toDateString(),
            now()->subDays(17)->toDateString(),
            now()->subDays(10)->toDateString(),
            now()->subDays(3)->toDateString(),
        ];

        foreach ($sessionDates as $index => $date) {
            $session = AttendanceSession::updateOrCreate(
                ['teacher_assignment_id' => $mathAssignment->id, 'session_date' => $date],
                ['topic' => 'Unit '.($index + 1)]
            );

            foreach ($studentUsers as $studentIndex => $student) {
                $status = match (($index + $studentIndex) % 5) {
                    0 => 'late',
                    3 => 'absent',
                    default => 'present',
                };

                AttendanceRecord::updateOrCreate(
                    ['attendance_session_id' => $session->id, 'student_id' => $student->id],
                    ['status' => $status]
                );
            }
        }

        $quizScores = [
            'Quiz 1' => [86, 78, 91, 84],
            'Quiz 2' => [88, 82, 87, 90],
            'Quiz 3' => [94, 85, 89, 92],
        ];

        foreach ($quizScores as $title => $scores) {
            $quiz = Quiz::updateOrCreate(
                ['teacher_assignment_id' => $mathAssignment->id, 'title' => $title],
                ['quiz_date' => now()->subDays(20 - (int) str_replace('Quiz ', '', $title) * 6)->toDateString(), 'max_score' => 100]
            );

            foreach ($studentUsers as $index => $student) {
                QuizScore::updateOrCreate(
                    ['quiz_id' => $quiz->id, 'student_id' => $student->id],
                    ['score' => $scores[$index]]
                );
            }
        }

        foreach ([90, 83, 88, 91] as $index => $score) {
            MidtermScore::updateOrCreate(
                ['teacher_assignment_id' => $mathAssignment->id, 'student_id' => $studentUsers[$index]->id],
                ['score' => $score]
            );
        }
    }
}
