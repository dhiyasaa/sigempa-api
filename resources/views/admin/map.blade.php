@extends('admin.layout')

@section('content')

<h2>Peta Gempa</h2>

<div id="map" style="height:500px;"></div>

<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css"/>
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

<script>
var map = L.map('map').setView([-2.5, 118], 5);

L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);

var data = @json($data);

data.forEach(g => {

    let lat = parseFloat(g.lintang);
    if(g.lintang.includes('LS')) lat *= -1;

    let lng = parseFloat(g.bujur);
    if(g.bujur.includes('BB')) lng *= -1;

    L.marker([lat, lng])
        .addTo(map)
        .bindPopup(g.wilayah + "<br>M: " + g.magnitudo);
});
</script>

@endsection