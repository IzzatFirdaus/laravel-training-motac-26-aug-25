@props([
    'showRoute' => null,
    'editRoute' => null,
    'destroyRoute' => null,
    'label' => '',
    'id' => null,
    'model' => null,
    'extraItems' => [],
])

@php
    // Stable unique id for accessible controls
    $uid = $id ?? uniqid('actbtn_');
    // Current user guard helper
    $currentUser = auth()->user();
@endphp

{{-- Container for action buttons, accessible group with label --}}
<div class="d-flex align-items-center" role="group" aria-label="{{ $label }} actions">
    {{-- Desktop / tablet inline actions --}}
    <div class="d-none d-sm-flex gap-2 align-items-center" aria-hidden="false">
        @can('view', $model)
            <a href="{{ $showRoute }}" class="myds-btn myds-btn--secondary myds-btn--sm" aria-label="{{ __('ui.view') }} {{ $label }}" data-myds="link">{{ __('ui.view') }}</a>
        @endcan

        @can('update', $model)
            <a href="{{ $editRoute }}" class="myds-btn myds-btn--primary myds-btn--sm" aria-label="{{ __('ui.edit') ?? 'Sunting' }} {{ $label }}" data-myds="link">{{ __('ui.edit') ?? 'Sunting' }}</a>
        @endcan

        @can('delete', $model)
            {{-- Destructive action uses POST + method override so JS can intercept for confirmation --}}
            <form method="POST" action="{{ $destroyRoute }}" class="d-inline" data-myds-form data-model="{{ $label }}" aria-label="Padam {{ $label }}">
                @csrf
                @method('DELETE')
                <button type="submit"
                        class="myds-btn myds-btn--danger myds-btn--sm"
                        aria-label="Padam {{ $label }}">
                    Padam
                </button>
            </form>
        @endcan

        @if(!empty($extraItems))
            {{-- Accessible dropdown for extra items --}}
            <div class="dropdown">
                <button
                    id="moreExtra-{{ $uid }}"
                    type="button"
                    class="myds-btn myds-btn--outline myds-btn--sm dropdown-toggle"
                    aria-haspopup="true"
                    aria-expanded="false"
                    aria-controls="moreExtraMenu-{{ $uid }}"
                    aria-label="{{ __('ui.more_actions') ?? 'Lagi tindakan' }} untuk {{ $label }}">
                    {{ __('ui.more') ?? 'Lagi' }}
                </button>

                <ul id="moreExtraMenu-{{ $uid }}" class="dropdown-menu myds-dropdown" role="menu" aria-labelledby="moreExtra-{{ $uid }}">
                    @foreach($extraItems as $item)
                        @php $ability = $item['ability'] ?? null; @endphp
                        @if(is_null($ability) || (isset($model) && $currentUser && $currentUser->can($ability, $model)))
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

    {{-- Mobile: stacked + overflow dropdown --}}
    <div class="d-flex d-sm-none flex-column w-100 gap-2" aria-hidden="false">
        @can('view', $model)
            <a href="{{ $showRoute }}" class="myds-btn myds-btn--secondary myds-btn--block myds-btn--sm" aria-label="Lihat {{ $label }}" data-myds="link">Lihat</a>
        @endcan

        @can('update', $model)
            <a href="{{ $editRoute }}" class="myds-btn myds-btn--primary myds-btn--block myds-btn--sm" aria-label="{{ __('ui.edit') ?? 'Sunting' }} {{ $label }}" data-myds="link">{{ __('ui.edit') ?? 'Sunting' }}</a>
        @endcan

        <div class="dropdown">
                <button
                id="moreActions-{{ $uid }}"
                class="myds-btn myds-btn--outline myds-btn--sm dropdown-toggle"
                type="button"
                aria-haspopup="true"
                aria-expanded="false"
                aria-controls="moreActionsMenu-{{ $uid }}"
                    aria-label="{{ __('ui.more_actions') ?? 'Lagi tindakan' }} untuk {{ $label }}">
                {{ __('ui.more') ?? 'Lagi' }}
            </button>

            <ul id="moreActionsMenu-{{ $uid }}" class="dropdown-menu myds-dropdown" aria-labelledby="moreActions-{{ $uid }}" role="menu">
                @can('delete', $model)
                    <li role="none" class="px-2 py-1">
                        <form method="POST" action="{{ $destroyRoute }}" class="m-0" data-myds-form data-model="{{ $label }}" role="group" aria-label="Padam {{ $label }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="myds-btn myds-btn--danger w-100" role="menuitem" aria-label="Padam {{ $label }}">
                                Padam
                            </button>
                        </form>
                    </li>
                @endcan

                @if(!empty($extraItems))
                    <li><hr class="dropdown-divider" aria-hidden="true"></li>
                    @foreach($extraItems as $item)
                        @php $ability = $item['ability'] ?? null; @endphp
                        @if(is_null($ability) || (isset($model) && $currentUser && $currentUser->can($ability, $model)))
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
