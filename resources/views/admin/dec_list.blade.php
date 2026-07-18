@extends('admin.layout')

@section('content')

<h4>Perhitungan Deep Embedded Clustering (DEC)</h4>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>Tanggal</th>
            <th>Magnitudo</th>
            <th>Kedalaman</th>
            <th>Aksi</th>
        </tr>
    </thead>

    <tbody>

    @foreach($data as $g)
        <tr>
            <td>{{ $g->tanggal }}</td>
            <td>{{ $g->magnitudo }}</td>
            <td>{{ $g->kedalaman }}</td>
            <td>
                <a href="{{ route('admin.dec.detail',$g->id) }}"
                   class="btn btn-primary btn-sm">
                    Lihat Detail
                </a>
            </td>
        </tr>
    @endforeach

    </tbody>

</table>

@endsection