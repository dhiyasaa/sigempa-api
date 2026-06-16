@extends('admin.layout')

@section('content')

<h2>History Gempa</h2>

@if(session('success'))
    <div class="alert-success">
        {{ session('success') }}
    </div>
@endif

<div id="autoFetchAlert"
     style="display:none; margin-bottom:15px; background:#dcfce7; color:#166534; padding:12px 16px; border-radius:8px; font-weight:500;">
    Auto Fetch aktif. Sistem mengambil data BMKG setiap 15 detik selama halaman ini dibuka.
</div>

<div style="margin-bottom:15px; display:flex; gap:10px; align-items:center; flex-wrap:wrap;">

    <a href="{{ route('admin.refresh') }}" class="btn btn-refresh">
        🔄 Refresh Data BMKG
    </a>

    <button type="button"
            id="autoFetchBtn"
            onclick="toggleAutoFetch()"
            class="btn btn-upload"
            style="border:none; cursor:pointer;">
        ⚪ Auto Fetch OFF
    </button>

    <span id="autoFetchStatus"
          style="font-size:13px; color:#6b7280; font-weight:500;">
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
            autoFetchBtn.style.background = '#22c55e';
            autoFetchBtn.style.color = 'white';
            autoFetchAlert.style.display = 'block';
            autoFetchStatus.innerText = 'Aktif, cek BMKG tiap 15 detik';
        } else {
            autoFetchBtn.innerText = '⚪ Auto Fetch OFF';
            autoFetchBtn.style.background = '#6b7280';
            autoFetchBtn.style.color = 'white';
            autoFetchAlert.style.display = 'none';
            autoFetchStatus.innerText = 'Nonaktif';
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

        autoFetchStatus.innerText = 'Mengambil data BMKG...';

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

                autoFetchStatus.innerText = 'Terakhir cek: ' + jam;

                console.log('Auto Fetch:', data);

                if (data.has_new_data) {
                    localStorage.setItem('autoFetchGempa', 'ON');
                    window.location.reload();
                }
            })
            .catch(error => {
                console.error('Auto Fetch Error:', error);
                autoFetchStatus.innerText = 'Auto fetch gagal. Cek koneksi/server.';
            });
    }

    updateAutoFetchView();

    if (autoFetchActive) {
        startAutoFetch();
    }
</script>

@endsection