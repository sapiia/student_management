<x-layouts.app title="Edit Subject">
    <form class="panel mx-auto max-w-2xl space-y-5" method="POST" action="{{ route('admin.subjects.update', $subject) }}">
        @csrf
        @method('PUT')
        <div>
            <p class="page-kicker">Edit record</p>
            <h1 class="page-title">Edit subject</h1>
        </div>
        @include('subjects.partials.form', ['subject' => $subject])
        <div class="flex gap-3">
            <button class="btn-primary" type="submit">Save changes</button>
            <a class="btn-secondary" href="{{ route('admin.subjects.index') }}">Cancel</a>
        </div>
    </form>
</x-layouts.app>
