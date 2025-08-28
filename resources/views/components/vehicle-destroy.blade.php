<form method="POST" action="{{ $action }}" class="d-inline" data-myds-form>
    @csrf
    <button type="button" class="myds-btn myds-btn--danger myds-btn--sm ms-1" aria-label="Padam {{ $label }}">Padam</button>
</form>
