@props(['action', 'label' => 'vehicle'])

{{-- Vehicle-specific destroy form; consistent with MYDS patterns --}}
<form method="POST" action="{{ $action }}" class="d-inline" data-myds-form data-model="{{ $label }}" role="group" aria-label="Padam {{ $label }}">
    @csrf
    <button type="submit" class="myds-btn myds-btn--danger myds-btn--sm" aria-label="Padam {{ $label }}">
        Padam
        <span class="sr-only">Padam {{ $label }}</span>
    </button>
</form>
