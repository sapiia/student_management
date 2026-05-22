<x-layouts.app title="Add Student">
    <form class="panel mx-auto max-w-2xl space-y-5" method="POST" action="{{ route('admin.students.store') }}">
        @csrf
        <div>
            <p class="page-kicker">New record</p>
            <h1 class="page-title">Add student</h1>
        </div>
        @include('students.partials.form', ['student' => null])
        <div class="flex gap-3">
            <button class="btn-primary" type="submit">Save student</button>
            <a class="btn-secondary" href="{{ route('admin.students.index') }}">Cancel</a>
        </div>
    </form>
</x-layouts.app>
