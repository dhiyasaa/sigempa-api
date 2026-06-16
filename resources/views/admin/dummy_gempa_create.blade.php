@extends('admin.layout')

@section('content')

<h2>Tambah Data Dummy Gempa</h2>

@if ($errors->any())
    <div class="alert-success" style="background:#fee2e2; color:#991b1b;">
        <b>Data belum lengkap:</b>
        <ul style="margin:8px 0 0 18px;">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="card">
    <form action="{{ route('admin.dummyGempa.store') }}" method="POST">
        @csrf

        <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px;">

            <div>
                <label style="font-weight:600;">Tanggal</label>
                <input type="text"
                       name="tanggal"
                       value="{{ old('tanggal', now('Asia/Jakarta')->format('d M Y')) }}"
                       placeholder="Contoh: 16 Jun 2026"
                       style="width:100%; padding:10px; margin-top:6px; border:1px solid #ddd; border-radius:8px;">
            </div>

            <div>
                <label style="font-weight:600;">Jam</label>
                <input type="text"
                       name="jam"
                       value="{{ old('jam', now('Asia/Jakarta')->format('H:i:s') . ' WIB') }}"
                       placeholder="Contoh: 22:40:00 WIB"
                       style="width:100%; padding:10px; margin-top:6px; border:1px solid #ddd; border-radius:8px;">
            </div>

            <div>
                <label style="font-weight:600;">Lintang</label>
                <input type="text"
                       name="lintang"
                       value="{{ old('lintang', '0.281 LS') }}"
                       placeholder="Contoh: 0.281 LS"
                       style="width:100%; padding:10px; margin-top:6px; border:1px solid #ddd; border-radius:8px;">
                <small style="color:#6b7280;">Untuk uji dekat lokasi kamu: 0.281 LS</small>
            </div>

            <div>
                <label style="font-weight:600;">Bujur</label>
                <input type="text"
                       name="bujur"
                       value="{{ old('bujur', '100.446 BT') }}"
                       placeholder="Contoh: 100.446 BT"
                       style="width:100%; padding:10px; margin-top:6px; border:1px solid #ddd; border-radius:8px;">
                <small style="color:#6b7280;">Untuk uji dekat lokasi kamu: 100.446 BT</small>
            </div>

            <div>
                <label style="font-weight:600;">Magnitudo</label>
                <input type="text"
                       name="magnitudo"
                       value="{{ old('magnitudo', '6.4') }}"
                       placeholder="Contoh: 6.4"
                       style="width:100%; padding:10px; margin-top:6px; border:1px solid #ddd; border-radius:8px;">
            </div>

            <div>
                <label style="font-weight:600;">Kedalaman</label>
                <input type="text"
                       name="kedalaman"
                       value="{{ old('kedalaman', '10 km') }}"
                       placeholder="Contoh: 10 km"
                       style="width:100%; padding:10px; margin-top:6px; border:1px solid #ddd; border-radius:8px;">
            </div>

            <div>
                <label style="font-weight:600;">Status Risiko</label>
                <select name="status"
                        style="width:100%; padding:10px; margin-top:6px; border:1px solid #ddd; border-radius:8px;">
                    <option value="SIAGA" {{ old('status', 'SIAGA') == 'SIAGA' ? 'selected' : '' }}>
                        SIAGA
                    </option>
                    <option value="WASPADA" {{ old('status') == 'WASPADA' ? 'selected' : '' }}>
                        WASPADA
                    </option>
                    <option value="AMAN" {{ old('status') == 'AMAN' ? 'selected' : '' }}>
                        AMAN
                    </option>
                </select>
            </div>

            <div>
                <label style="font-weight:600;">Source</label>
                <input type="text"
                       value="DUMMY"
                       disabled
                       style="width:100%; padding:10px; margin-top:6px; border:1px solid #ddd; border-radius:8px; background:#f3f4f6;">
            </div>

        </div>

        <div style="margin-top:16px;">
            <label style="font-weight:600;">Wilayah</label>
            <input type="text"
                   name="wilayah"
                   value="{{ old('wilayah', '8 km Barat Daya Padang (DATA UJI NOTIF)') }}"
                   placeholder="Contoh: 8 km Barat Daya Padang"
                   style="width:100%; padding:10px; margin-top:6px; border:1px solid #ddd; border-radius:8px;">
        </div>

        <div style="margin-top:16px;">
            <label style="font-weight:600;">Potensi</label>
            <textarea name="potensi"
                      rows="3"
                      placeholder="Contoh: Gempa dirasakan dan perlu diteruskan kepada masyarakat"
                      style="width:100%; padding:10px; margin-top:6px; border:1px solid #ddd; border-radius:8px;">{{ old('potensi', 'Gempa dirasakan dan perlu diteruskan kepada masyarakat') }}</textarea>
        </div>

        <div style="margin-top:22px; display:flex; gap:10px; flex-wrap:wrap;">
            <button type="submit"
                    class="btn btn-refresh"
                    style="border:none; cursor:pointer;">
                Simpan Data Dummy
            </button>

            <a href="{{ route('admin.history') }}" class="btn btn-delete">
                Batal
            </a>
        </div>
    </form>
</div>

@endsection