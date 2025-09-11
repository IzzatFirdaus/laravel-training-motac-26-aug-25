<div role="group" aria-label="Maklumat pengguna" class="row g-3" data-myds-form-group="user-info">

    {{-- Name Field --}}
    <fieldset class="col-12" aria-labelledby="user-info-legend" data-field="name">
        <legend id="user-info-legend" class="sr-only">Maklumat pengguna</legend>

        <label for="name" class="myds-label myds-body-sm font-medium d-block mb-2" data-field-label="{{ __('ui.form.user_info') }}">
            {{ __('ui.form.user_info') }}
            <span class="myds-text--danger ms-1" aria-hidden="true">*</span>
            <span class="sr-only"> medan wajib</span>
        </label>
        <input
            id="name"
            name="name"
            type="text"
            inputmode="text"
            class="myds-input myds-tap-target @error('name') is-invalid myds-input--error @enderror"
            value="{{ old('name', $user->name ?? '') }}"
            required
            aria-required="true"
            aria-invalid="{{ $errors->has('name') ? 'true' : 'false' }}"
            maxlength="255"
            autocomplete="name"
            aria-describedby="name-help @error('name') name-error @enderror"
            data-myds-input="text"
            data-required="true"
            placeholder="{{ __('ui.form.name_placeholder') }}"
        />
        <div id="name-help" class="myds-body-xs myds-text--muted mt-1" data-field-help="{{ __('ui.form.name_help') }}">{{ __('ui.form.name_help') }}</div>
        @error('name')
            <div id="name-error" class="d-flex align-items-start myds-text--danger myds-body-xs mt-2" role="alert" data-field-error="{{ $message }}">
                <i class="bi bi-x-circle me-1 mt-0.5 flex-shrink-0" aria-hidden="true"></i>
                <span>{{ $message }}</span>
            </div>
        @enderror
    </fieldset>

    {{-- Email Field --}}
    <fieldset class="col-12" aria-labelledby="user-email-legend" data-field="email">
        <legend id="user-email-legend" class="sr-only">Emel</legend>

        <label for="email" class="myds-label myds-body-sm font-medium d-block mb-2" data-field-label="{{ __('ui.user.email') }}">
            {{ __('ui.user.email') }}
            <span class="myds-text--danger ms-1" aria-hidden="true">*</span>
            <span class="sr-only"> medan wajib</span>
        </label>
        <input
            id="email"
            name="email"
            type="email"
            inputmode="email"
            class="myds-input myds-tap-target @error('email') is-invalid myds-input--error @enderror"
            value="{{ old('email', $user->email ?? '') }}"
            required
            aria-required="true"
            aria-invalid="{{ $errors->has('email') ? 'true' : 'false' }}"
            maxlength="255"
            autocomplete="email"
            aria-describedby="email-help @error('email') email-error @enderror"
            data-myds-input="email"
            data-required="true"
            placeholder="{{ __('ui.form.email_placeholder') }}"
        />
        <div id="email-help" class="myds-body-xs myds-text--muted mt-1" data-field-help="{{ __('ui.form.email_help') }}">{{ __('ui.form.email_help') }}</div>
        @error('email')
            <div id="email-error" class="d-flex align-items-start myds-text--danger myds-body-xs mt-2" role="alert" data-field-error="{{ $message }}">
                <i class="bi bi-x-circle me-1 mt-0.5 flex-shrink-0" aria-hidden="true"></i>
                <span>{{ $message }}</span>
            </div>
        @enderror
    </fieldset>

    {{-- Password Field --}}
    <fieldset class="col-12" aria-labelledby="user-password-legend" data-field="password">
        <legend id="user-password-legend" class="sr-only">Kata Laluan</legend>

        <label for="password" class="myds-label myds-body-sm font-medium d-block mb-2" data-field-label="{{ __('ui.password.show_hide') }}">
            {{ __('ui.password.show_hide') }}
            @unless(isset($user))
                <span class="myds-text--danger ms-1" aria-hidden="true">*</span>
                <span class="sr-only"> medan wajib</span>
            @endunless
        </label>

        <div class="myds-input-group" data-myds-password-toggle>
            <input
                id="password"
                name="password"
                type="password"
                class="myds-input myds-tap-target @error('password') is-invalid myds-input--error @enderror"
                {{ isset($user) ? '' : 'required' }}
                @unless(isset($user)) aria-required="true" @endunless
                aria-invalid="{{ $errors->has('password') ? 'true' : 'false' }}"
                minlength="8"
                autocomplete="new-password"
                aria-describedby="password-help @error('password') password-error @enderror"
                data-myds-input="password"
                data-password-target
                placeholder="{{ __('ui.password.placeholder') }}"
            />

            {{-- Password visibility toggle (progressive enhancement) --}}
            <button type="button"
                class="myds-input-group__button myds-btn myds-btn--secondary myds-btn--icon myds-tap-target"
                aria-label="{{ __('ui.password.show_hide') }}"
                data-toggle="password"
                data-target="password"
                data-myds-password-toggle-btn
                title="{{ __('ui.password.show_hide_title') }}">
                <i class="bi bi-eye" aria-hidden="true"></i>
            </button>
        </div>

        @if(isset($user))
            <div id="password-help" class="myds-body-xs myds-text--muted mt-1" data-field-help="{{ __('ui.password.keep_notice') }}">{{ __('ui.password.keep_notice') }}</div>
        @else
            <div id="password-help" class="myds-body-xs myds-text--muted mt-1" data-field-help="{{ __('ui.password.min_length') }}">{{ __('ui.password.min_length') }}</div>
        @endif

        @error('password')
            <div id="password-error" class="d-flex align-items-start myds-text--danger myds-body-xs mt-2" role="alert" data-field-error="{{ $message }}">
                <i class="bi bi-x-circle me-1 mt-0.5 flex-shrink-0" aria-hidden="true"></i>
                <span>{{ $message }}</span>
            </div>
        @enderror
    </fieldset>
</div>
