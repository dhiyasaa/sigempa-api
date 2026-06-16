@extends('admin.layout')

@section('content')

<h2>Dashboard</h2>

<div class="card">
    <div>Total Gempa</div>
    <div class="stat">{{ $total }}</div>
</div>

<div class="card">
    <h4>Data Terbaru</h4>

    @foreach($latest as $g)
        <p>
            {{ $g->wilayah }} <br>
            <b>M {{ $g->magnitudo }}</b>
        </p>
        <hr>
    @endforeach
</div>

@endsection