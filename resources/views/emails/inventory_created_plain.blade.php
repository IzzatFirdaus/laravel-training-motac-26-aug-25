{{ config('app.name') }}
{{ url('/') }}

Subject: Inventori baru dicipta — {{ $inventory->name ?? '(tiada nama)' }}

Ringkasan:
- Nama: {{ $inventory->name ?? '—' }}
- Kuantiti: {{ isset($inventory->qty) ? $inventory->qty : '—' }}
- Harga: {{ isset($inventory->price) && is_numeric($inventory->price) ? 'RM ' . number_format($inventory->price, 2) : '—' }}

@if(! empty($inventory->description))
Description:
{{ $inventory->description }}
@endif

Lihat inventori: {{ url('/inventories/' . $inventory->getKey()) }}

Jika anda tidak menjangka emel ini, sila abaikan.
Regards,
{{ config('app.name') }}
