@props([
    'name',
    'type' => 'text',
    'label' => null,
    'value' => null,
    'required' => false,
    'placeholder' => '',
])

@php
    $label = $label ?? ucfirst(str_replace('_', ' ', $name));
    $id = $name;
    $errorId = $name . '-error';
    $hasError = $errors->has($name);
    $showValue = old($name, $value);
@endphp

<div class="mb-3">
    <label for="{{ $id }}" class="form-label">{{ $label }}@if($required) <span aria-hidden="true">*</span>@endif</label>

    @if($type === 'textarea')
        <textarea id="{{ $id }}" name="{{ $name }}" class="form-control @if($hasError) is-invalid @endif" aria-invalid="{{ $hasError ? 'true' : 'false' }}" @if($placeholder) placeholder="{{ $placeholder }}" @endif aria-describedby="{{ $hasError ? $errorId : '' }}">{{ $showValue }}</textarea>
    @else
        <input id="{{ $id }}" name="{{ $name }}" type="{{ $type }}" value="{{ $showValue }}" class="form-control @if($hasError) is-invalid @endif" aria-invalid="{{ $hasError ? 'true' : 'false' }}" @if($placeholder) placeholder="{{ $placeholder }}" @endif @if($required) required @endif>
    @endif

    @if($hasError)
        <div id="{{ $errorId }}" class="invalid-feedback">{{ $errors->first($name) }}</div>
    @endif
</div>
