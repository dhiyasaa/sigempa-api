@extends('admin.layout')

@section('content')

<h3 class="mb-4">Detail Proses Deep Embedded Clustering (DEC)</h3>

<div class="card shadow-sm mb-4">
    <div class="card-header bg-primary text-white">
        Data Input Gempa
    </div>
    <div class="card-body">
        <table class="table table-bordered mb-0">
            <tr>
                <th width="220">Magnitudo</th>
                <td>{{ $input['magnitudo'] }}</td>
            </tr>
            <tr>
                <th>Kedalaman</th>
                <td>{{ $input['kedalaman'] }} km</td>
            </tr>
        </table>
    </div>
</div>

<div class="card shadow-sm mb-4">
    <div class="card-header bg-info text-white">
        Hasil Normalisasi
    </div>
    <div class="card-body">

        <table class="table table-bordered">

            <tr>
                <th>Magnitudo</th>
                <td>{{ number_format($normalized['magnitudo'],6) }}</td>
            </tr>

            <tr>
                <th>Kedalaman</th>
                <td>{{ number_format($normalized['kedalaman'],6) }}</td>
            </tr>

        </table>

    </div>
</div>

<div class="card shadow-sm mb-4">
    <div class="card-header bg-secondary text-white">
        Output Encoder (Latent Representation)
    </div>

    <div class="card-body">

        <table class="table table-bordered">

            <thead>
                <tr>
                    <th>Dimensi</th>
                    <th>Nilai</th>
                </tr>
            </thead>

            <tbody>

                @foreach($latent as $i => $z)

                <tr>
                    <td>Z{{ $i+1 }}</td>
                    <td>{{ number_format($z,6) }}</td>
                </tr>

                @endforeach

            </tbody>

        </table>

    </div>
</div>

<div class="card shadow-sm mb-4">
    <div class="card-header bg-warning">
        Centroid Hasil Training DEC
    </div>

    <div class="card-body">

        <table class="table table-bordered">

            <thead>

                <tr>
                    <th>Cluster</th>
                    <th>Koordinat</th>
                </tr>

            </thead>

            <tbody>

                @foreach($centroids as $i => $row)

                <tr>

                    <td>Cluster {{ $i }}</td>

                    <td>

                        (

                        @foreach($row as $j=>$v)

                            {{ number_format($v,6) }}

                            @if(!$loop->last)

                                ,

                            @endif

                        @endforeach

                        )

                    </td>

                </tr>

                @endforeach

            </tbody>

        </table>

    </div>
</div>

<div class="card shadow-sm mb-4">

    <div class="card-header bg-dark text-white">
        Probabilitas Soft Assignment DEC
    </div>

    <div class="card-body">

        @foreach($probability as $i=>$p)

            <label>
                Cluster {{ $i }}
                ({{ number_format($p*100,2) }}%)
            </label>

            <div class="progress mb-3">

                <div
                    class="progress-bar"

                    style="width:{{ $p*100 }}%;">

                    {{ number_format($p*100,2) }}%

                </div>

            </div>

        @endforeach

    </div>

</div>

<div class="card shadow-lg border-0">

    <div
        class="card-header text-white"
        style="background:{{ $color }}">

        Hasil Prediksi DEC

    </div>

    <div class="card-body">

        <table class="table table-bordered">

            <tr>

                <th width="220">
                    Cluster
                </th>

                <td>
                    {{ $cluster }}
                </td>

            </tr>

            <tr>

                <th>
                    Label
                </th>

                <td>
                    {{ $label }}
                </td>

            </tr>

            <tr>

                <th>
                    Status Risiko
                </th>

                <td>

                    <span
                        class="badge"
                        style="background:{{ $color }}">

                        {{ $status }}

                    </span>

                </td>

            </tr>

        </table>

    </div>

</div>

@endsection