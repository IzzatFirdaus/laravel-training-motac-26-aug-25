<form method="POST" action="{{ $action }}" class="d-inline destroy-form">
    @csrf
    <button type="button" class="btn btn-sm btn-outline-danger ms-1" aria-label="Destroy {{ $label }}" onclick="window.MYDS && window.MYDS.handleDestroy ? window.MYDS.handleDestroy(this) : handleDestroy(this)">Delete</button>
</form>
