@csrf
<div class="mb-3">
    <label for="name" class="form-label">Full name</label>
    <input type="text" id="name" name="name" value="{{ old('name', $student->name) }}" class="form-control @error('name') is-invalid @enderror" required maxlength="255">
    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>
<div class="mb-3">
    <label for="email" class="form-label">Email <span class="text-muted">(optional)</span></label>
    <input type="email" id="email" name="email" value="{{ old('email', $student->email) }}" class="form-control @error('email') is-invalid @enderror" maxlength="255">
    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>
<div class="mb-3">
    <label for="address" class="form-label">Address</label>
    <input type="text" id="address" name="address" value="{{ old('address', $student->address) }}" class="form-control @error('address') is-invalid @enderror" required maxlength="255">
    @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>
<div class="mb-3">
    <label for="mobile" class="form-label">Mobile</label>
    <input type="tel" id="mobile" name="mobile" value="{{ old('mobile', $student->mobile) }}" class="form-control @error('mobile') is-invalid @enderror" required placeholder="+254 712 345 678">
    @error('mobile')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>
<div class="mb-4">
    <label for="course_id" class="form-label">Course</label>
    <select id="course_id" name="course_id" class="form-select @error('course_id') is-invalid @enderror">
        <option value="">Unassigned</option>
        @foreach ($courses as $course)
            <option value="{{ $course->id }}" @selected(old('course_id', $student->course_id) == $course->id)>{{ $course->code }} - {{ $course->name }}</option>
        @endforeach
    </select>
    @error('course_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>
