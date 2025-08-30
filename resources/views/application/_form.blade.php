<div class="row g-3">
    <div class="col-12">
        <label for="name" class="form-label font-heading">Nama <span class="text-danger" aria-hidden="true">*</span></label>
        <input
            id="name"
            name="name"
            type="text"
            class="form-control myds-input @error('name') is-invalid @enderror"
            value="{{ old('name', $application->name ?? '') }}"
            required
            maxlength="255"
            aria-required="true"
            aria-invalid="{{ $errors->has('name') ? 'true' : 'false' }}"
            @if($errors->has('name')) aria-describedby="name-error" @endif
        >
        @error('name')
            <div id="name-error" class="invalid-feedback" role="alert">{{ $message }}</div>
        @enderror

        <div id="users-autocomplete" class="position-relative mt-2 autocomplete-wrapper" aria-hidden="false">
            <ul id="users-list" class="list-group autocomplete-list" role="listbox" aria-label="Cadangan pengguna" style="display:none"></ul>
        </div>
    </div>

    <div class="col-12">
        <label for="description" class="form-label">Keterangan</label>
        <textarea
            id="description"
            name="description"
            class="form-control myds-input @error('description') is-invalid @enderror"
            aria-invalid="{{ $errors->has('description') ? 'true' : 'false' }}"
            @if($errors->has('description')) aria-describedby="description-error" @endif
        >{{ old('description', $application->description ?? '') }}</textarea>
        @error('description')
            <div id="description-error" class="invalid-feedback" role="alert">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-12">
        <label for="inventory_id" class="form-label">Inventori</label>
        <select
            id="inventory_id"
            name="inventory_id"
            class="form-control myds-input @error('inventory_id') is-invalid @enderror"
            aria-invalid="{{ $errors->has('inventory_id') ? 'true' : 'false' }}"
            @if($errors->has('inventory_id')) aria-describedby="inventory-error" @endif
        >
            <option value="">{{ __('(tiada)') }}</option>
            @foreach(($inventories ?? collect()) as $inv)
                <option value="{{ $inv->id }}" {{ (string) old('inventory_id', $application->inventory_id ?? '') === (string) $inv->id ? 'selected' : '' }}>
                    {{ $inv->name }}
                </option>
            @endforeach
        </select>
        @error('inventory_id')
            <div id="inventory-error" class="invalid-feedback" role="alert">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-12">
        <label for="vehicle_id" class="form-label">Kenderaan</label>
        <select
            id="vehicle_id"
            name="vehicle_id"
            class="form-control myds-input @error('vehicle_id') is-invalid @enderror"
            aria-invalid="{{ $errors->has('vehicle_id') ? 'true' : 'false' }}"
            @if($errors->has('vehicle_id')) aria-describedby="vehicle-error" @endif
        >
            <option value="">{{ __('(tiada)') }}</option>
            {{-- Options will be populated dynamically based on selected inventory --}}
            @if(isset($application) && $application->vehicle_id && $application->inventory)
                @foreach($application->inventory->vehicles ?? collect() as $v)
                    <option value="{{ $v->id }}" {{ (string) old('vehicle_id', $application->vehicle_id ?? '') === (string) $v->id ? 'selected' : '' }}>
                        {{ $v->name }}
                    </option>
                @endforeach
            @endif
        </select>
        @error('vehicle_id')
            <div id="vehicle-error" class="invalid-feedback" role="alert">{{ $message }}</div>
        @enderror
    </div>

    {{-- Pemilik (owner) field: show a select for admins, otherwise set current user as hidden owner --}}
    @push('scripts')
        @vite('resources/js/features/application-form.js')
    @endpush

</div>
