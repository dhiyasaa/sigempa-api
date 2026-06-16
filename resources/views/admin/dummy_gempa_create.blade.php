@extends('admin.layout')

@section('content')

<style>
    .dummy-form-card {
        background: white;
        border-radius: 14px;
        padding: 24px;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.06);
    }

    .dummy-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px;
    }

    .dummy-form-group label {
        font-weight: 600;
        color: #111827;
        font-size: 14px;
    }

    .dummy-form-group input,
    .dummy-form-group select,
    .dummy-form-group textarea {
        width: 100%;
        padding: 11px 12px;
        margin-top: 6px;
        border: 1px solid #d1d5db;
        border-radius: 8px;
        font-size: 14px;
        box-sizing: border-box;
        font-family: inherit;
        outline: none;
    }

    .dummy-form-group input:focus,
    .dummy-form-group select:focus,
    .dummy-form-group textarea:focus {
        border-color: #675EF7;
        box-shadow: 0 0 0 3px rgba(103, 94, 247, 0.12);
    }

    .dummy-form-group small {
        display: block;
        color: #6b7280;
        margin-top: 5px;
        font-size: 12px;
    }

    .dummy-source-input {
        background: #f3f4f6;
        color: #6b7280;
    }

    .dummy-action-row {
        margin-top: 22px;
        display: flex;
        gap: 12px;
        align-items: center;
        flex-wrap: wrap;
    }

    .dummy-action-btn {
        height: 46px;
        min-width: 190px;
        padding: 0 18px;
        border-radius: 8px;
        border: none;
        text-decoration: none;
        font-size: 15px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        box-sizing: border-box;
        line-height: 1;
        font-family: inherit;
    }

    .dummy-btn-blue {
        background: #2563eb;
        color: white;
    }

    .dummy-btn-red {
        background: #ff4757;
        color: white;
    }

    .dummy-error-box {
        background: #fee2e2;
        color: #991b1b;
        padding: 12px 16px;
        border-radius: 8px;
        margin-bottom: 15px;
    }

    .dummy-error-box ul {
        margin: 8px 0 0 18px;
    }

    @media (max-width: 768px) {
        .dummy-grid {
            grid-template-columns: 1fr;
        }

        .dummy-action-btn {
            width: 100%;
        }
    }
</style>

<h2>Tambah Data Dummy Gempa</h2>

@if ($errors->any())
    <div class="dummy-error-box">
        <b>Data belum lengkap:</b>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="dummy-form-card">
    <form action="{{ route('admin.dummyGempa.store') }}" method="POST">
        @csrf

        <div class="dummy-grid">

            <div class="dummy-form-group">
                <label>Tanggal</label>
                <input type="text"
                       name="tanggal"
                       value="{{ old('tanggal', now('Asia/Jakarta')->format('d M Y')) }}"
                       placeholder="Contoh: 16 Jun 2026">
            </div>

            <div class="dummy-form-group">
                <label>Jam</label>
                <input type="text"
                       name="jam"
                       value="{{ old('jam', now('Asia/Jakarta')->format('H:i:s') . ' WIB') }}"
                       placeholder="Contoh: 22:40:00 WIB">
            </div>

            <div class="dummy-form-group">
                <label>Lintang</label>
                <input type="text"
                       name="lintang"
                       value="{{ old('lintang', '0.281 LS') }}"
                       placeholder="Contoh: 0.281 LS">
                <small>Untuk uji dekat lokasi kamu: 0.281 LS</small>
            </div>

            <div class="dummy-form-group">
                <label>Bujur</label>
                <input type="text"
                       name="bujur"
                       value="{{ old('bujur', '100.446 BT') }}"
                       placeholder="Contoh: 100.446 BT">
                <small>Untuk uji dekat lokasi kamu: 100.446 BT</small>
            </div>

            <div class="dummy-form-group">
                <label>Magnitudo</label>
                <input type="text"
                       name="magnitudo"
                       value="{{ old('magnitudo', '6.4') }}"
                       placeholder="Contoh: 6.4">
            </div>

            <div class="dummy-form-group">
                <label>Kedalaman</label>
                <input type="text"
                       name="kedalaman"
                       value="{{ old('kedalaman', '10 km') }}"
                       placeholder="Contoh: 10 km">
            </div>

            <div class="dummy-form-group">
                <label>Status Risiko</label>
                <select name="status">
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

            <div class="dummy-form-group">
                <label>Source</label>
                <input type="text"
                       value="DUMMY"
                       disabled
                       class="dummy-source-input">
            </div>

        </div>

        <div class="dummy-form-group" style="margin-top:16px;">
            <label>Wilayah</label>
            <input type="text"
                   name="wilayah"
                   value="{{ old('wilayah', '8 km Barat Daya Padang (DATA UJI NOTIF)') }}"
                   placeholder="Contoh: 8 km Barat Daya Padang">
        </div>

        <div class="dummy-form-group" style="margin-top:16px;">
            <label>Potensi</label>
            <textarea name="potensi"
                      rows="3"
                      placeholder="Contoh: Gempa dirasakan dan perlu diteruskan kepada masyarakat">{{ old('potensi', 'Gempa dirasakan dan perlu diteruskan kepada masyarakat') }}</textarea>
        </div>

        <div class="dummy-action-row">
            <button type="submit" class="dummy-action-btn dummy-btn-blue">
                Simpan Data Dummy
            </button>

            <a href="{{ route('admin.history') }}" class="dummy-action-btn dummy-btn-red">
                Batal
            </a>
        </div>
    </form>
</div>

@endsection