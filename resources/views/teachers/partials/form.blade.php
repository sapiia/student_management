<label class="form-label">Name
    <input class="form-input" type="text" name="name" value="{{ old('name', $teacher?->name) }}" required>
</label>
<label class="form-label">Email
    <input class="form-input" type="email" name="email" value="{{ old('email', $teacher?->email) }}" required>
</label>
<label class="form-label">Password
    <input class="form-input" type="password" name="password" @if(! $teacher) required @endif>
</label>
