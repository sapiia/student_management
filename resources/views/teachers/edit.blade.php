<x-layouts.app title="Edit Teacher">
    <form class="panel mx-auto max-w-xl space-y-5" method="POST" action="{{ route('admin.teachers.update', $teacher) }}">
        @csrf
        @method('PUT')
        <div>
            <p class="page-kicker">Faculty record</p>
            <h1 class="page-title">Edit teacher</h1>
        </div>
        @include('teachers.partials.form', ['teacher' => $teacher])
        <div class="flex gap-3">
            <button class="btn-primary" type="submit">Update teacher</button>
            <a class="btn-secondary" href="{{ route('admin.teachers.index') }}">Cancel</a>
        </div>
    </form>
</x-layouts.app>
