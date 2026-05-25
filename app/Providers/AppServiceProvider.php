<?php

namespace App\Providers;

use App\Models\TeacherAssignment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('components.layouts.app', function ($view) {
            $teacherPrimaryAssignment = null;

            if (Auth::check() && Auth::user()->role === 'teacher') {
                $assignments = TeacherAssignment::with(['schoolClass', 'subject'])
                    ->orderBy('school_class_id')
                    ->get();

                $teacherPrimaryAssignment = $assignments->first(function (TeacherAssignment $assignment) {
                    return $assignment->schoolClass->name === '10th Grade Mathematics (A)';
                }) ?? $assignments->first();
            }

            $view->with('teacherPrimaryAssignment', $teacherPrimaryAssignment);
        });
    }
}
