<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? config('app.name', 'Student Management') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="{{ auth()->check() && auth()->user()->role === 'admin' ? 'admin-surface' : (auth()->check() && auth()->user()->role === 'teacher' ? 'teacher-surface' : 'app-surface') }}">
    @auth
        @if(auth()->user()->role === 'admin')
            <div class="admin-shell">
                <aside class="admin-edupulse-sidebar">
                    <div class="admin-edupulse-brand">
                        <h1>Main Academy</h1>
                        <p>Admin Portal</p>
                    </div>

                    <nav class="admin-edupulse-nav custom-scrollbar">
                        <a class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                            <span class="material-symbols-outlined">dashboard</span>
                            <span>Dashboard</span>
                        </a>
                        <a class="{{ request()->routeIs('admin.students.*') ? 'active' : '' }}" href="{{ route('admin.students.index') }}">
                            <span class="material-symbols-outlined">group</span>
                            <span>Students</span>
                        </a>
                        <a class="{{ request()->routeIs('admin.classes.*') ? 'active' : '' }}" href="{{ route('admin.classes.index') }}">
                            <span class="material-symbols-outlined">class</span>
                            <span>Classes</span>
                        </a>
                        <a class="{{ request()->routeIs('admin.subjects.*') ? 'active' : '' }}" href="{{ route('admin.subjects.index') }}">
                            <span class="material-symbols-outlined">calendar_today</span>
                            <span>Schedules</span>
                        </a>
                        <a class="{{ request()->routeIs('admin.scores') ? 'active' : '' }}" href="{{ route('admin.scores') }}">
                            <span class="material-symbols-outlined">analytics</span>
                            <span>Reports</span>
                        </a>
                        <a class="{{ request()->routeIs('admin.teachers.*') ? 'active' : '' }}" href="{{ route('admin.teachers.index') }}">
                            <span class="material-symbols-outlined">supervisor_account</span>
                            <span>Teachers</span>
                        </a>
                    </nav>

                    <div class="admin-edupulse-footer">
                        <a class="admin-invite-link" href="{{ route('admin.teachers.create') }}">
                            <span class="material-symbols-outlined">person_add</span>
                            <span>Invite New Admin</span>
                        </a>
                        <a href="{{ route('dashboard') }}">
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

                <section class="admin-edupulse-main">
                    <main class="admin-edupulse-content custom-scrollbar">
                        @if(session('status'))
                            <div class="mb-6 rounded-lg border border-[#c9c4d4] bg-white px-4 py-3 text-sm font-semibold text-[#432d97] shadow-sm">
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
        @elseif(auth()->user()->role === 'teacher')
            <div class="teacher-shell">
                <aside class="teacher-sidebar">
                    <div class="teacher-brand">
                        <h1>Main Academy</h1>
                        <p>Teacher Portal</p>
                    </div>

                    <nav class="teacher-nav custom-scrollbar">
                        <a class="{{ request()->routeIs('teacher.dashboard') ? 'active' : '' }}" href="{{ route('teacher.dashboard') }}">
                            <span class="material-symbols-outlined">dashboard</span>
                            <span>Dashboard</span>
                        </a>
                        <a class="{{ request()->routeIs('teacher.attendance') ? 'active' : '' }}" href="{{ $teacherPrimaryAssignment ? route('teacher.attendance', $teacherPrimaryAssignment) : route('teacher.dashboard') }}">
                            <span class="material-symbols-outlined">event_available</span>
                            <span>Attendance</span>
                        </a>
                        <a class="{{ request()->routeIs('teacher.scores') ? 'active' : '' }}" href="{{ $teacherPrimaryAssignment ? route('teacher.scores', $teacherPrimaryAssignment) : route('teacher.dashboard') }}">
                            <span class="material-symbols-outlined">analytics</span>
                            <span>Scores</span>
                        </a>
                        <a href="{{ route('teacher.dashboard') }}#teacher-students">
                            <span class="material-symbols-outlined">group</span>
                            <span>Students</span>
                        </a>
                        <a href="{{ route('teacher.dashboard') }}#teacher-classes">
                            <span class="material-symbols-outlined">class</span>
                            <span>Classes</span>
                        </a>
                    </nav>

                    <div class="teacher-sidebar-footer">
                        <a class="teacher-primary-link" href="{{ $teacherPrimaryAssignment ? route('teacher.scores', $teacherPrimaryAssignment) : route('teacher.dashboard') }}">
                            <span class="material-symbols-outlined">add</span>
                            <span>Add New Record</span>
                        </a>
                        <a href="{{ route('dashboard') }}">
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

                <section class="teacher-main">
                    <header class="teacher-topbar">
                        <div>
                            <p>EduPulse SMS</p>
                        </div>
                        <div class="teacher-top-actions">
                            <label class="teacher-search">
                                <span class="material-symbols-outlined">search</span>
                                <input type="search" placeholder="Search students, classes, or reports...">
                            </label>
                            <button type="button" aria-label="Notifications">
                                <span class="material-symbols-outlined">notifications</span>
                            </button>
                            <button type="button" aria-label="Settings">
                                <span class="material-symbols-outlined">settings</span>
                            </button>
                            <div class="teacher-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
                        </div>
                    </header>

                    <main class="teacher-content custom-scrollbar">
                        @if(session('status'))
                            <div class="mb-6 rounded-lg border border-[#c9c4d4] bg-white px-4 py-3 text-sm font-semibold text-[#432d97] shadow-sm">
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
        @elseif(auth()->user()->role === 'student')
            <div class="student-surface min-h-screen">
                @if(session('status'))
                    <div class="fixed left-1/2 top-4 z-[70] -translate-x-1/2 rounded-lg border border-[#c9c4d4] bg-white px-4 py-3 text-sm font-semibold text-[#432d97] shadow-lg">
                        {{ session('status') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="fixed left-1/2 top-4 z-[70] w-[min(92vw,520px)] -translate-x-1/2 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800 shadow-lg">
                        <p class="font-semibold">Please fix the following:</p>
                        <ul class="mt-2 list-disc pl-5">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{ $slot }}
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
