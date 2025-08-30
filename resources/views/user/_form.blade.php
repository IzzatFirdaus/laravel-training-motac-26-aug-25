<div role="group" aria-label="Maklumat pengguna" class="row g-3">
    <fieldset class="col-12" aria-labelledby="user-info-legend">
        <legend id="user-info-legend" class="sr-only">Maklumat pengguna</legend>

        <label for="name" class="myds-label">{{ __('ui.form.user_info') }} <span class="myds-text--danger" aria-hidden="true">*</span></label>
        <input
            id="name"
            name="name"
            type="text"
            inputmode="text"
            class="myds-input @error('name') is-invalid @enderror"
            value="{{ old('name', $user->name ?? '') }}"
            required
            aria-required="true"
            maxlength="255"
            autocomplete="name"
            aria-describedby="name-help @error('name') name-error @enderror"
        />
    <div id="name-help" class="myds-body-xs myds-text--muted mt-1">{{ __('ui.form.name_help') }}</div>
        @error('name')
            <div id="name-error" class="myds-field-error mt-1" role="alert">{{ $message }}</div>
        @enderror
    </fieldset>

    <fieldset class="col-12" aria-labelledby="user-email-legend">
        <legend id="user-email-legend" class="sr-only">Emel</legend>

    <label for="email" class="myds-label">{{ __('ui.user.email') }} <span class="myds-text--danger" aria-hidden="true">*</span></label>
        <input
            id="email"
            name="email"
            type="email"
            inputmode="email"
            class="myds-input @error('email') is-invalid @enderror"
            value="{{ old('email', $user->email ?? '') }}"
            required
            aria-required="true"
            maxlength="255"
            autocomplete="email"
            aria-describedby="email-help @error('email') email-error @enderror"
        />
    <div id="email-help" class="myds-body-xs myds-text--muted mt-1">{{ __('ui.form.email_help') }}</div>
        @error('email')
            <div id="email-error" class="myds-field-error mt-1" role="alert">{{ $message }}</div>
        @enderror
    </fieldset>

    <fieldset class="col-12" aria-labelledby="user-password-legend">
        <legend id="user-password-legend" class="sr-only">Kata Laluan</legend>

    <label for="password" class="myds-label">{{ __('ui.password.show_hide') }}
            @unless(isset($user))
                <span class="myds-text--danger" aria-hidden="true">*</span>
            @endunless
        </label>

        <div class="d-flex align-items-center gap-2">
            <input
                id="password"
                name="password"
                type="password"
                class="myds-input @error('password') is-invalid @enderror"
                {{ isset($user) ? '' : 'required' }}
                @unless(isset($user)) aria-required="true" @endunless
                minlength="8"
                autocomplete="new-password"
                aria-describedby="password-help @error('password') password-error @enderror"
                data-password-target
            />

            {{-- Password visibility toggle (progressive enhancement) --}}
        <button type="button"
            class="myds-btn myds-btn--tertiary myds-btn--icon"
            aria-label="{{ __('ui.password.show_hide') }}"
            data-toggle="password"
            data-target="password"
            title="{{ __('ui.password.show_hide_title') }}">
                <i class="bi bi-eye" aria-hidden="true"></i>
            </button>
        </div>

        @if(isset($user))
            <div id="password-help" class="myds-body-xs myds-text--muted mt-1">{{ __('ui.password.keep_notice') }}</div>
        @else
            <div id="password-help" class="myds-body-xs myds-text--muted mt-1">{{ __('ui.password.min_length') }}</div>
        @endif

        @error('password')
            <div id="password-error" class="myds-field-error mt-1" role="alert">{{ $message }}</div>
        @enderror
    </fieldset>
</div>
