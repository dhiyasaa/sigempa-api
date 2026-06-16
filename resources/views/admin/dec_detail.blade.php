@extends('admin.layout')

@section('content')

<h4>Detail Perhitungan DEC</h4>

<div class="card p-3 mb-3">
    <b>DATA INPUT</b><br>
    Magnitudo: {{ $gempa->magnitudo }}<br>
    Kedalaman: {{ $gempa->kedalaman }}<br>
</div>

<div class="card p-3 mb-3">
    <b>NORMALISASI</b><br>
    M = {{ number_format($mNorm, 3) }}<br>
    D = {{ number_format($dNorm, 3) }}
</div>

<div class="card p-3 mb-3">
    <b>CENTROID</b><br>
    Tinggi = ({{ $c_tinggi[0] }}, {{ $c_tinggi[1] }})<br>
    Sedang = ({{ $c_sedang[0] }}, {{ $c_sedang[1] }})<br>
    Rendah = ({{ $c_rendah[0] }}, {{ $c_rendah[1] }})
</div>

<div class="card p-3 mb-3">
    <b>JARAK</b><br>
    Tinggi = {{ number_format($dTinggi, 3) }}<br>
    Sedang = {{ number_format($dSedang, 3) }}<br>
    Rendah = {{ number_format($dRendah, 3) }}
</div>

<div class="card p-3 mb-3"
     style="
        background: {{ $color }}20;
        border: 1px solid {{ $color }};
        border-left: 6px solid {{ $color }};
     ">
    <b>HASIL</b><br>
    Cluster = {{ $cluster }}<br>
    Status = <b>{{ $status }}</b>
</div>

@endsection