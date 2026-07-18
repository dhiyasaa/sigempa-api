@extends('admin.layout')

@section('content')

<h4>Detail Perhitungan DEC</h4>

<div class="card p-3 mb-3">
    <b>DATA INPUT</b><br>
    Magnitudo : {{ $gempa->magnitudo }}<br>
    Kedalaman : {{ $gempa->kedalaman }}
</div>

<div class="card p-3 mb-3">
    <b>NORMALISASI</b><br>
    M = {{ number_format($mNorm,3) }}<br>
    D = {{ number_format($dNorm,3) }}
</div>

<div class="card p-3 mb-3">
    <b>PROSES DEEP EMBEDDED CLUSTERING (DEC)</b>

    <div class="text-center mt-4 mb-2" style="font-size:18px; line-height:2;">
        Input Gempa
        <br>↓<br>

        Normalisasi
        <br>↓<br>

        Encoder
        <br>↓<br>

        Deep Embedded Clustering
        <br>↓<br>

        Prediksi Cluster
    </div>

    <hr>

    <small class="text-muted">
        Model melakukan klasifikasi menggunakan encoder dan model
        Deep Embedded Clustering (DEC) yang telah dilatih sebelumnya.
        Hasil prediksi diperoleh langsung dari model tanpa menghitung
        jarak centroid secara manual.
    </small>
</div>

<div class="card p-3 mb-3"
     style="
        background: {{ $color }}20;
        border:1px solid {{ $color }};
        border-left:6px solid {{ $color }};
     ">

    <b>HASIL PREDIKSI</b><br><br>

    <b>Cluster</b><br>
    {{ $cluster }}

    <br><br>

    <b>Status Risiko</b><br>
    <span style="color:{{ $color }}">
        <b>{{ $status }}</b>
    </span>

</div>

@endsection