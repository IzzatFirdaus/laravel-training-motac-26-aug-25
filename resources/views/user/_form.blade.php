<div role="group" aria-label="Maklumat pengguna" class="row g-3">
    <div class="col-12">
        <label for="name" class="form-label myds-label">Nama <span class="text-danger" aria-hidden="true">*</span></label>
    <input id="name"
               name="name"
               type="text"
               inputmode="text"
           class="form-control myds-input @error('name') is-invalid @enderror"
           value="{{ old('name', $user->name ?? '') }}"
           required
           aria-required="true"
               maxlength="255"
           aria-describedby="@error('name') name-error @enderror"
               autocomplete="name" />
        @error('name')
            <div id="name-error" class="invalid-feedback" role="alert">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-12">
        <label for="email" class="form-label myds-label">Emel <span class="text-danger" aria-hidden="true">*</span></label>
    <input id="email"
               name="email"
               type="email"
               inputmode="email"
           class="form-control myds-input @error('email') is-invalid @enderror"
           value="{{ old('email', $user->email ?? '') }}"
           required
           aria-required="true"
               maxlength="255"
           aria-describedby="@error('email') email-error @enderror"
               autocomplete="email" />
        @error('email')
            <div id="email-error" class="invalid-feedback" role="alert">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-12">
        <label for="password" class="form-label myds-label">Kata Laluan
            @unless(isset($user))
                <span class="text-danger" aria-hidden="true">*</span>
            @endunless
        </label>
    <input id="password"
               name="password"
               type="password"
               class="form-control myds-input @error('password') is-invalid @enderror"
           {{ isset($user) ? '' : 'required' }}
           @unless(isset($user)) aria-required="true" @endunless
               minlength="8"
           aria-describedby="password-help @error('password') password-error @enderror"
               autocomplete="{{ isset($user) ? 'new-password' : 'new-password' }}" />
        @error('password')
            <div id="password-error" class="invalid-feedback" role="alert">{{ $message }}</div>
        @enderror

        @if(isset($user))
            <div id="password-help" class="form-text myds-help">Kosongkan untuk mengekalkan kata laluan semasa.</div>
        @else
            <div id="password-help" class="form-text myds-help">Kata laluan mesti sekurang-kurangnya 8 aksara.</div>
        @endif
    </div>
</div>