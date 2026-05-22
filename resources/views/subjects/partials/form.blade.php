<label class="form-label">Name
    <input class="form-input" type="text" name="name" value="{{ old('name', $subject?->name) }}" required>
</label>
<label class="form-label">Code
    <input class="form-input" type="text" name="code" value="{{ old('code', $subject?->code) }}">
</label>
