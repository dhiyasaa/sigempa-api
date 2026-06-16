@extends('admin.layout')

@section('content')

<h2>Umpan Balik Pengguna</h2>

@if(session('success'))
    <div class="alert-success">{{ session('success') }}</div>
@endif

<div class="card">
    <table>
        <tr>
            <th>Nama</th>
            <th>Email</th>
            <th>Kategori</th>
            <th>Pesan</th>
            <th>Tanggal</th>
            <th>Aksi</th>
        </tr>

        @forelse($data as $u)
            <tr>
                <td>{{ $u->nama ?? '-' }}</td>
                <td>{{ $u->email ?? '-' }}</td>
                <td>{{ $u->kategori ?? '-' }}</td>
                <td>{{ $u->pesan }}</td>
                <td>{{ $u->created_at }}</td>
                <td>
                    <form action="{{ route('admin.umpanBalik.delete', $u->id) }}" method="POST">
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
                <td colspan="6">Belum ada umpan balik.</td>
            </tr>
        @endforelse
    </table>
</div>

@endsection