@props([
    'name',
    'type' => 'text',
    'label' => null,
    'value' => null,
    'required' => false,
    'placeholder' => '',
    'help' => null,
    'maxlength' => null,
    'min' => null,
    'max' => null,
    'step' => null,
])

@php
    // Deterministic IDs for accessibility (MyGOVEA principle: clear structure)
    $labelText = $label ?? ucfirst(str_replace('_', ' ', $name));
    $id = $name;
    $errorId = $name . '-error';
    $hasError = $errors->has($name);
    $showValue = old($name, $value);
    $helpId = $help ? $name . '-help' : null;

    // Build describedby for proper accessibility linking
    $describedBy = collect([$helpId, $hasError ? $errorId : null])->filter()->implode(' ');
@endphp

<div class="myds-form-group" data-field="{{ $name }}" data-myds-field>
    <label for="{{ $id }}" class="myds-label font-medium d-block mb-2" data-field-label="{{ $labelText }}">
        {{ $labelText }}
        @if($required)
            <span aria-hidden="true" class="myds-text--danger ms-1">*</span>
            <span class="sr-only"> (medan wajib)</span>
        @endif
    </label>

    @if($type === 'textarea')
        <textarea
            id="{{ $id }}"
            name="{{ $name }}"
            class="myds-input myds-textarea {{ $hasError ? 'is-invalid myds-input--error' : '' }} myds-tap-target"
            aria-invalid="{{ $hasError ? 'true' : 'false' }}"
            @if($describedBy) aria-describedby="{{ $describedBy }}" @endif
            @if($placeholder) placeholder="{{ $placeholder }}" data-placeholder="{{ $placeholder }}" @endif
            @if($required) required aria-required="true" @endif
            @if($maxlength) maxlength="{{ $maxlength }}" @endif
            rows="4"
            data-myds-textarea>{{ $showValue }}</textarea>
    @elseif($type === 'select')
        <select
            id="{{ $id }}"
            name="{{ $name }}"
            class="myds-input myds-select {{ $hasError ? 'is-invalid myds-input--error' : '' }} myds-tap-target"
            aria-invalid="{{ $hasError ? 'true' : 'false' }}"
            @if($describedBy) aria-describedby="{{ $describedBy }}" @endif
            @if($required) required aria-required="true" @endif
            data-myds-select>
            {{ $slot }}
        </select>
    @else
        <input
            id="{{ $id }}"
            name="{{ $name }}"
            type="{{ $type }}"
            value="{{ $showValue }}"
            class="myds-input {{ $hasError ? 'is-invalid myds-input--error' : '' }} myds-tap-target"
            aria-invalid="{{ $hasError ? 'true' : 'false' }}"
            @if($describedBy) aria-describedby="{{ $describedBy }}" @endif
            @if($placeholder) placeholder="{{ $placeholder }}" data-placeholder="{{ $placeholder }}" @endif
            @if($required) required aria-required="true" @endif
            @if($maxlength) maxlength="{{ $maxlength }}" @endif
            @if($min) min="{{ $min }}" @endif
            @if($max) max="{{ $max }}" @endif
            @if($step) step="{{ $step }}" @endif
            @if($type === 'email') autocomplete="email" @endif
            @if($type === 'password') autocomplete="current-password" @endif
            data-myds-input="{{$type}}">
    @endif

    @if($help)
        <div id="{{ $helpId }}" class="myds-body-xs myds-text--muted mt-1" data-field-help="{{ $help }}">
            {{ $help }}
        </div>
    @endif

    @if($hasError)
        <div id="{{ $errorId }}"
             class="d-flex align-items-start myds-text--danger myds-body-xs mt-2"
             role="alert"
             aria-live="polite"
             data-field-error="{{ $errors->first($name) }}">
            <i class="bi bi-x-circle me-1 mt-0.5 flex-shrink-0" aria-hidden="true"></i>
            <span>{{ $errors->first($name) }}</span>
        </div>
    @endif
</div>
