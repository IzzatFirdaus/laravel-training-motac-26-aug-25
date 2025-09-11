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
    // Current user guard helper - using Auth facade for consistency
    $currentUser = \Illuminate\Support\Facades\Auth::user();
@endphp

{{-- Container for action buttons, accessible group with label --}}
<div class="d-flex gap-1" role="group" aria-label="Tindakan untuk {{ e($label) }}" data-myds-actions>
    {{-- View Action --}}
    @if($showRoute)
        @can('view', $model)
            <a href="{{ $showRoute }}"
               class="myds-btn myds-btn--secondary myds-btn--sm myds-tap-target"
               aria-label="Lihat {{ e($label) }}"
               data-action="view"
               data-myds-link>
                <i class="bi bi-eye" aria-hidden="true"></i>
                <span class="sr-only">Lihat</span>
            </a>
        @endcan
    @endif

    {{-- Edit Action --}}
    @if($editRoute)
        @can('update', $model)
            <a href="{{ $editRoute }}"
               class="myds-btn myds-btn--secondary myds-btn--sm myds-tap-target"
               aria-label="Ubah {{ e($label) }}"
               data-action="edit"
               data-myds-link>
                <i class="bi bi-pencil-square" aria-hidden="true"></i>
                <span class="sr-only">Ubah</span>
            </a>
        @endcan
    @endif

    {{-- Delete Action --}}
    @if($destroyRoute)
        @can('delete', $model)
            <form method="POST"
                  action="{{ $destroyRoute }}"
                  class="d-inline"
                  data-myds-form
                  aria-label="Padam {{ e($label) }}"
                  data-confirm-message="Anda pasti mahu memadam '{{ e($label) }}'? Tindakan ini tidak boleh dibatalkan."
                  data-confirm-title="Padam Item"
                  data-action="delete">
                @csrf
                @method('DELETE')
                <button type="submit"
                        class="myds-btn myds-btn--danger myds-btn--sm myds-tap-target"
                        aria-label="Padam {{ e($label) }}"
                        data-destructive-action>
                    <i class="bi bi-trash3" aria-hidden="true"></i>
                    <span class="sr-only">Padam</span>
                </button>
            </form>
        @endcan
    @endif

    {{-- Extra Actions (if any) --}}
    @if(!empty($extraItems))
        <div class="dropdown" data-myds-dropdown>
            <button
                id="moreActions-{{ $uid }}"
                type="button"
                class="myds-btn myds-btn--tertiary myds-btn--sm myds-tap-target dropdown-toggle"
                aria-haspopup="true"
                aria-expanded="false"
                aria-controls="moreActionsMenu-{{ $uid }}"
                aria-label="Lagi tindakan untuk {{ e($label) }}"
                data-bs-toggle="dropdown">
                <i class="bi bi-three-dots-vertical" aria-hidden="true"></i>
                <span class="sr-only">Lagi</span>
            </button>

            <ul id="moreActionsMenu-{{ $uid }}"
                class="dropdown-menu myds-dropdown-menu"
                role="menu"
                aria-labelledby="moreActions-{{ $uid }}">
                @foreach($extraItems as $item)
                    @php $ability = $item['ability'] ?? null; @endphp
                    @if(is_null($ability) || (isset($model) && $currentUser && $currentUser->can($ability, $model)))
                        <li role="none">
                            <a role="menuitem"
                               class="dropdown-item myds-dropdown-item"
                               href="{{ $item['route'] }}"
                               tabindex="0"
                               data-extra-action="{{ $item['key'] ?? '' }}">
                                @if(isset($item['icon']))
                                    <i class="bi bi-{{ $item['icon'] }} me-2" aria-hidden="true"></i>
                                @endif
                                {{ e($item['label']) }}
                            </a>
                        </li>
                    @endif
                @endforeach
            </ul>
        </div>
    @endif
</div>
