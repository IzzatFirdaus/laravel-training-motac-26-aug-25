@props([
    'showRoute' => null,
    'editRoute' => null,
    'destroyRoute' => null,
    'label' => '',
    'id' => null,
    'model' => null,
    // extraItems: array of [ 'label' => string, 'route' => url-string, 'ability' => optional ability to check ]
    'extraItems' => [],
])

@php
    // ensure a stable unique id for dropdowns when $id is not provided
    $uid = $id ?? uniqid('actbtn_');
@endphp

<div class="d-flex align-items-center" role="group" aria-label="{{ $label }} actions">
    {{-- Desktop / tablet inline actions --}}
    <div class="d-none d-sm-flex gap-1 align-items-center" aria-hidden="false">
        @can('view', $model)
            <a href="{{ $showRoute }}" class="myds-btn myds-btn--secondary myds-btn--sm" aria-label="Lihat {{ $label }}" data-myds="link">Lihat</a>
        @endcan

        @can('update', $model)
            <a href="{{ $editRoute }}" class="myds-btn myds-btn--primary myds-btn--sm" aria-label="Edit {{ $label }}" data-myds="link">Edit</a>
        @endcan

        @can('delete', $model)
            {{-- destructive actions use POST and data-myds-form so JS can intercept for confirmation --}}
            <form method="POST" action="{{ $destroyRoute }}" class="d-inline" data-myds-form data-model="{{ $label }}">
                @csrf
                <button type="submit"
                        class="myds-btn myds-btn--danger myds-btn--sm"
                        aria-label="Padam {{ $label }}">
                    Padam
                </button>
            </form>
        @endcan

        @if(!empty($extraItems))
            <div class="dropdown ml-1">
                <button
                    class="myds-btn myds-btn--outline myds-btn--sm dropdown-toggle"
                    type="button"
                    id="moreExtra-{{ $uid }}"
                    data-bs-toggle="dropdown"
                    aria-haspopup="true"
                    aria-expanded="false"
                    aria-label="Lagi tindakan untuk {{ $label }}">
                    Lagi
                </button>

                <ul class="dropdown-menu myds-dropdown" aria-labelledby="moreExtra-{{ $uid }}" role="menu">
                    @foreach($extraItems as $item)
                        @php $ability = $item['ability'] ?? null; @endphp
                        @if(is_null($ability) || (isset($model) && auth()->user() && auth()->user()->can($ability, $model)))
                            <li role="none">
                                <a role="menuitem"
                                   class="dropdown-item"
                                   href="{{ $item['route'] }}"
                                   tabindex="0">
                                    {{ $item['label'] }}
                                </a>
                            </li>
                        @endif
                    @endforeach
                </ul>
            </div>
        @endif
    </div>

    {{-- Mobile: stacked + overflow --}}
    <div class="d-flex d-sm-none flex-column w-100 gap-2" aria-hidden="false">
        @can('view', $model)
            <a href="{{ $showRoute }}" class="myds-btn myds-btn--secondary myds-btn--block myds-btn--sm" aria-label="Lihat {{ $label }}" data-myds="link">Lihat</a>
        @endcan

        @can('update', $model)
            <a href="{{ $editRoute }}" class="myds-btn myds-btn--primary myds-btn--block myds-btn--sm" aria-label="Edit {{ $label }}" data-myds="link">Edit</a>
        @endcan

        <div class="dropdown">
            <button
                class="myds-btn myds-btn--outline myds-btn--sm dropdown-toggle"
                type="button"
                id="moreActions-{{ $uid }}"
                data-bs-toggle="dropdown"
                aria-haspopup="true"
                aria-expanded="false"
                aria-label="Lagi tindakan untuk {{ $label }}">
                Lagi
            </button>

            <ul class="dropdown-menu myds-dropdown" aria-labelledby="moreActions-{{ $uid }}" role="menu">
                @can('delete', $model)
                    <li role="none" class="px-2 py-1">
                        <form method="POST" action="{{ $destroyRoute }}" class="m-0" data-myds-form data-model="{{ $label }}">
                            @csrf
                            <button type="submit" class="myds-btn myds-btn--danger w-100" role="menuitem" aria-label="Padam {{ $label }}">
                                Padam
                            </button>
                        </form>
                    </li>
                @endcan

                @if(!empty($extraItems))
                    <li><hr class="dropdown-divider"></li>
                    @foreach($extraItems as $item)
                        @php $ability = $item['ability'] ?? null; @endphp
                        @if(is_null($ability) || (isset($model) && auth()->user() && auth()->user()->can($ability, $model)))
                            <li role="none">
                                <a role="menuitem" class="dropdown-item" href="{{ $item['route'] }}" tabindex="0">
                                    {{ $item['label'] }}
                                </a>
                            </li>
                        @endif
                    @endforeach
                @endif
            </ul>
        </div>
    </div>
</div>
