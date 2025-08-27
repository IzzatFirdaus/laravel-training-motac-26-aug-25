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
        <a href="{{ $showRoute }}" class="btn btn-sm btn-outline-secondary" aria-label="Lihat {{ $label }}">Lihat</a>

        <a href="{{ $editRoute }}" class="btn btn-sm btn-outline-primary ms-1" aria-label="Edit {{ $label }}">Edit</a>

        <form method="POST" action="{{ $destroyRoute }}" class="d-inline ms-1" onsubmit="return false;">
            @csrf
            <button type="button" class="btn btn-sm btn-outline-danger" onclick="window.MYDS.handleDestroy(this)" aria-label="Padam {{ $label }}">Padam</button>
        </form>
    </div>

    <!-- Mobile: stacked + overflow -->
    <div class="d-flex d-sm-none flex-column w-100 gap-2">
        <a href="{{ $showRoute }}" class="btn btn-sm btn-block btn-outline-secondary" style="min-height:44px;" aria-label="Lihat {{ $label }}">Lihat</a>

        <a href="{{ $editRoute }}" class="btn btn-sm btn-block btn-primary" style="min-height:44px;" aria-label="Edit {{ $label }}">Edit</a>

        <div class="dropdown">
            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="moreActions-{{ $id }}" data-bs-toggle="dropdown" aria-expanded="false">
                Lagi
            </button>
            <ul class="dropdown-menu" aria-labelledby="moreActions-{{ $id }}">
                <li>
                    <form method="POST" action="{{ $destroyRoute }}" class="px-3 py-1" onsubmit="return false;">
                        @csrf
                        <button type="button" class="dropdown-item text-danger" onclick="window.MYDS.handleDestroy(this)" aria-label="Padam {{ $label }}">Padam</button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</div>
