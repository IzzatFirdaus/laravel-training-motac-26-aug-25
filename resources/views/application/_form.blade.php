<div class="row g-3">
    <div class="col-12">
        <label for="name" class="form-label">Nama</label>
        <input id="name" name="name" type="text" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $application->name ?? '') }}" required maxlength="255">
        @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-12">
        <label for="description" class="form-label">Keterangan</label>
        <textarea id="description" name="description" class="form-control @error('description') is-invalid @enderror">{{ old('description', $application->description ?? '') }}</textarea>
        @error('description')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

        <div class="col-12">
            <label for="inventory_id" class="form-label">Inventori berkaitan (pilihan)</label>
            <select id="inventory_id" name="inventory_id" class="form-control @error('inventory_id') is-invalid @enderror">
                <option value="">(tiada)</option>
                @foreach(($inventories ?? collect()) as $inv)
                    <option value="{{ $inv->id }}" {{ (string) old('inventory_id', $application->inventory_id ?? '') === (string) $inv->id ? 'selected' : '' }}>{{ $inv->name }}</option>
                @endforeach
            </select>
            @error('inventory_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
</div>
