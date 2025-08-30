@props([
    'name',
    'type' => 'text',
    'label' => null,
    'value' => null,
    'required' => false,
    'placeholder' => '',
])

@php
    $labelText = $label ?? ucfirst(str_replace('_', ' ', $name));
    $id = $name;
    $errorId = $name . '-error';
    $hasError = $errors->has($name);
    $showValue = old($name, $value);
@endphp

<div class="myds-form-group">
    <label for="{{ $id }}" class="myds-label font-heading">
        {{ $labelText }}
        @if($required)
            <span aria-hidden="true" class="myds-text--danger">*</span>
            <span class="sr-only"> (wajib diisi)</span>
        @endif
    </label>

    @if($type === 'textarea')
        <textarea
            id="{{ $id }}"
            name="{{ $name }}"
            class="myds-input myds-textarea {{ $hasError ? 'is-invalid' : '' }}"
            aria-invalid="{{ $hasError ? 'true' : 'false' }}"
            @if($hasError) aria-describedby="{{ $errorId }}" @endif
            @if($placeholder) placeholder="{{ __($placeholder) }}" @endif
        >{{ $showValue }}</textarea>
    @else
        <input
            id="{{ $id }}"
            name="{{ $name }}"
            type="{{ $type }}"
            value="{{ $showValue }}"
            class="myds-input {{ $hasError ? 'is-invalid' : '' }}"
            aria-invalid="{{ $hasError ? 'true' : 'false' }}"
            @if($hasError) aria-describedby="{{ $errorId }}" @endif
            @if($placeholder) placeholder="{{ __($placeholder) }}" @endif
            @if($required) required aria-required="true" @endif
        >
    @endif

    @if($hasError)
        <div id="{{ $errorId }}" class="myds-field-error" role="alert">
            {{ $errors->first($name) }}
        </div>
    @endif
</div>
