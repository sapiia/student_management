<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? config('app.name', 'Student Management') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="{{ auth()->check() && in_array(auth()->user()->role, ['admin', 'teacher'], true) ? 'admin-surface' : 'app-surface' }}">
    @auth
        @if(in_array(auth()->user()->role, ['admin', 'teacher'], true))
            <div class="admin-page">
                <div class="admin-frame">
                    <aside class="admin-sidebar">
                        <a href="{{ route('dashboard') }}" class="admin-brand">
                            <span class="admin-brand-mark">SM</span>
                            <span>Student<br>Management</span>
                        </a>

                        <nav class="mt-10 space-y-1 text-sm">
                            @if(auth()->user()->role === 'admin')
                                <a class="admin-side-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                                    <span>Home</span>
                                </a>
                                <a class="admin-side-link admin-sub-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                                    <span>Admin</span>
                                </a>
                                <a class="admin-side-link admin-sub-link {{ request()->routeIs('admin.students.*') ? 'active' : '' }}" href="{{ route('admin.students.index') }}">
                                    <span>Students</span>
                                </a>
                                <a class="admin-side-link admin-sub-link {{ request()->routeIs('admin.teachers.*') ? 'active' : '' }}" href="{{ route('admin.teachers.index') }}">
                                    <span>Teachers</span>
                                </a>
                                <a class="admin-side-link {{ request()->routeIs('admin.students.*') ? 'active' : '' }}" href="{{ route('admin.students.index') }}">
                                    <span>Student Records</span>
                                </a>
                                <a class="admin-side-link {{ request()->routeIs('admin.teachers.*') ? 'active' : '' }}" href="{{ route('admin.teachers.index') }}">
                                    <span>Teachers</span>
                                </a>
                                <span class="admin-side-link muted">Library</span>
                                <span class="admin-side-link muted">Account</span>
                                <a class="admin-side-link {{ request()->routeIs('admin.classes.*') ? 'active' : '' }}" href="{{ route('admin.classes.index') }}">
                                    <span>Class</span>
                                </a>
                                <a class="admin-side-link {{ request()->routeIs('admin.subjects.*') ? 'active' : '' }}" href="{{ route('admin.subjects.index') }}">
                                    <span>Subject</span>
                                </a>
                                <span class="admin-side-link muted">Routine</span>
                                <span class="admin-side-link muted">Attendance</span>
                                <span class="admin-side-link muted">Exam</span>
                                <span class="admin-side-link muted">Notice</span>
                                <span class="admin-side-link muted">Transport</span>
                                <span class="admin-side-link muted">Hostel</span>
                            @else
                                <a class="admin-side-link {{ request()->routeIs('teacher.dashboard') ? 'active' : '' }}" href="{{ route('teacher.dashboard') }}">
                                    <span>Home</span>
                                </a>
                                <a class="admin-side-link admin-sub-link {{ request()->routeIs('teacher.dashboard') ? 'active' : '' }}" href="{{ route('teacher.dashboard') }}">
                                    <span>Teacher</span>
                                </a>
                                <a class="admin-side-link admin-sub-link {{ request()->routeIs('teacher.*') ? 'active' : '' }}" href="{{ route('teacher.dashboard') }}">
                                    <span>Classes</span>
                                </a>
                                <a class="admin-side-link {{ request()->routeIs('teacher.attendance') ? 'active' : '' }}" href="{{ route('teacher.dashboard') }}">
                                    <span>Attendance</span>
                                </a>
                                <a class="admin-side-link {{ request()->routeIs('teacher.scores') ? 'active' : '' }}" href="{{ route('teacher.dashboard') }}">
                                    <span>Scores</span>
                                </a>
                                <span class="admin-side-link muted">Student Progress</span>
                                <span class="admin-side-link muted">Routine</span>
                                <span class="admin-side-link muted">Exam</span>
                                <span class="admin-side-link muted">Notice</span>
                                <span class="admin-side-link muted">Account</span>
                            @endif
                        </nav>
                    </aside>

                    <section class="admin-workspace">
                        <header class="admin-topbar">
                            <div class="admin-search">
                                <span>What do you want to find?</span>
                                <span class="text-sm font-black text-[#dc4e98]">Q</span>
                            </div>

                            <div class="flex items-center gap-3">
                                <button class="admin-icon-btn" type="button" aria-label="Notifications">!</button>
                                <button class="admin-icon-btn" type="button" aria-label="Messages">...</button>
                                <div class="admin-profile">
                                    <div class="admin-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
                                    <div class="hidden sm:block">
                                        <p class="text-sm font-bold text-zinc-900">{{ auth()->user()->name }}</p>
                                        <p class="text-xs text-zinc-500">{{ ucfirst(auth()->user()->role) }}</p>
                                    </div>
                                </div>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button class="admin-logout" type="submit">Logout</button>
                                </form>
                            </div>
                        </header>

                        <main class="admin-content">
                            @if(session('status'))
                                <div class="mb-6 rounded-lg border border-[#d7e3ff] bg-[#f8faff] px-4 py-3 text-sm font-semibold text-[#4f79c9] shadow-sm">
                                    {{ session('status') }}
                                </div>
                            @endif

                            @if($errors->any())
                                <div class="mb-6 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800 shadow-sm">
                                    <p class="font-semibold">Please fix the following:</p>
                                    <ul class="mt-2 list-disc pl-5">
                                        @foreach($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            {{ $slot }}
                        </main>
                    </section>
                </div>
            </div>
        @else
            <div class="min-h-screen">
            <header class="app-header">
                <div class="mx-auto flex max-w-7xl flex-col gap-4 px-4 py-4 sm:px-6 lg:flex-row lg:items-center lg:justify-between lg:px-8">
                    <div class="flex items-center gap-3">
                        <a href="{{ route('dashboard') }}" class="brand-mark" aria-label="Student Management home">SM</a>
                        <div>
                            <a href="{{ route('dashboard') }}" class="text-lg font-black text-zinc-950">Student Management</a>
                            <p class="text-sm text-zinc-500">{{ ucfirst(auth()->user()->role) }} workspace</p>
                        </div>
                    </div>
                    <nav class="flex flex-wrap items-center gap-2 text-sm">
                        @if(auth()->user()->role === 'admin')
                            <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'bg-[#eef3ff] text-[#4f79c9]' : '' }}" href="{{ route('admin.dashboard') }}">Dashboard</a>
                            <a class="nav-link {{ request()->routeIs('admin.students.*') ? 'bg-[#eef3ff] text-[#4f79c9]' : '' }}" href="{{ route('admin.students.index') }}">Students</a>
                            <a class="nav-link {{ request()->routeIs('admin.teachers.*') ? 'bg-[#eef3ff] text-[#4f79c9]' : '' }}" href="{{ route('admin.teachers.index') }}">Teachers</a>
                        @elseif(auth()->user()->role === 'teacher')
                            <a class="nav-link {{ request()->routeIs('teacher.*') ? 'bg-[#eef3ff] text-[#4f79c9]' : '' }}" href="{{ route('teacher.dashboard') }}">Classes</a>
                        @else
                            <a class="nav-link {{ request()->routeIs('student.*') ? 'bg-[#eef3ff] text-[#4f79c9]' : '' }}" href="{{ route('student.dashboard') }}">My Progress</a>
                        @endif
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button class="btn-secondary" type="submit">Logout</button>
                        </form>
                    </nav>
                </div>
            </header>

            <main class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
                @if(session('status'))
                    <div class="mb-6 rounded-lg border border-[#d7e3ff] bg-[#f8faff] px-4 py-3 text-sm font-semibold text-[#4f79c9] shadow-sm">
                        {{ session('status') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="mb-6 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800 shadow-sm">
                        <p class="font-semibold">Please fix the following:</p>
                        <ul class="mt-2 list-disc pl-5">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{ $slot }}
            </main>
        </div>
        @endif
    @else
        <div class="min-h-screen">
            <main class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
                @if($errors->any())
                    <div class="mb-6 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800 shadow-sm">
                        <p class="font-semibold">Please fix the following:</p>
                        <ul class="mt-2 list-disc pl-5">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{ $slot }}
            </main>
        </div>
    @endauth
</body>
</html>
