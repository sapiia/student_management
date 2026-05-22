<x-layouts.app title="Add Class">
    <form class="panel mx-auto max-w-2xl space-y-5" method="POST" action="{{ route('admin.classes.store') }}">
        @csrf
        <div>
            <p class="page-kicker">New record</p>
            <h1 class="page-title">Add class</h1>
        </div>
        @include('classes.partials.form', ['class' => null])
        <div class="flex gap-3">
            <button class="btn-primary" type="submit">Save class</button>
            <a class="btn-secondary" href="{{ route('admin.classes.index') }}">Cancel</a>
        </div>
    </form>
</x-layouts.app>
