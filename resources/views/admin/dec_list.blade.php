@extends('admin.layout')

@section('content')
<h4>Perhitungan DEC</h4>

<table class="table table-bordered">
<tr>
    <th>Tanggal</th>
    <th>Magnitudo</th>
    <th>Kedalaman</th>
    <th>Aksi</th>
</tr>

@foreach($data as $g)
<tr>
    <td>{{$g->tanggal}}</td>
    <td>{{$g->magnitudo}}</td>
    <td>{{$g->kedalaman}}</td>
    <td>
        <a href="{{route('admin.dec.detail',$g->id)}}" class="btn btn-primary btn-sm">
            Lihat Perhitungan
        </a>
    </td>
</tr>
@endforeach

</table>
@endsection