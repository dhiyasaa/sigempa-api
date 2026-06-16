@extends('admin.layout')

@section('content')

<style>
    .top-action-row {
        margin-bottom: 15px;
        display: flex;
        gap: 12px;
        align-items: center;
        flex-wrap: wrap;
    }

    .action-btn-custom {
        height: 44px;
        min-width: 170px;
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

    .btn-blue-custom {
        background: #2563eb;
        color: white;
    }

    .btn-red-custom {
        background: #ff4757;
        color: white;
    }

    .worker-info-box {
        margin-bottom: 15px;
        padding: 12px 16px;
        border-radius: 8px;
        background: #dcfce7;
        color: #166534;
        font-weight: 500;
        line-height: 1.5;
    }

    .worker-badge {
        height: 44px;
        min-width: 150px;
        padding: 0 14px;
        border-radius: 8px;
        background: #dcfce7;
        color: #166534;
        font-size: 14px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        box-sizing: border-box;
    }
</style>

<h2>History Gempa</h2>

@if(session('success'))
    <div class="alert-success">
        {{ session('success') }}
    </div>
@endif

<div class="worker-info-box">
    Auto Fetch dijalankan oleh Railway Worker dengan command
    <b>php artisan gempa:auto-fetch</b>.
    Sistem akan mengambil data BMKG setiap 15 detik selama service worker aktif.
</div>

<div class="top-action-row">

    <a href="{{ route('admin.refresh') }}" class="action-btn-custom btn-blue-custom">
        🔄 Refresh Data BMKG
    </a>

    <a href="{{ route('admin.dummyGempa.create') }}" class="action-btn-custom btn-red-custom">
        🚨 Tambah Data Dummy
    </a>

    <span class="worker-badge">
        🟢 Worker Auto Fetch
    </span>

</div>

<div class="card">
    <table>
        <tr>
            <th>Tanggal</th>
            <th>Jam</th>
            <th>Magnitudo</th>
            <th>Kedalaman</th>
            <th>Wilayah</th>
            <th>Status</th>
            <th>Aksi</th>
        </tr>

        @forelse($data as $g)
            <tr>
                <td>{{ $g['tanggal'] }}</td>
                <td>{{ $g['jam'] }}</td>
                <td>{{ $g['magnitudo'] }}</td>
                <td>{{ $g['kedalaman'] }}</td>
                <td>{{ $g['wilayah'] }}</td>

                <td>
                    <span style="
                        background: {{ $g['color'] ?? '#6b7280' }};
                        color: {{ strtoupper($g['status'] ?? '') === 'WASPADA' ? '#3F3200' : 'white' }};
                        padding:6px 12px;
                        border-radius:20px;
                        font-size:12px;
                        font-weight:600;
                    ">
                        {{ $g['status'] }}
                    </span>
                </td>

                <td>
                    <div class="action-row">
                        <a href="{{ route('admin.dec.detail', $g['id']) }}" class="btn btn-dec">
                            Detail
                        </a>

                        <form action="{{ route('admin.gempa.delete', $g['id']) }}"
                              method="POST"
                              style="display:inline;"
                              onsubmit="return confirm('Yakin ingin menghapus data gempa ini?')">
                            @csrf
                            @method('DELETE')

                            <button type="submit" class="btn btn-delete">
                                Hapus
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="7">Belum ada history gempa BMKG.</td>
            </tr>
        @endforelse
    </table>
</div>

@endsection