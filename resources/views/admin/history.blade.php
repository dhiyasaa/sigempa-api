@extends('admin.layout')

@section('content')

<h2>History Gempa</h2>

@if(session('success'))
    <div class="alert-success">
        {{ session('success') }}
    </div>
@endif

<div style="margin-bottom:15px; display:flex; gap:10px;">

    <a href="{{ route('admin.refresh') }}" class="btn btn-refresh">
        🔄 Refresh Data BMKG
    </a>

    <a href="{{ route('admin.autoFetch') }}" class="btn btn-upload">
        🟢 Auto Fetch ON
    </a>

</div>

<div class="card">
    <table>
        <tr>
            <th>Tanggal</th>
            <th>Jam</th>
            <th>Magnitudo</th>
            <th>Kedalaman</th>
            <th>Wilayah</th>
            <th>Status</th>
            <th>Aksi</th>
        </tr>

        @forelse($data as $g)
            <tr>
                <td>{{ $g['tanggal'] }}</td>
                <td>{{ $g['jam'] }}</td>
                <td>{{ $g['magnitudo'] }}</td>
                <td>{{ $g['kedalaman'] }} km</td>
                <td>{{ $g['wilayah'] }}</td>

                <td>
                    <span style="
                        background: {{ $g['color'] }};
                        color:white;
                        padding:6px 12px;
                        border-radius:20px;
                        font-size:12px;
                        font-weight:600;
                    ">
                        {{ $g['status'] }}
                    </span>
                </td>

                <td>
                    <div class="action-row">
                        <a href="{{ route('admin.dec.detail', $g['id']) }}" class="btn btn-dec">
                            Detail
                        </a>

                        <form action="{{ route('admin.gempa.delete', $g['id']) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')

                            <button type="submit" class="btn btn-delete">
                                Hapus
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="7">Belum ada history gempa BMKG.</td>
            </tr>
        @endforelse
    </table>
</div>

@endsection