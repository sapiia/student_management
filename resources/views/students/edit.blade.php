<x-layouts.app title="Edit Student">
    <form class="panel mx-auto max-w-2xl space-y-5" method="POST" action="{{ route('admin.students.update', $student) }}">
        @csrf
        @method('PUT')
        <div>
            <p class="page-kicker">Student record</p>
            <h1 class="page-title">Edit student</h1>
        </div>
        @include('students.partials.form', ['student' => $student])
        <div class="flex gap-3">
            <button class="btn-primary" type="submit">Update student</button>
            <a class="btn-secondary" href="{{ route('admin.students.index') }}">Cancel</a>
        </div>
    </form>
</x-layouts.app>
