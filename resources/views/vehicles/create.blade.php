@extends('layouts.app')

@section('title', __('ui.vehicles.create_title', ['app' => config('app.name', 'Sistem Kerajaan')]))

@section('content')
<main id="main-content" class="myds-container py-4" role="main" tabindex="-1" aria-labelledby="page-heading">

<nav aria-label="Breadcrumb" class="mb-4">
    <ol class="d-flex list-unstyled myds-text--muted myds-body-sm m-0 p-0">
        <li><a href="{{ route('vehicles.index') }}" class="text-primary text-decoration-none">{{ __('ui.vehicles.breadcrumb_label') }}</a></li>
        <li class="mx-2" aria-hidden="true">/</li>
        <li aria-current="page" class="myds-text--muted">{{ __('ui.vehicles.create_heading') }}</li>
    </ol>
</nav>

    <header class="mb-6" role="banner">
        <h1 id="page-heading" class="myds-heading-md font-heading font-semibold mb-2">{{ __('ui.vehicles.create_heading') }}</h1>
        <p class="myds-body-sm myds-text--muted mb-0">{{ __('ui.vehicles.create_description') }}</p>
    </header>

{{-- Form Container with MYDS Grid --}}
<div class="myds-grid myds-grid-desktop myds-grid-tablet myds-grid-mobile">
    <div class="mobile:col-span-4 tablet:col-span-8 desktop:col-span-8">

        {{-- Status Message --}}
        @if (session('status'))
            <div class="myds-alert myds-alert--success d-flex align-items-start mb-4" role="alert">
                <i class="bi bi-check-circle me-2 flex-shrink-0" aria-hidden="true"></i>
                <div>
                    <h4 class="myds-body-md font-medium">{{ __('ui.form.success_heading') }}</h4>
                    <p class="mb-0 myds-body-sm">{{ session('status') }}</p>
                </div>
            </div>
        @endif

        {{-- Main Form Card --}}
        <div class="bg-surface border rounded-m p-4 shadow-sm">
            <form method="POST" action="{{ route('vehicles.store') }}" novalidate aria-labelledby="form-title" data-myds-form>
                @csrf

                <h2 id="form-title" class="sr-only">{{ __('ui.vehicles.form_title') }}</h2>

                {{-- Required Fields Notice --}}
                <div class="bg-muted border-l-4 border-primary p-3 mb-4">
                    <div class="d-flex align-items-start">
                        <i class="bi bi-info-circle me-2 mt-0.5 text-primary flex-shrink-0" aria-hidden="true"></i>
                        <div>
                            <p class="myds-body-sm font-medium mb-1">{{ __('ui.form.guidance_heading') }}</p>
                            <p class="myds-body-sm myds-text--muted mb-0">{{ __('ui.form.guidance_body') }}</p>
                        </div>
                    </div>
                </div>

                {{-- Name Field (MYDS Input Component) --}}
                <div class="mb-4">
                    <label for="name" class="myds-label myds-body-sm font-medium d-block mb-2">
                        {{ __('ui.vehicles.name_label') }}
                        <span class="myds-text--danger ms-1" aria-hidden="true">*</span>
                        <span class="sr-only"> medan wajib</span>
                    </label>
                    <input type="text"
                           id="name"
                           name="name"
                           class="myds-input @error('name') is-invalid @enderror"
                           value="{{ old('name') }}"
                           aria-describedby="name-help @error('name') name-error @enderror"
                           aria-required="true"
                           maxlength="255"
                           required>
                    <div id="name-help" class="myds-body-xs myds-text--muted mt-1">{{ __('ui.vehicles.name_help') }}</div>
                    @error('name')
                        <div id="name-error" class="d-flex align-items-start myds-text--danger myds-body-xs mt-2" role="alert">
                            <i class="bi bi-x-circle me-1 mt-0.5 flex-shrink-0" aria-hidden="true"></i>
                            <span>{{ $message }}</span>
                        </div>
                    @enderror
                </div>

                {{-- Owner Field --}}
                <div class="mb-4">
                    <label for="user_id" class="myds-label myds-body-sm font-medium d-block mb-2">{{ __('ui.vehicles.owner_label') }}</label>
                    @if(auth()->check() && auth()->user()->hasRole('admin'))
                        <select id="user_id"
                                name="user_id"
                                class="myds-input @error('user_id') is-invalid @enderror"
                                aria-describedby="user_id-help @error('user_id') user_id-error @enderror">
                            <option value="">{{ __('ui.vehicles.none_owner') }}</option>
                            @foreach(($users ?? collect()) as $user)
                                <option value="{{ $user->id }}" {{ (string) old('user_id') === (string) $user->id ? 'selected' : '' }}>
                                    {{ e($user->name) }}
                                </option>
                            @endforeach
                        </select>
                        <div id="user_id-help" class="myds-body-xs myds-text--muted mt-1">{{ __('ui.vehicles.owner_help') }}</div>
                    @else
                        <input type="hidden" name="user_id" value="{{ auth()->id() ?? '' }}">
                        <div class="myds-input bg-muted cursor-not-allowed" role="textbox" aria-readonly="true" tabindex="-1">
                            {{ auth()->user()->name ?? __('ui.vehicles.current_user') }}
                        </div>
                        <div id="user_id-help" class="myds-body-xs myds-text--muted mt-1">{{ __('ui.vehicles.owner_help') }}</div>
                    @endif
                    @error('user_id')
                        <div id="user_id-error" class="d-flex align-items-start myds-text--danger myds-body-xs mt-2" role="alert">
                            <i class="bi bi-x-circle me-1 mt-0.5 flex-shrink-0" aria-hidden="true"></i>
                            <span>{{ $message }}</span>
                        </div>
                    @enderror
                </div>

                {{-- Quantity Field --}}
                <div class="mb-4">
                    <label for="qty" class="myds-label myds-body-sm font-medium d-block mb-2">{{ __('ui.vehicles.qty_label') }} <span class="myds-text--danger ms-1" aria-hidden="true">*</span></label>
              <input type="number"
                  id="qty"
                  name="qty"
                  class="myds-input @error('qty') is-invalid @enderror"
                  value="{{ old('qty', 1) }}"
                  min="1"
                  aria-describedby="qty-help @error('qty') qty-error @enderror"
                  aria-required="true"
                  required>
                    <div id="qty-help" class="myds-body-xs myds-text--muted mt-1">{{ __('ui.vehicles.qty_help') }}</div>
                    @error('qty')
                        <div id="qty-error" class="d-flex align-items-start myds-text--danger myds-body-xs mt-2" role="alert">
                            <i class="bi bi-x-circle me-1 mt-0.5 flex-shrink-0" aria-hidden="true"></i>
                            <span>{{ $message }}</span>
                        </div>
                    @enderror
                </div>

                {{-- Price Field --}}
                <div class="mb-4">
                    <label for="price" class="myds-label myds-body-sm font-medium d-block mb-2">{{ __('ui.vehicles.price_label') }}</label>
                    <div class="myds-input-group">
               <span class="myds-input-group__text bg-muted" aria-hidden="true">RM</span>
               <input type="number"
                   id="price"
                   name="price"
                   class="myds-input @error('price') is-invalid @enderror"
                   value="{{ old('price') }}"
                   step="0.01"
                   min="0"
                   aria-describedby="price-help @error('price') price-error @enderror">
                    </div>
                    <div id="price-help" class="myds-body-xs myds-text--muted mt-1">{{ __('ui.vehicles.price_help') }}</div>
                    @error('price')
                        <div id="price-error" class="d-flex align-items-start myds-text--danger myds-body-xs mt-2" role="alert">
                            <i class="bi bi-x-circle me-1 mt-0.5 flex-shrink-0" aria-hidden="true"></i>
                            <span>{{ $message }}</span>
                        </div>
                    @enderror
                </div>

                {{-- Description Field --}}
                <div class="mb-6">
                    <label for="description" class="myds-label myds-body-sm font-medium d-block mb-2">{{ __('ui.vehicles.description_label') }}</label>
                    <textarea id="description"
                              name="description"
                              class="myds-input @error('description') is-invalid @enderror"
                              rows="4"
                              aria-describedby="description-help @error('description') description-error @enderror"
                              maxlength="1000">{{ old('description') }}</textarea>
                    <div id="description-help" class="myds-body-xs myds-text--muted mt-1">{{ __('ui.vehicles.description_help') }}</div>
                    @error('description')
                        <div id="description-error" class="d-flex align-items-start myds-text--danger myds-body-xs mt-2" role="alert">
                            <i class="bi bi-x-circle me-1 mt-0.5 flex-shrink-0" aria-hidden="true"></i>
                            <span>{{ $message }}</span>
                        </div>
                    @enderror
                </div>

                {{-- Form Actions --}}
                <div class="d-flex flex-column flex-sm-row gap-3 justify-content-end">
                    <a href="{{ route('vehicles.index') }}" class="myds-btn myds-btn--secondary" aria-label="{{ __('ui.cancel') }}">
                        <i class="bi bi-arrow-left me-2" aria-hidden="true"></i>
                        {{ __('ui.cancel') }}
                    </a>
                    <button type="submit" class="myds-btn myds-btn--primary" aria-label="{{ __('ui.buttons.create_vehicle') }}">
                        <i class="bi bi-save me-2" aria-hidden="true"></i>
                        {{ __('ui.buttons.create_vehicle') }}
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Help Sidebar (MyGOVEA user assistance principle) --}}
    <div class="mobile:col-span-4 tablet:col-span-8 desktop:col-span-4 mt-4 desktop:mt-0">
        <div class="bg-muted border rounded-m p-4">
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
