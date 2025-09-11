@php
use Illuminate\Support\Facades\Auth;
@endphp

@extends('layouts.app')

@section('title', __('ui.vehicles.create_title', ['app' => config('app.name', 'Sistem Kerajaan')]))

@section('content')
<main id="main-content" class="myds-container py-4" role="main" tabindex="-1" aria-labelledby="page-heading">

    {{-- Breadcrumb Navigation (MyGOVEA clear navigation principle) --}}
    <nav aria-label="Breadcrumb" class="mb-4">
        <ol class="d-flex list-unstyled myds-text--muted myds-body-sm m-0 p-0">
            <li><a href="{{ route('vehicles.index') }}" class="text-primary text-decoration-none" data-nav="vehicles">{{ __('ui.vehicles.breadcrumb_label') }}</a></li>
            <li class="mx-2" aria-hidden="true">/</li>
            <li aria-current="page" class="myds-text--muted">{{ __('ui.vehicles.create_heading') }}</li>
        </ol>
    </nav>

    {{-- MYDS Grid Layout --}}
    <div class="myds-grid myds-grid-desktop myds-grid-tablet myds-grid-mobile">
        <div class="mobile:col-span-4 tablet:col-span-8 desktop:col-span-8">

            <header class="mb-6" role="banner">
                <h1 id="page-heading" class="myds-heading-md font-heading font-semibold mb-2">{{ __('ui.vehicles.create_heading') }}</h1>
                <p class="myds-body-sm myds-text--muted mb-0">{{ __('ui.vehicles.create_description_short') }}</p>
            </header>

            {{-- Status Messages --}}
            @if(session('success'))
                <div class="myds-alert myds-alert--success d-flex align-items-start mb-4" role="alert" aria-live="polite">
                    <i class="bi bi-check-circle me-2 flex-shrink-0" aria-hidden="true"></i>
                    <div>
                        <h4 class="myds-body-md font-medium">{{ __('ui.form.success_heading') }}</h4>
                        <p class="mb-0 myds-body-sm">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            @if($errors->any())
                <div class="myds-alert myds-alert--danger d-flex align-items-start mb-4" role="alert" aria-live="polite">
                    <i class="bi bi-x-circle me-2 flex-shrink-0" aria-hidden="true"></i>
                    <div>
                        <h4 class="myds-body-md font-medium">{{ __('ui.form.error_heading') }}</h4>
                        <ul class="mb-0 myds-body-sm mt-2">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            {{-- Main Form Card --}}
            <div class="bg-surface border rounded-m p-4 shadow-sm" data-myds-card="vehicle-store">
                <form method="POST" action="{{ route('vehicles.store') }}" novalidate data-myds-form="vehicle-store">
                    @csrf

                    {{-- Name Field --}}
                    <x-form-field name="name"
                                  label="{{ __('ui.vehicles.name_label') }}"
                                  :value="old('name')"
                                  required
                                  maxlength="255"
                                  placeholder="{{ __('ui.vehicles.name_placeholder') }}"
                                  help="{{ __('ui.vehicles.name_help') }}" />

                    {{-- Owner Field --}}
                    <div class="mb-4" data-field="user_id">
                        <label for="user_id" class="myds-label myds-body-sm font-medium d-block mb-2" data-field-label="{{ __('ui.vehicles.owner_label') }}">
                            {{ __('ui.vehicles.owner_label') }}
                            <span class="myds-body-xs myds-text--muted ms-1">({{ __('ui.optional') }})</span>
                        </label>
                        @if(Auth::check() && Auth::user()->hasRole('admin'))
                            <select id="user_id"
                                    name="user_id"
                                    class="myds-input myds-tap-target @error('user_id') is-invalid myds-input--error @enderror"
                                    aria-describedby="user_id-help @error('user_id') user_id-error @enderror"
                                    data-myds-select="owner">
                                <option value="">{{ __('ui.vehicles.none_owner') }}</option>
                                @foreach(($users ?? collect()) as $user)
                                    <option value="{{ $user->id }}" @selected((string) old('user_id') === (string) $user->id)>{{ e($user->name) }}</option>
                                @endforeach
                            </select>
                            <div id="user_id-help" class="myds-body-xs myds-text--muted mt-1" data-field-help="{{ __('ui.vehicles.owner_help') }}">{{ __('ui.vehicles.owner_help') }}</div>
                        @else
                            <input type="hidden" name="user_id" value="{{ Auth::id() ?? '' }}">
                            <div class="myds-input bg-muted cursor-not-allowed" role="textbox" aria-readonly="true" tabindex="-1">
                                {{ Auth::user()->name ?? __('ui.vehicles.current_user') }}
                            </div>
                            <div id="user_id-help" class="myds-body-xs myds-text--muted mt-1" data-field-help="{{ __('ui.vehicles.current_user_help') }}">{{ __('ui.vehicles.current_user_help') }}</div>
                        @endif
                        @error('user_id')
                            <div id="user_id-error" class="d-flex align-items-start myds-text--danger myds-body-xs mt-2" role="alert" data-field-error="{{ $message }}">
                                <i class="bi bi-x-circle me-1 mt-0.5 flex-shrink-0" aria-hidden="true"></i>
                                <span>{{ $message }}</span>
                            </div>
                        @enderror
                    </div>

                    {{-- Quantity Field --}}
                    <x-form-field name="qty"
                                  type="number"
                                  label="{{ __('ui.vehicles.qty_label') }}"
                                  :value="old('qty', 1)"
                                  required
                                  min="1"
                                  help="{{ __('ui.vehicles.qty_help') }}" />

                    {{-- Price Field --}}
                    <x-form-field name="price"
                                  type="number"
                                  label="{{ __('ui.vehicles.price_label') }}"
                                  :value="old('price')"
                                  step="0.01"
                                  min="0"
                                  placeholder="0.00"
                                  help="{{ __('ui.vehicles.price_help') }}" />

                    {{-- Description Field --}}
                    <x-form-field name="description"
                                  type="textarea"
                                  label="{{ __('ui.vehicles.description_label') }}"
                                  maxlength="1000"
                                  placeholder="{{ __('ui.vehicles.description_placeholder') }}"
                                  help="{{ __('ui.vehicles.description_help') }}">{{ old('description') }}</x-form-field>

                    {{-- Categories (if available) --}}
                    @if(isset($categories) && count($categories) > 0)
                        <div class="mb-4" data-field="category_id">
                            <label for="category_id" class="myds-label myds-body-sm font-medium d-block mb-2" data-field-label="{{ __('ui.categories.label', ['default' => 'Kategori']) }}">{{ __('ui.categories.label', ['default' => 'Kategori']) }}</label>
                            <select id="category_id"
                                    name="category_id"
                                    class="myds-input myds-tap-target @error('category_id') is-invalid myds-input--error @enderror"
                                    aria-describedby="category_id-help @error('category_id') category_id-error @enderror"
                                    data-myds-select="category">
                                <option value="">{{ __('ui.categories.select_placeholder', ['default' => '— Pilih kategori —']) }}</option>
                                @foreach($categories as $id => $label)
                                    <option value="{{ $id }}" @selected(old('category_id') == $id)>{{ e($label) }}</option>
                                @endforeach
                            </select>
                            <div id="category_id-help" class="myds-body-xs myds-text--muted mt-1" data-field-help="{{ __('ui.categories.help') }}">{{ __('ui.categories.help') }}</div>
                            @error('category_id')
                                <div id="category_id-error" class="d-flex align-items-start myds-text--danger myds-body-xs mt-2" role="alert" data-field-error="{{ $message }}">
                                    <i class="bi bi-x-circle me-1 mt-0.5 flex-shrink-0" aria-hidden="true"></i>
                                    <span>{{ $message }}</span>
                                </div>
                            @enderror
                        </div>
                    @endif

                    {{-- Form Actions --}}
                    <div class="d-flex flex-column flex-sm-row gap-3 justify-content-end mt-6" data-myds-actions>
                        <a href="{{ route('vehicles.index') }}" class="myds-btn myds-btn--secondary myds-tap-target" aria-label="{{ __('ui.cancel') }}" data-nav="cancel">
                            <i class="bi bi-arrow-left me-2" aria-hidden="true"></i>
                            {{ __('ui.cancel') }}
                        </a>
                        <button type="submit" class="myds-btn myds-btn--primary myds-tap-target" aria-label="{{ __('ui.save') }}" data-action="save">
                            <i class="bi bi-save me-2" aria-hidden="true"></i>
                            {{ __('ui.save') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Help Sidebar (MyGOVEA user assistance principle) --}}
        <div class="mobile:col-span-4 tablet:col-span-8 desktop:col-span-4 mt-4 desktop:mt-0">
            <div class="bg-muted border rounded-m p-4" data-myds-sidebar="help">
                <h3 class="myds-heading-xs font-heading font-medium mb-3">{{ __('ui.help.heading') }}</h3>
                <div class="space-y-3">
                    <div>
                        <h4 class="myds-body-sm font-medium mb-1">{{ __('ui.help.tips_heading') }}</h4>
                        <ul class="myds-body-xs myds-text--muted space-y-1">
                            <li>{{ __('ui.vehicles.help.tip_1') }}</li>
                            <li>{{ __('ui.vehicles.help.tip_2') }}</li>
                            <li>{{ __('ui.vehicles.help.tip_3') }}</li>
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
