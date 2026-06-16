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

    .btn-green-custom {
        background: #22c55e;
        color: white;
    }

    .btn-gray-custom {
        background: #6b7280;
        color: white;
    }

    .btn-red-custom {
        background: #ff4757;
        color: white;
    }

    .auto-status-badge {
        height: 44px;
        min-width: 105px;
        padding: 0 14px;
        border-radius: 8px;
        background: #f3f4f6;
        color: #6b7280;
        font-size: 14px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        box-sizing: border-box;
    }

    .auto-fetch-alert-custom {
        display: none;
        margin-bottom: 15px;
        background: #dcfce7;
        color: #166534;
        padding: 12px 16px;
        border-radius: 8px;
        font-weight: 500;
    }
</style>

<h2>History Gempa</h2>

@if(session('success'))
    <div class="alert-success">
        {{ session('success') }}
    </div>
@endif

<div id="autoFetchAlert" class="auto-fetch-alert-custom">
    Auto Fetch aktif. Sistem mengambil data BMKG setiap 15 detik selama halaman ini dibuka.
</div>

<div class="top-action-row">

    <a href="{{ route('admin.refresh') }}" class="action-btn-custom btn-blue-custom">
        🔄 Refresh Data BMKG
    </a>

    <button type="button"
            id="autoFetchBtn"
            onclick="toggleAutoFetch()"
            class="action-btn-custom btn-gray-custom">
        ⚪ Auto Fetch OFF
    </button>

    <a href="{{ route('admin.dummyGempa.create') }}" class="action-btn-custom btn-red-custom">
        🚨 Tambah Data Dummy
    </a>

    <span id="autoFetchStatus" class="auto-status-badge">
        Nonaktif
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

<script>
    let autoFetchInterval = null;
    let autoFetchActive = localStorage.getItem('autoFetchGempa') === 'ON';

    const autoFetchBtn = document.getElementById('autoFetchBtn');
    const autoFetchAlert = document.getElementById('autoFetchAlert');
    const autoFetchStatus = document.getElementById('autoFetchStatus');

    function updateAutoFetchView() {
        if (autoFetchActive) {
            autoFetchBtn.innerText = '🟢 Auto Fetch ON';

            autoFetchBtn.classList.remove('btn-gray-custom');
            autoFetchBtn.classList.add('btn-green-custom');

            autoFetchAlert.style.display = 'block';
            autoFetchStatus.innerText = 'Aktif';
            autoFetchStatus.style.background = '#dcfce7';
            autoFetchStatus.style.color = '#166534';
        } else {
            autoFetchBtn.innerText = '⚪ Auto Fetch OFF';

            autoFetchBtn.classList.remove('btn-green-custom');
            autoFetchBtn.classList.add('btn-gray-custom');

            autoFetchAlert.style.display = 'none';
            autoFetchStatus.innerText = 'Nonaktif';
            autoFetchStatus.style.background = '#f3f4f6';
            autoFetchStatus.style.color = '#6b7280';
        }
    }

    function toggleAutoFetch() {
        autoFetchActive = !autoFetchActive;

        if (autoFetchActive) {
            localStorage.setItem('autoFetchGempa', 'ON');
            startAutoFetch();
        } else {
            localStorage.setItem('autoFetchGempa', 'OFF');
            stopAutoFetch();
        }

        updateAutoFetchView();
    }

    function startAutoFetch() {
        stopAutoFetch();

        autoFetchStatus.innerText = 'Cek BMKG...';

        fetchGempa();

        autoFetchInterval = setInterval(function () {
            fetchGempa();
        }, 15000);
    }

    function stopAutoFetch() {
        if (autoFetchInterval !== null) {
            clearInterval(autoFetchInterval);
            autoFetchInterval = null;
        }
    }

    function fetchGempa() {
        fetch("{{ route('admin.refreshJson') }}")
            .then(response => response.json())
            .then(data => {
                const now = new Date();
                const jam = now.toLocaleTimeString('id-ID');

                autoFetchStatus.innerText = 'Cek: ' + jam;

                console.log('Auto Fetch:', data);

                if (data.has_new_data) {
                    localStorage.setItem('autoFetchGempa', 'ON');
                    window.location.reload();
                }
            })
            .catch(error => {
                console.error('Auto Fetch Error:', error);

                autoFetchStatus.innerText = 'Gagal';
                autoFetchStatus.style.background = '#fee2e2';
                autoFetchStatus.style.color = '#991b1b';
            });
    }

    updateAutoFetchView();

    if (autoFetchActive) {
        startAutoFetch();
    }
</script>

@endsection