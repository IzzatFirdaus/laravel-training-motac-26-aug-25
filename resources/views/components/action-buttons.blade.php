@props([
    'showRoute',
    'editRoute',
    'destroyRoute',
    'label' => '',
    'id' => null,
    'model' => ''
])

<div class="d-flex align-items-center">
    <!-- Desktop / tablet inline actions -->
    <div class="d-none d-sm-flex gap-1 align-items-center">
        <a href="{{ $showRoute }}" class="btn btn-sm btn-outline-secondary myds-btn myds-btn--secondary" aria-label="Lihat {{ $label }}" data-myds="link">Lihat</a>

        <a href="{{ $editRoute }}" class="btn btn-sm btn-outline-primary ms-1 myds-btn myds-btn--primary" aria-label="Edit {{ $label }}" data-myds="link">Edit</a>

    <form method="POST" action="{{ $destroyRoute }}" class="d-inline ms-1" data-myds-form>
            @csrf
            <button type="button" class="btn btn-sm btn-outline-danger myds-btn myds-btn--danger" aria-label="Padam {{ $label }}">Padam</button>
        </form>
    </div>

    <!-- Mobile: stacked + overflow -->
    <div class="d-flex d-sm-none flex-column w-100 gap-2">
    <a href="{{ $showRoute }}" class="btn btn-sm btn-block btn-outline-secondary myds-btn myds-btn--secondary" style="min-height:44px;" aria-label="Lihat {{ $label }}" data-myds="link">Lihat</a>

    <a href="{{ $editRoute }}" class="btn btn-sm btn-block btn-primary myds-btn myds-btn--primary" style="min-height:44px;" aria-label="Edit {{ $label }}" data-myds="link">Edit</a>

        <div class="dropdown">
            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="moreActions-{{ $id }}" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" aria-label="Lagi tindakan">
                Lagi
            </button>
            <div class="dropdown-menu myds-dropdown" aria-labelledby="moreActions-{{ $id }}" role="menu">
                <form method="POST" action="{{ $destroyRoute }}" class="px-3 py-1" data-myds-form>
                    @csrf
                    <button type="button" class="dropdown-item myds-btn myds-btn--danger" role="menuitem" aria-label="Padam {{ $label }}">Padam</button>
                </form>
            </div>
        </div>
    </div>
</div>
