<div class="row g-3">
    <div class="col-12">
        <label for="name" class="form-label">Nama</label>
    <input id="name" name="name" type="text" class="form-control myds-input @error('name') is-invalid @enderror" value="{{ old('name', $user->name ?? '') }}" required maxlength="255">
        @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-12">
        <label for="email" class="form-label">Emel</label>
    <input id="email" name="email" type="email" class="form-control myds-input @error('email') is-invalid @enderror" value="{{ old('email', $user->email ?? '') }}" required maxlength="255">
        @error('email')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-12">
        <label for="password" class="form-label">Kata Laluan</label>
    <input id="password" name="password" type="password" class="form-control myds-input @error('password') is-invalid @enderror" {{ isset($user) ? '' : 'required' }} minlength="8">
        @error('password')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
        @if(isset($user))
            <div class="form-text">Kosongkan untuk mengekalkan kata laluan semasa.</div>
        @endif
    </div>
</div>
