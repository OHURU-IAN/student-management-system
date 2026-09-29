<form method="POST" action="{{ $action }}" class="d-inline" data-confirm="Delete {{ $label }}? This cannot be undone." onsubmit="return confirm(this.dataset.confirm);">
    @csrf
    @method('DELETE')
    <button class="btn {{ $size ?? 'btn-sm' }} btn-outline-danger">Delete</button>
</form>
