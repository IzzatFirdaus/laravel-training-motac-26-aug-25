<form method="POST" action="{{ $action }}" class="d-inline" data-myds-form data-model="{{ $label }}">
    @csrf
    {{-- Button is type=button so JS can show a confirmation modal or prompt before submitting the form. --}}
    <button type="button" class="myds-btn myds-btn--danger myds-btn--sm ms-1" aria-label="Padam {{ $label }}">
        <span aria-hidden="true">Padam</span>
        <span class="visually-hidden">Padam {{ $label }}</span>
    </button>
</form>
