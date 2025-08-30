<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale() ?? 'ms') }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>{{ __('ui.inventory_created_title') }} — {{ config('app.name') }}</title>

  @include('emails._styles')
</head>
<body>
  <span class="visually-hidden">{{ __('Preheader: Inventori baru dicipta di :app', ['app' => config('app.name')]) }}</span>

  <div class="myds-container" role="document" aria-labelledby="inv-title">
      <a href="{{ url('/') }}" class="brand" aria-label="{{ config('app.name') }}">
      @if(file_exists(public_path('images/gov-logo.png')))
        <img src="{{ asset('images/gov-logo.png') }}" alt="{{ config('app.name') }} logo" class="brand-logo">
      @else
        {{ config('app.name') }}
      @endif
    </a>

    <div class="myds-card" role="article" aria-labelledby="inv-title">
      <p class="muted">
        {{ __('ui.new_inventory_created') }}
      </p>
  {{ __('ui.inventory_created_body') }}
      </p>
          <td class="meta"><span class="label">{{ __('ui.inventory_label_name') }}:</span><span>{{ e($inventory->name ?? '—') }}</span></td>
  <table role="presentation" cellpadding="0" cellspacing="0" width="100%" class="meta-table">
        <tr>
          <td class="meta"><span class="label">{{ __('ui.inventory_label_qty') }}:</span><span>{{ isset($inventory->qty) ? e($inventory->qty) : '—' }}</span></td>
        </tr>
        <tr>
          <td class="meta"><span class="label">{{ __('ui.inventory_label_price') }}:</span>
        </tr>
        <tr>
          <td class="meta"><span class="label">{{ __('ui.inventory_label_price') }}:</span>
            <span>
              @if(isset($inventory->price) && is_numeric($inventory->price))
                {{ 'RM ' . number_format($inventory->price, 2) }}
              @else
                —
              @endif
            </span>
          </td>
        </tr>
      </table>

      @if(! empty($inventory->description))
        <div class="description">
          {!! nl2br(e($inventory->description)) !!}
        </div>
      @endif
          {{ __('ui.view_inventory_text') }}
  <p class="action-row">
        <a class="myds-btn" href="{{ route('inventories.show', $inventory->getKey()) }}" target="_blank" rel="noopener noreferrer">
          {{ __('ui.view_inventory_text') }}
        </a>
      </p>

  <p class="muted small">
  <span class="label">{{ __('ui.inventory_created_created_at_label') }}:</span>
        @if(isset($inventory->created_at))
          <time datetime="{{ \Illuminate\Support\Carbon::parse($inventory->created_at)->toIso8601String() }}">{{ \Illuminate\Support\Carbon::parse($inventory->created_at)->format('j M Y H:i') }}</time>
        @else
          —
        @endif
      </p>
      {{ __('ui.email_ignore_full') }}

    <p class="footer">
      {{ __('ui.email_ignore_full') }}
    </p>

    <p class="muted small" style="margin-top:8px;">
      {{ config('app.name') }} — <a href="{{ url('/') }}">{{ url('/') }}</a>
    </p>
  </div>
</body>
</html>
