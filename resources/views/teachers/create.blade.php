<x-layouts.app title="Add Teacher">
    <form class="panel mx-auto max-w-xl space-y-5" method="POST" action="{{ route('admin.teachers.store') }}">
        @csrf
        <div>
            <p class="page-kicker">New faculty</p>
            <h1 class="page-title">Add teacher</h1>
        </div>
        @include('teachers.partials.form', ['teacher' => null])
        <div class="flex gap-3">
            <button class="btn-primary" type="submit">Save teacher</button>
            <a class="btn-secondary" href="{{ route('admin.teachers.index') }}">Cancel</a>
        </div>
    </form>
</x-layouts.app>
