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
    @can('view', $model)
        <a href="{{ $showRoute }}" class="myds-btn myds-btn--secondary myds-btn--sm" aria-label="Lihat {{ $label }}" data-myds="link">Lihat</a>
    @endcan

        @can('update', $model)
            <a href="{{ $editRoute }}" class="myds-btn myds-btn--primary ms-1 myds-btn--sm" aria-label="Edit {{ $label }}" data-myds="link">Edit</a>
        @endcan

        @can('delete', $model)
            <form method="POST" action="{{ $destroyRoute }}" class="d-inline ms-1" data-myds-form>
                @csrf
                <button type="button" class="myds-btn myds-btn--danger myds-btn--sm" aria-label="Padam {{ $label }}">Padam</button>
            </form>
        @endcan
    </div>

    <!-- Mobile: stacked + overflow -->
    <div class="d-flex d-sm-none flex-column w-100 gap-2">
    @can('view', $model)
        <a href="{{ $showRoute }}" class="myds-btn myds-btn--secondary myds-btn--block myds-btn--sm" style="min-height:44px;" aria-label="Lihat {{ $label }}" data-myds="link">Lihat</a>
    @endcan

    @can('update', $model)
    <a href="{{ $editRoute }}" class="myds-btn myds-btn--primary myds-btn--block myds-btn--sm" style="min-height:44px;" aria-label="Edit {{ $label }}" data-myds="link">Edit</a>
    @endcan

        <div class="dropdown">
            <button class="myds-btn myds-btn--outline myds-btn--sm dropdown-toggle" type="button" id="moreActions-{{ $id }}" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" aria-label="Lagi tindakan">
                Lagi
            </button>
            <div class="dropdown-menu myds-dropdown" aria-labelledby="moreActions-{{ $id }}" role="menu">
                @can('delete', $model)
                    <form method="POST" action="{{ $destroyRoute }}" class="px-3 py-1" data-myds-form>
                        @csrf
                        <button type="button" class="dropdown-item myds-btn myds-btn--danger" role="menuitem" aria-label="Padam {{ $label }}">Padam</button>
                    </form>
                @endcan
            </div>
        </div>
    </div>
</div>
