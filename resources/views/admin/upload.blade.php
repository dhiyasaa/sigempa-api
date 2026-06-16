@extends('admin.layout')

@section('content')

<h2>Upload Excel Gempa</h2>

@if(session('success'))
    <div class="alert-success">{{ session('success') }}</div>
@endif

@if(session('error'))
    <div style="background:#f8d7da;color:#721c24;padding:10px;border-radius:10px;margin-bottom:15px;">
        {{ session('error') }}
    </div>
@endif

<div class="card">
    <h3>1. Upload File Excel</h3>

    <form action="{{ route('admin.upload.preview') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <input type="file" name="file" accept=".xlsx,.xls,.csv" required>

        <br><br>

        <button type="submit" class="btn btn-upload">
            Upload & Preview
        </button>
    </form>

    <br>

    <p><b>Format Excel:</b></p>

    <table>
        <tr>
            <th>tanggal</th>
            <th>jam</th>
            <th>lintang</th>
            <th>bujur</th>
            <th>magnitudo</th>
            <th>kedalaman</th>
            <th>wilayah</th>
            <th>potensi</th>
        </tr>
        <tr>
            <td>07 Mei 2026</td>
            <td>13:17:45 WIB</td>
            <td>4.12 LS</td>
            <td>122.45 BT</td>
            <td>4.5</td>
            <td>10 km</td>
            <td>Pusat gempa berada...</td>
            <td>-</td>
        </tr>
    </table>
</div>

@if($totalData > 0)

<div class="card">
    <h3>2. Ringkasan Data Upload</h3>

    <p>Total data upload: <b>{{ $totalData }}</b></p>
    <p>Data sudah diproses DEC: <b>{{ $processedCount }}</b></p>
</div>

<div class="card">
    <h3>3. Preview 50 Data Pertama</h3>

    <table>
        <tr>
            <th>No</th>
            <th>Tanggal</th>
            <th>Jam</th>
            <th>Magnitudo</th>
            <th>Kedalaman</th>
            <th>Wilayah</th>
            <th>Status</th>
        </tr>

        @foreach($previewData as $i => $g)
        <tr>
            <td>{{ $i + 1 }}</td>
            <td>{{ $g->tanggal }}</td>
            <td>{{ $g->jam }}</td>
            <td>{{ $g->magnitudo }}</td>
            <td>{{ $g->kedalaman }}</td>
            <td>{{ $g->wilayah }}</td>
            <td>
                @if($g->status)
                    <span style="
                        background: {{ $g->color }};
                        color:white;
                        padding:6px 12px;
                        border-radius:20px;
                        font-size:12px;
                        font-weight:600;
                    ">
                        {{ $g->status }}
                    </span>
                @else
                    Belum diproses
                @endif
            </td>
        </tr>
        @endforeach
    </table>
</div>

<div class="card">
    <h3>4. Diagram Data Upload</h3>

    @php
        $total = $totalData;
        $rendahPct = $total ? ($rendah / $total) * 100 : 0;
        $sedangPct = $total ? ($sedang / $total) * 100 : 0;
        $tinggiPct = $total ? ($tinggi / $total) * 100 : 0;
    @endphp

    <p><b>Distribusi berdasarkan magnitudo awal:</b></p>

    <div style="margin-bottom:15px;">
        <b>Rendah (&lt; 4.5): {{ $rendah }}</b>
        <div style="background:#eee;border-radius:10px;overflow:hidden;">
            <div style="width:{{ $rendahPct }}%;background:#28a745;color:white;padding:8px;">
                {{ round($rendahPct,1) }}%
            </div>
        </div>
    </div>

    <div style="margin-bottom:15px;">
        <b>Sedang (4.5 - 5.4): {{ $sedang }}</b>
        <div style="background:#eee;border-radius:10px;overflow:hidden;">
            <div style="width:{{ $sedangPct }}%;background:#ffc107;color:black;padding:8px;">
                {{ round($sedangPct,1) }}%
            </div>
        </div>
    </div>

    <div style="margin-bottom:15px;">
        <b>Tinggi (&ge; 5.5): {{ $tinggi }}</b>
        <div style="background:#eee;border-radius:10px;overflow:hidden;">
            <div style="width:{{ $tinggiPct }}%;background:#dc3545;color:white;padding:8px;">
                {{ round($tinggiPct,1) }}%
            </div>
        </div>
    </div>
</div>

<div class="card">
    <h3>5. Proses DEC</h3>

    <form action="{{ route('admin.upload.processDec') }}" method="POST" style="display:inline;">
        @csrf
        <button type="submit" class="btn btn-dec">
            Proses DEC Semua Data
        </button>
    </form>

    <form action="{{ route('admin.upload.clear') }}" method="POST" style="display:inline;">
        @csrf
        <button type="submit" class="btn btn-delete">
            Bersihkan Upload
        </button>
    </form>
</div>

@if($processedCount > 0)
<div class="card">
    <h3>6. Hasil DEC</h3>

    <p>
        AMAN: <b>{{ $aman }}</b> |
        WASPADA: <b>{{ $waspada }}</b> |
        SIAGA: <b>{{ $siaga }}</b>
    </p>

    <form action="{{ route('admin.upload.save') }}" method="POST">
        @csrf
        <button type="submit" class="btn btn-upload">
            Masukkan ke Data Gempa
        </button>
    </form>
</div>
@endif

@endif

@endsection