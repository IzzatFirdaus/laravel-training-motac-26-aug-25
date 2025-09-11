@php
    use Illuminate\Support\Facades\Auth;
@endphp

<div class="row g-3" role="group" aria-label="Maklumat Permohonan">
    {{-- Name --}}
    <div class="col-12">
        <label for="name" class="myds-label font-heading">Nama <span class="myds-text--danger" aria-hidden="true">*</span></label>
        <input
            id="name"
            name="name"
            type="text"
            class="myds-input @error('name') is-invalid @enderror"
            value="{{ old('name', $application->name ?? '') }}"
            required
            maxlength="255"
            aria-required="true"
            aria-invalid="{{ $errors->has('name') ? 'true' : 'false' }}"
            @if($errors->has('name')) aria-describedby="name-error" @endif
            autocomplete="name"
            inputmode="text"
            />
        @error('name')
            <div id="name-error" class="myds-field-error" role="alert">{{ $message }}</div>
        @enderror

        {{-- Users autocomplete placeholder (progressive enhancement) --}}
        <div id="users-autocomplete" class="position-relative mt-2 autocomplete-wrapper" aria-hidden="false">
            <ul id="users-list"
                class="autocomplete-list bg-surface border rounded p-0 m-0 visually-hidden"
                role="listbox"
                aria-label="Cadangan pengguna"
                style="list-style:none;"></ul>
        </div>
    </div>

    {{-- Description --}}
    <div class="col-12">
        <label for="description" class="myds-label">Keterangan</label>
        <textarea
            id="description"
            name="description"
            class="myds-input @error('description') is-invalid @enderror"
            aria-invalid="{{ $errors->has('description') ? 'true' : 'false' }}"
            @if($errors->has('description')) aria-describedby="description-error" @endif
            rows="4"
            maxlength="1000"
            placeholder="Maklumat lanjut mengenai permohonan">{{ old('description', $application->description ?? '') }}</textarea>
        @error('description')
            <div id="description-error" class="myds-field-error" role="alert">{{ $message }}</div>
        @enderror
    </div>

    {{-- Inventory --}}
    <div class="col-12 col-md-6">
        <label for="inventory_id" class="myds-label">Inventori</label>
        <select
            id="inventory_id"
            name="inventory_id"
            class="myds-input @error('inventory_id') is-invalid @enderror"
            aria-invalid="{{ $errors->has('inventory_id') ? 'true' : 'false' }}"
            @if($errors->has('inventory_id')) aria-describedby="inventory-error" @endif
            data-dependent="#vehicle_id"
            data-fetch-url="{{ url('/api/inventories') }}"
        >
            <option value="">{{ __('(tiada)') }}</option>
            @foreach(($inventories ?? collect()) as $inv)
                <option value="{{ $inv->id }}" {{ (string) old('inventory_id', $application->inventory_id ?? '') === (string) $inv->id ? 'selected' : '' }}>
                    {{ $inv->name }}
                </option>
            @endforeach
        </select>
        @error('inventory_id')
            <div id="inventory-error" class="myds-field-error" role="alert">{{ $message }}</div>
        @enderror
    </div>

    {{-- Vehicle (dependent on inventory) --}}
    <div class="col-12 col-md-6">
        <label for="vehicle_id" class="myds-label">Kenderaan</label>
        <select
            id="vehicle_id"
            name="vehicle_id"
            class="myds-input @error('vehicle_id') is-invalid @enderror"
            aria-invalid="{{ $errors->has('vehicle_id') ? 'true' : 'false' }}"
            @if($errors->has('vehicle_id')) aria-describedby="vehicle-error" @endif
            data-placeholder="(tiada)"
        >
            <option value="">{{ __('(tiada)') }}</option>
            @if(isset($application) && $application->vehicle_id && $application->inventory)
                @foreach($application->inventory->vehicles ?? collect() as $v)
                    <option value="{{ $v->id }}" {{ (string) old('vehicle_id', $application->vehicle_id ?? '') === (string) $v->id ? 'selected' : '' }}>
                        {{ $v->name }}
                    </option>
                @endforeach
            @endif
        </select>
        @error('vehicle_id')
            <div id="vehicle-error" class="myds-field-error" role="alert">{{ $message }}</div>
        @enderror
    </div>

    {{-- Owner: admin selects, others become hidden owner field --}}
    <div class="col-12 col-md-6">
        <label for="owner_id" class="myds-label">Pemilik</label>

        @if(Auth::check() && Auth::user()->hasRole('admin'))
            <select id="owner_id" name="owner_id" class="myds-input @error('owner_id') is-invalid @enderror" aria-describedby="@error('owner_id') owner-error @enderror">
                <option value="">(Tiada pemilik ditetapkan)</option>
                @foreach(($users ?? collect()) as $user)
                    <option value="{{ $user->id }}" {{ (string) old('owner_id', $application->owner_id ?? '') === (string) $user->id ? 'selected' : '' }}>
                        {{ $user->name }}
                    </option>
                @endforeach
            </select>
            @error('owner_id')
                <div id="owner-error" class="myds-field-error" role="alert">{{ $message }}</div>
            @enderror
        @else
            <input type="hidden" name="owner_id" value="{{ Auth::id() ?? '' }}">
            <div class="myds-input bg-muted mt-1" aria-hidden="true">{{ Auth::user()->name ?? 'Pengguna Semasa' }}</div>
            <div class="myds-body-xs myds-text--muted">Anda akan menjadi pemilik permohonan ini</div>
        @endif
    </div>

    {{-- Submit helper (will not render a button here) --}}
</div>

@push('scripts')
    {{-- Feature script: handles inventory -> vehicle dependency and users autocomplete.
        The script should be idempotent (safe to include on both create/edit pages).
        Ensure resources/js/features/application-form.js exists and exports initialisation functions. --}}
    @vite('resources/js/features/application-form.js')
@endpush
