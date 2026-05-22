<x-layouts.app title="Edit Class">
    <form class="panel mx-auto max-w-2xl space-y-5" method="POST" action="{{ route('admin.classes.update', $class) }}">
        @csrf
        @method('PUT')
        <div>
            <p class="page-kicker">Edit record</p>
            <h1 class="page-title">Edit class</h1>
        </div>
        @include('classes.partials.form', ['class' => $class])
        <div class="flex gap-3">
            <button class="btn-primary" type="submit">Save changes</button>
            <a class="btn-secondary" href="{{ route('admin.classes.index') }}">Cancel</a>
        </div>
    </form>
</x-layouts.app>
