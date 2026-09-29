@csrf
<div class="mb-3">
    <label for="code" class="form-label">Course code</label>
    <input type="text" id="code" name="code" value="{{ old('code', $course->code) }}" class="form-control text-uppercase @error('code') is-invalid @enderror" required maxlength="20" placeholder="CS101">
    @error('code')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>
<div class="mb-3">
    <label for="name" class="form-label">Name</label>
    <input type="text" id="name" name="name" value="{{ old('name', $course->name) }}" class="form-control @error('name') is-invalid @enderror" required maxlength="255">
    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>
<div class="mb-4">
    <label for="description" class="form-label">Description <span class="text-muted">(optional)</span></label>
    <textarea id="description" name="description" rows="3" class="form-control @error('description') is-invalid @enderror" maxlength="2000">{{ old('description', $course->description) }}</textarea>
    @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>
