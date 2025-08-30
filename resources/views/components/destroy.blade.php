@props(['action', 'label' => 'item'])

{{-- A small, accessible destructive form. Uses data-myds-form so JS can confirm before submit. --}}
<form method="POST" action="{{ $action }}" class="d-inline" data-myds-form data-model="{{ $label }}" role="group" aria-label="Padam {{ $label }}">
    @csrf
    @method('DELETE')
    <button
        type="submit"
        class="myds-btn myds-btn--danger myds-btn--sm"
        aria-label="Padam {{ $label }}"
    >
        <span aria-hidden="true">Padam</span>
        <span class="sr-only">Padam {{ $label }}</span>
    </button>
</form>
