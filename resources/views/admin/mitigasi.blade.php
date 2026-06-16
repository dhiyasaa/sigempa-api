@extends('admin.layout')

@section('content')

<h2>Mitigasi Gempa</h2>

@if($gempaTerbaru)
<div class="card">
    <h3>Data Gempa Terbaru</h3>

    <table>
        <tr>
            <th>Tanggal</th>
            <th>Jam</th>
            <th>Magnitudo</th>
            <th>Kedalaman</th>
            <th>Wilayah</th>
            <th>Status</th>
        </tr>
        <tr>
            <td>{{ $gempaTerbaru->tanggal }}</td>
            <td>{{ $gempaTerbaru->jam }}</td>
            <td>{{ $gempaTerbaru->magnitudo }}</td>
            <td>{{ $gempaTerbaru->kedalaman }}</td>
            <td>{{ $gempaTerbaru->wilayah }}</td>
            <td>
                <span style="
                    background: {{ $gempaTerbaru->color ?? '#6c757d' }};
                    color:white;
                    padding:6px 12px;
                    border-radius:20px;
                    font-size:12px;
                    font-weight:600;
                ">
                    {{ $gempaTerbaru->status ?? '-' }}
                </span>
            </td>
        </tr>
    </table>
</div>

<div class="card">
    <h3>Rekomendasi Mitigasi</h3>

    @if($gempaTerbaru->status == 'AMAN')
        <div style="border-left:6px solid #28a745; padding-left:15px;">
            <h3 style="color:#28a745;">AMAN - Bahaya Rendah</h3>
            <ul>
                <li>Tetap tenang dan tidak perlu melakukan evakuasi.</li>
                <li>Pantau informasi resmi dari BMKG.</li>
                <li>Periksa kondisi lingkungan sekitar.</li>
                <li>Lanjutkan aktivitas normal dengan tetap waspada.</li>
            </ul>
        </div>
    @elseif($gempaTerbaru->status == 'WASPADA')
        <div style="border-left:6px solid #ffc107; padding-left:15px;">
            <h3 style="color:#d39e00;">WASPADA - Bahaya Sedang</h3>
            <ul>
                <li>Siapkan tas darurat dan perlengkapan penting.</li>
                <li>Ketahui jalur evakuasi terdekat.</li>
                <li>Jauhi kaca, lemari, dan benda yang mudah jatuh.</li>
                <li>Pantau perkembangan informasi resmi dari BMKG.</li>
            </ul>
        </div>
    @elseif($gempaTerbaru->status == 'SIAGA')
        <div style="border-left:6px solid #dc3545; padding-left:15px;">
            <h3 style="color:#dc3545;">SIAGA - Bahaya Tinggi</h3>
            <ul>
                <li>Segera menuju titik aman atau area terbuka.</li>
                <li>Jauhi bangunan tinggi, tebing, pantai, dan area rawan longsor.</li>
                <li>Bawa tas darurat jika memungkinkan.</li>
                <li>Ikuti arahan petugas dan informasi resmi BMKG.</li>
                <li>Bantu keluarga dan orang sekitar yang membutuhkan pertolongan.</li>
            </ul>
        </div>
    @else
        <p>Status gempa belum tersedia.</p>
    @endif
</div>
@else
<div class="card">
    Belum ada data gempa BMKG terbaru.
</div>
@endif

<div class="card">
    <h3>Daftar Mitigasi Berdasarkan Status</h3>

    <table>
        <tr>
            <th>Status</th>
            <th>Tingkat Risiko</th>
            <th>Rekomendasi</th>
        </tr>
        <tr>
            <td>
                <span style="background:#28a745;color:white;padding:6px 12px;border-radius:20px;">
                    AMAN
                </span>
            </td>
            <td>Bahaya Rendah</td>
            <td>Tidak perlu evakuasi, pantau BMKG, cek lingkungan sekitar.</td>
        </tr>
        <tr>
            <td>
                <span style="background:#ffc107;color:white;padding:6px 12px;border-radius:20px;">
                    WASPADA
                </span>
            </td>
            <td>Bahaya Sedang</td>
            <td>Siapkan jalur evakuasi, tas darurat, dan pantau informasi resmi.</td>
        </tr>
        <tr>
            <td>
                <span style="background:#dc3545;color:white;padding:6px 12px;border-radius:20px;">
                    SIAGA
                </span>
            </td>
            <td>Bahaya Tinggi</td>
            <td>Segera menuju titik aman dan ikuti arahan petugas.</td>
        </tr>
    </table>
</div>

@endsection