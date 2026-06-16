@extends('admin.layout')

@section('content')

<h2>Data Gempa</h2>

@if(session('success'))
    <div class="alert-success">
        {{ session('success') }}
    </div>
@endif

<a href="{{ route('admin.upload') }}" class="btn btn-upload">
    📂 Upload Excel
</a>

<div class="card">
    <table>
        <tr>
            <th>Tanggal</th>
            <th>Jam</th>
            <th>Magnitudo</th>
            <th>Kedalaman</th>
            <th>Wilayah</th>
            <th>Sumber</th>
            <th>Status</th>
            <th>Aksi</th>
        </tr>

        @forelse($data as $g)
            <tr>
                <td>{{ $g->tanggal }}</td>
                <td>{{ $g->jam }}</td>
                <td>{{ $g->magnitudo }}</td>
                <td>{{ $g->kedalaman }}</td>
                <td>{{ $g->wilayah }}</td>
                <td>{{ $g->source ?? '-' }}</td>

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
                        -
                    @endif
                </td>

                <td>
                    <form action="{{ route('admin.gempa.delete', $g->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')

                        <button type="submit" class="btn btn-delete">
                            Hapus
                        </button>
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="8">Belum ada data gempa.</td>
            </tr>
        @endforelse
    </table>
</div>

@endsection