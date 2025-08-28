<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>New Inventory Created</title>
  <style>
    /* Minimal MYDS email styles */
    body { font-family: Inter, system-ui, -apple-system, 'Segoe UI', Roboto, 'Helvetica Neue', Arial; color: #0f172a; margin:0; padding:0; }
    .container { max-width: 680px; margin: 0 auto; padding: 24px; }
    .brand { display:block; font-weight:700; color:#0b5fff; font-size:18px; margin-bottom:12px; }
    .card { background:#ffffff; border:1px solid #e6eef8; border-radius:8px; padding:18px; }
    .muted { color:#6b7280; font-size:14px; }
    .btn { display:inline-block; padding:8px 14px; background:#0b5fff; color:#fff; border-radius:6px; text-decoration:none; }
    .meta { font-size:13px; color:#374151; }
  </style>
</head>
<body>
  <div class="container">
    <div class="brand">MYDS — {{ config('app.name') }}</div>

    <div class="card">
      <h2 style="margin-top:0;">New inventory created</h2>

      <p class="muted">A new inventory item has been created in the system.</p>

      <p class="meta"><strong>Nama:</strong> {{ $inventory->name }}</p>
      <p class="meta"><strong>Qty:</strong> {{ $inventory->qty }}</p>
      <p class="meta"><strong>Price:</strong> {{ $inventory->price }}</p>

      @if(! empty($inventory->description))
        <div style="margin-top:12px;">{{ nl2br(e($inventory->description)) }}</div>
      @endif

      <p style="margin-top:18px;">
        <a class="btn" href="{{ url('/inventories/' . $inventory->getKey()) }}">Lihat Inventori</a>
      </p>
    </div>

    <p class="muted" style="margin-top:12px;">Jika anda tidak menjangka emel ini, sila abaikan.</p>
  </div>
</body>
</html>
