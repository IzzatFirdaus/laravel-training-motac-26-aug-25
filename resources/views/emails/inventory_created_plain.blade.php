{{ config('app.name') }}
{{ url('/') }}

Subjek: Inventori baru dicipta — {{ $inventory->name ?? '(tiada nama)' }}

Ringkasan:
- Nama: {{ $inventory->name ?? '—' }}
- Kuantiti: {{ isset($inventory->qty) ? $inventory->qty : '—' }}
- Harga: {{ isset($inventory->price) && is_numeric($inventory->price) ? 'RM ' . number_format($inventory->price, 2) : '—' }}
- Dicipta pada: {{ isset($inventory->created_at) ? \Illuminate\Support\Carbon::parse($inventory->created_at)->format('j M Y H:i') : '—' }}

@if(! empty($inventory->description))
Keterangan:
{{ $inventory->description }}
@endif

Lihat inventori: {{ route('inventories.show', $inventory->getKey()) }}

Jika anda tidak menjangka emel ini, sila abaikan mesej ini atau hubungi pentadbir sistem.
Salam hormat,
{{ config('app.name') }}
