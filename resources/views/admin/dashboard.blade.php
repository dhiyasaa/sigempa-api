@extends('admin.layout')

@section('content')

<h2 class="mb-4">Dashboard SiGempa</h2>

<div class="row">

    <div class="col-md-4 mb-3">
        <div class="card shadow-sm">
            <div class="card-body text-center">
                <h6 class="text-muted">Total Data Gempa</h6>
                <h2 class="text-primary">{{ $total ?? 0 }}</h2>
            </div>
        </div>
    </div>

</div>

<div class="card shadow-sm mt-3">
    <div class="card-header">
        <strong>Data Gempa Terbaru</strong>
    </div>

    <div class="card-body">

        <table class="table table-bordered table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>Tanggal</th>
                    <th>Wilayah</th>
                    <th>Magnitudo</th>
                    <th>Kedalaman</th>
                </tr>
            </thead>

            <tbody>

            @forelse($latest as $g)
                <tr>
                    <td>{{ $g->tanggal }}</td>
                    <td>{{ $g->wilayah }}</td>
                    <td>{{ $g->magnitudo }}</td>
                    <td>{{ $g->kedalaman }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center">
                        Belum ada data gempa.
                    </td>
                </tr>
            @endforelse

            </tbody>

        </table>

    </div>

</div>

@endsection