<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale() ?? 'ms') }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>{{ __('Inventori baharu dicipta') }} — {{ config('app.name') }}</title>

  <!--
    Minimal, email-friendly MYDS / MyGOVEA styles.
    Keep CSS conservative for email client compatibility; use system fonts and limited selectors.
  -->
  <style>
    /* Base */
    :root{
      --myds-primary: rgb(37, 99, 235);
      --myds-bg: rgb(255, 255, 255);
      --myds-surface: rgb(250, 250, 250);
      --myds-text: rgb(15, 23, 42);
      --myds-muted: rgb(107, 114, 128);
      --myds-border: rgb(230, 238, 248);
      --myds-radius: 8px;
    }

    html,body{margin:0;padding:0;background:var(--myds-surface);height:100%;width:100%;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;}
    body{font-family:Inter, system-ui, -apple-system, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;color:var(--myds-text);line-height:1.4;}
  .myds-container{max-width:680px;margin:0 auto;padding:24px;}
    .brand{display:block;font-weight:700;color:var(--myds-primary);font-size:18px;margin-bottom:12px;text-decoration:none;}
  .myds-card{background:var(--myds-bg);border:1px solid var(--myds-border);border-radius:var(--myds-radius);padding:18px;}
    h1,h2{margin:0 0 8px 0;font-weight:600;color:var(--myds-text);}
    p{margin:0 0 12px 0;color:var(--myds-text);}
    .muted{color:var(--myds-muted);font-size:14px;margin:0 0 12px 0;}
    .meta{font-size:14px;color:var(--myds-text);margin:6px 0;}
    .label{color:var(--myds-muted);font-weight:600;margin-right:6px;}
    .myds-btn{
      display:inline-block;padding:10px 16px;background:var(--myds-primary);color:#fff;border-radius:6px;text-decoration:none;font-weight:600;
    }
    .footer{font-size:13px;color:var(--myds-muted);margin-top:18px;}
    /* Accessibility helpers */
    .visually-hidden{position:absolute!important;height:1px;width:1px;overflow:hidden;clip:rect(1px,1px,1px,1px);white-space:nowrap;border:0;padding:0;margin:-1px;}
    /* Make sure images don't exceed container */
    img{max-width:100%;height:auto;border:0;display:block;}
    /* Small screens */
    @media (max-width:480px){
      .container{padding:16px;}
      .card{padding:16px;}
      .btn{display:block;text-align:center;width:100%;}
    }
  </style>
</head>
<body>
  <span class="visually-hidden">{{ __('Preheader: Inventori baru dicipta di :app', ['app' => config('app.name')]) }}</span>

  <div class="myds-container" role="document" aria-labelledby="inv-title">
    <a href="{{ url('/') }}" class="brand" aria-label="{{ config('app.name') }}">
      {{-- Prefer a small government or agency logo if available; fall back to gov-logo.png --}}
      @if(file_exists(public_path('images/gov-logo-email.png')))
        <img src="{{ asset('images/gov-logo-email.png') }}" alt="{{ config('app.name') }} logo" style="height:36px;margin-bottom:8px;">
      @elseif(file_exists(public_path('images/gov-logo.png')))
        <img src="{{ asset('images/gov-logo.png') }}" alt="{{ config('app.name') }} logo" style="height:36px;margin-bottom:8px;">
      @else
        {{ config('app.name') }}
      @endif
    </a>

  <div class="myds-card" role="article" aria-labelledby="inv-title">
      <h2 id="inv-title">{{ __('Inventori baharu dicipta') }}</h2>

      <p class="muted">
        {{ __('Sistem melaporkan inventori baru telah ditambah ke dalam pangkalan data.') }}
      </p>

      <div class="meta"><span class="label">{{ __('Nama:') }}</span><span>{{ e($inventory->name ?? '—') }}</span></div>
      <div class="meta"><span class="label">{{ __('Kuantiti:') }}</span><span>{{ isset($inventory->qty) ? e($inventory->qty) : '—' }}</span></div>
      <div class="meta"><span class="label">{{ __('Harga:') }}</span>
        <span>
          @if(isset($inventory->price) && is_numeric($inventory->price))
            {{ 'RM ' . number_format($inventory->price, 2) }}
          @else
            —
          @endif
        </span>
      </div>

      @if(! empty($inventory->description))
        <div style="margin-top:12px;color:var(--myds-text);font-size:14px;">
          {!! nl2br(e($inventory->description)) !!}
        </div>
      @endif

      <p style="margin-top:18px;">
        <a class="myds-btn myds-btn--primary" href="{{ url('/inventories/' . $inventory->getKey()) }}" target="_blank" rel="noopener noreferrer">
          {{ __('Lihat Inventori') }}
        </a>
      </p>
    </div>

    <p class="footer">
      {{ __('Jika anda tidak menjangka emel ini, sila abaikan mesej ini.') }}
    </p>

    <p class="muted" style="font-size:12px;margin-top:8px;">
      {{ config('app.name') }} — {{ url('/') }}
    </p>
  </div>
</body>
</html>
