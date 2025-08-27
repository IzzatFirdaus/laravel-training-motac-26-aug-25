<form method="POST" action="{{ $action }}" class="d-inline destroy-form" data-myds-form>
    @csrf
    <button type="button" class="btn btn-sm btn-outline-danger ms-1 myds-btn myds-btn--danger" aria-label="Destroy {{ $label }}" onclick="window.MYDS && window.MYDS.handleDestroy ? window.MYDS.handleDestroy(this) : handleDestroy(this)">Padam</button>
</form>
