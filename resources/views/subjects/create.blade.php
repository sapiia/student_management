<x-layouts.app title="Add Subject">
    <form class="panel mx-auto max-w-2xl space-y-5" method="POST" action="{{ route('admin.subjects.store') }}">
        @csrf
        <div>
            <p class="page-kicker">New record</p>
            <h1 class="page-title">Add subject</h1>
        </div>
        @include('subjects.partials.form', ['subject' => null])
        <div class="flex gap-3">
            <button class="btn-primary" type="submit">Save subject</button>
            <a class="btn-secondary" href="{{ route('admin.subjects.index') }}">Cancel</a>
        </div>
    </form>
</x-layouts.app>
