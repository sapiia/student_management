<label class="form-label">Name
    <input class="form-input" type="text" name="name" value="{{ old('name', $student?->name) }}" required>
</label>
<label class="form-label">Email
    <input class="form-input" type="email" name="email" value="{{ old('email', $student?->user?->email) }}" required>
</label>
<div class="grid gap-4 sm:grid-cols-2">
    <label class="form-label">Password
        <input class="form-input" type="password" name="password" @if(! $student) required @endif>
    </label>
    <label class="form-label">Student number
        <input class="form-input" type="text" name="student_number" value="{{ old('student_number', $student?->student_number) }}">
    </label>
</div>
<div class="grid gap-4 sm:grid-cols-2">
    <label class="form-label">Class
        <select class="form-input" name="school_class_id">
            <option value="">Unassigned</option>
            @foreach($classes as $class)
                <option value="{{ $class->id }}" @selected(old('school_class_id', $student?->school_class_id) == $class->id)>{{ $class->name }}</option>
            @endforeach
        </select>
    </label>
    <label class="form-label">Guardian
        <input class="form-input" type="text" name="guardian_name" value="{{ old('guardian_name', $student?->guardian_name) }}">
    </label>
</div>
