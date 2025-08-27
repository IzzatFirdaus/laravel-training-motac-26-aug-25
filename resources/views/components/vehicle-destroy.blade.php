<form method="POST" action="{{ $action }}" class="d-inline vehicle-destroy-form">
    @csrf
    <button type="button" class="btn btn-sm btn-outline-danger ms-1 myds-btn myds-btn--danger" aria-label="Padam {{ $label }}" onclick="window.MYDS && window.MYDS.handleDestroy ? window.MYDS.handleDestroy(this) : handleDestroy(this)">Padam</button>
</form>
