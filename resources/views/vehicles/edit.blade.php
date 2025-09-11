@php
use Illuminate\Support\Facades\Auth;
@endphp

@extends('layouts.app')

@section('title', __('ui.vehicles.edit_title', ['app' => config('app.name', 'Sistem Kerajaan')]))

@section('content')
<main id="main-content" class="myds-container py-4" role="main" tabindex="-1" aria-labelledby="page-heading">

    {{-- Breadcrumb Navigation (MyGOVEA clear navigation principle) --}}
    <nav aria-label="Breadcrumb" class="mb-4">
        <ol class="d-flex list-unstyled myds-text--muted myds-body-sm m-0 p-0">
            <li><a href="{{ route('vehicles.index') }}" class="text-primary text-decoration-none" data-nav="vehicles">{{ __('ui.vehicles.breadcrumb_label') }}</a></li>
            <li class="mx-2" aria-hidden="true">/</li>
            <li><a href="{{ route('vehicles.show', $vehicle) }}" class="text-primary text-decoration-none" data-nav="vehicle-show">{{ e($vehicle->name) }}</a></li>
            <li class="mx-2" aria-hidden="true">/</li>
            <li aria-current="page" class="myds-text--muted">{{ __('ui.vehicles.edit_heading') }}</li>
        </ol>
    </nav>

    <header class="mb-6" role="banner">
        <h1 id="page-heading" class="myds-heading-md font-heading font-semibold mb-2">{{ __('ui.vehicles.edit_heading') }}</h1>
        <p class="myds-body-sm myds-text--muted mb-0">{{ __('ui.vehicles.edit_description') }}</p>
    </header>

    {{-- Form Container with MYDS Grid --}}
    <div class="myds-grid myds-grid-desktop myds-grid-tablet myds-grid-mobile">
        <div class="mobile:col-span-4 tablet:col-span-8 desktop:col-span-8">

            {{-- Status Message --}}
            @if (session('status'))
                <div class="myds-alert myds-alert--success d-flex align-items-start mb-4" role="alert" aria-live="polite">
                    <i class="bi bi-check-circle me-2 flex-shrink-0" aria-hidden="true"></i>
                    <div>
                        <h4 class="myds-body-md font-medium">{{ __('ui.form.success_heading') }}</h4>
                        <p class="mb-0 myds-body-sm">{{ session('status') }}</p>
                    </div>
                </div>
            @endif

            {{-- Main Form Card --}}
            <div class="bg-surface border rounded-m p-4 shadow-sm" data-myds-card="vehicle-edit">
                <form method="POST" action="{{ route('vehicles.update', $vehicle->id) }}" novalidate aria-labelledby="form-title" data-myds-form="vehicle-edit">
                    @csrf
                    @method('PUT')

                    <h2 id="form-title" class="sr-only">{{ __('ui.vehicles.form_title') }}</h2>

                    {{-- Name Field --}}
                    <div class="mb-4" data-field="name">
                        <label for="name" class="myds-label myds-body-sm font-medium d-block mb-2" data-field-label="{{ __('ui.vehicles.name_label') }}">
                            {{ __('ui.vehicles.name_label') }}
                            <span class="myds-text--danger ms-1" aria-hidden="true">*</span>
                            <span class="sr-only"> medan wajib</span>
                        </label>
                        <input type="text"
                               id="name"
                               name="name"
                               class="myds-input myds-tap-target @error('name') is-invalid myds-input--error @enderror"
                               value="{{ old('name', $vehicle->name) }}"
                               aria-describedby="name-help @error('name') name-error @enderror"
                               aria-required="true"
                               maxlength="255"
                               data-myds-input="text"
                               data-required="true"
                               required>
                        <div id="name-help" class="myds-body-xs myds-text--muted mt-1" data-field-help="{{ __('ui.vehicles.name_help') }}">{{ __('ui.vehicles.name_help') }}</div>
                        @error('name')
                            <div id="name-error" class="d-flex align-items-start myds-text--danger myds-body-xs mt-2" role="alert" data-field-error="{{ $message }}">
                                <i class="bi bi-x-circle me-1 mt-0.5 flex-shrink-0" aria-hidden="true"></i>
                                <span>{{ $message }}</span>
                            </div>
                        @enderror
                    </div>

                    {{-- Owner Field --}}
                    <div class="mb-4" data-field="user_id">
                        <label for="user_id" class="myds-label myds-body-sm font-medium d-block mb-2" data-field-label="{{ __('ui.vehicles.owner_label') }}">{{ __('ui.vehicles.owner_label') }}</label>
                        @if(Auth::check() && Auth::user()->hasRole('admin'))
                            <select id="user_id"
                                    name="user_id"
                                    class="myds-input myds-tap-target @error('user_id') is-invalid myds-input--error @enderror"
                                    aria-describedby="user_id-help @error('user_id') user_id-error @enderror"
                                    data-myds-select="owner">
                                <option value="">{{ __('ui.vehicles.none_owner') }}</option>
                                @foreach(($users ?? collect()) as $user)
                                    <option value="{{ $user->id }}" {{ (string) old('user_id', $vehicle->user_id) === (string) $user->id ? 'selected' : '' }}>
                                        {{ e($user->name) }}
                                    </option>
                                @endforeach
                            </select>
                            <div id="user_id-help" class="myds-body-xs myds-text--muted mt-1" data-field-help="{{ __('ui.vehicles.owner_help') }}">{{ __('ui.vehicles.owner_help') }}</div>
                        @else
                            <input type="hidden" name="user_id" value="{{ $vehicle->user_id ?? '' }}">
                            <div class="myds-input bg-muted cursor-not-allowed" role="textbox" aria-readonly="true" tabindex="-1">
                                {{ $vehicle->user?->name ?? __('ui.vehicles.none_owner') }}
                            </div>
                            <div id="user_id-help" class="myds-body-xs myds-text--muted mt-1" data-field-help="{{ __('ui.vehicles.owner_help') }}">{{ __('ui.vehicles.owner_readonly_help') }}</div>
                        @endif
                        @error('user_id')
                            <div id="user_id-error" class="d-flex align-items-start myds-text--danger myds-body-xs mt-2" role="alert" data-field-error="{{ $message }}">
                                <i class="bi bi-x-circle me-1 mt-0.5 flex-shrink-0" aria-hidden="true"></i>
                                <span>{{ $message }}</span>
                            </div>
                        @enderror
                    </div>

                    {{-- Quantity Field --}}
                    <div class="mb-4" data-field="qty">
                        <label for="qty" class="myds-label myds-body-sm font-medium d-block mb-2" data-field-label="{{ __('ui.vehicles.qty_label') }}">{{ __('ui.vehicles.qty_label') }}</label>
                        <input type="number"
                               id="qty"
                               name="qty"
                               class="myds-input myds-tap-target @error('qty') is-invalid myds-input--error @enderror"
                               value="{{ old('qty', $vehicle->qty ?? 1) }}"
                               min="1"
                               aria-describedby="qty-help @error('qty') qty-error @enderror"
                               data-myds-input="number">
                        <div id="qty-help" class="myds-body-xs myds-text--muted mt-1" data-field-help="{{ __('ui.vehicles.qty_help') }}">{{ __('ui.vehicles.qty_help') }}</div>
                        @error('qty')
                            <div id="qty-error" class="d-flex align-items-start myds-text--danger myds-body-xs mt-2" role="alert" data-field-error="{{ $message }}">
                                <i class="bi bi-x-circle me-1 mt-0.5 flex-shrink-0" aria-hidden="true"></i>
                                <span>{{ $message }}</span>
                            </div>
                        @enderror
                    </div>

                    {{-- Price Field --}}
                    <div class="mb-4" data-field="price">
                        <label for="price" class="myds-label myds-body-sm font-medium d-block mb-2" data-field-label="{{ __('ui.vehicles.price_label') }}">{{ __('ui.vehicles.price_label') }}</label>
                        <div class="myds-input-group">
                            <span class="myds-input-group__text bg-muted" aria-hidden="true">RM</span>
                            <input type="number"
                                   id="price"
                                   name="price"
                                   class="myds-input myds-tap-target @error('price') is-invalid myds-input--error @enderror"
                                   value="{{ old('price', $vehicle->price ?? '') }}"
                                   step="0.01"
                                   min="0"
                                   aria-describedby="price-help @error('price') price-error @enderror"
                                   data-myds-input="price">
                        </div>
                        <div id="price-help" class="myds-body-xs myds-text--muted mt-1" data-field-help="{{ __('ui.vehicles.price_help') }}">{{ __('ui.vehicles.price_help') }}</div>
                        @error('price')
                            <div id="price-error" class="d-flex align-items-start myds-text--danger myds-body-xs mt-2" role="alert" data-field-error="{{ $message }}">
                                <i class="bi bi-x-circle me-1 mt-0.5 flex-shrink-0" aria-hidden="true"></i>
                                <span>{{ $message }}</span>
                            </div>
                        @enderror
                    </div>

                    {{-- Description Field --}}
                    <div class="mb-6" data-field="description">
                        <label for="description" class="myds-label myds-body-sm font-medium d-block mb-2" data-field-label="{{ __('ui.vehicles.description_label') }}">{{ __('ui.vehicles.description_label') }}</label>
                        <textarea id="description"
                                  name="description"
                                  class="myds-input myds-textarea myds-tap-target @error('description') is-invalid myds-input--error @enderror"
                                  rows="4"
                                  aria-describedby="description-help @error('description') description-error @enderror"
                                  maxlength="1000"
                                  data-myds-textarea>{{ old('description', $vehicle->description) }}</textarea>
                        <div id="description-help" class="myds-body-xs myds-text--muted mt-1" data-field-help="{{ __('ui.vehicles.description_help') }}">{{ __('ui.vehicles.description_help') }}</div>
                        @error('description')
                            <div id="description-error" class="d-flex align-items-start myds-text--danger myds-body-xs mt-2" role="alert" data-field-error="{{ $message }}">
                                <i class="bi bi-x-circle me-1 mt-0.5 flex-shrink-0" aria-hidden="true"></i>
                                <span>{{ $message }}</span>
                            </div>
                        @enderror
                    </div>

                    {{-- Form Actions --}}
                    <div class="d-flex flex-column flex-sm-row gap-3 justify-content-end" data-myds-actions>
                        <a href="{{ route('vehicles.show', $vehicle) }}" class="myds-btn myds-btn--secondary myds-tap-target" aria-label="{{ __('ui.cancel') }}" data-nav="cancel">
                            <i class="bi bi-arrow-left me-2" aria-hidden="true"></i>
                            {{ __('ui.cancel') }}
                        </a>
                        <button type="submit" class="myds-btn myds-btn--primary myds-tap-target" aria-label="{{ __('ui.buttons.update_vehicle') }}" data-action="update">
                            <i class="bi bi-save me-2" aria-hidden="true"></i>
                            {{ __('ui.buttons.update_vehicle') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Help Sidebar (MyGOVEA user assistance principle) --}}
        <div class="mobile:col-span-4 tablet:col-span-8 desktop:col-span-4 mt-4 desktop:mt-0">
            <div class="bg-muted border rounded-m p-4" data-myds-sidebar="help">
                <h3 class="myds-heading-xs font-heading font-medium mb-3">{{ __('ui.help.edit_heading') }}</h3>
                <div class="space-y-3">
                    <div>
                        <h4 class="myds-body-sm font-medium mb-1">{{ __('ui.help.tips_heading') }}</h4>
                        <ul class="myds-body-xs myds-text--muted space-y-1">
                            <li>{{ __('ui.vehicles.help.edit_tip_1') }}</li>
                            <li>{{ __('ui.vehicles.help.edit_tip_2') }}</li>
                            <li>{{ __('ui.vehicles.help.edit_tip_3') }}</li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="myds-body-sm font-medium mb-1">{{ __('ui.help.support_heading') }}</h4>
                        <p class="myds-body-xs myds-text--muted">{!! __('ui.help.support_contact', ['contact' => '<a href="mailto:support@jdn.gov.my" class="text-primary">support@jdn.gov.my</a>']) !!}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection
