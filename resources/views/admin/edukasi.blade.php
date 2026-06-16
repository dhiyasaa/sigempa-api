@extends('admin.layout')

@section('content')

<h2>Edukasi Gempa</h2>

@if(session('success'))
    <div class="alert-success">{{ session('success') }}</div>
@endif

<div class="card">
    <h3>Tambah Edukasi</h3>

    <form action="{{ route('admin.edukasi.store') }}" method="POST">
        @csrf

        <input
            type="text"
            name="judul"
            placeholder="Judul edukasi"
            required
            style="width:100%;padding:10px;margin-bottom:10px;border:1px solid #ddd;border-radius:10px;"
        >

        <input
            type="text"
            name="link"
            placeholder="Link artikel / kosongkan jika halaman internal aplikasi"
            style="width:100%;padding:10px;margin-bottom:10px;border:1px solid #ddd;border-radius:10px;"
        >

        <button type="submit" class="btn btn-upload">
            Simpan Edukasi
        </button>
    </form>
</div>

<div class="card">
    <h3>Daftar Edukasi</h3>

    <table>
        <tr>
            <th>Judul</th>
            <th>Link</th>
            <th>Aksi</th>
        </tr>

        @forelse($data as $e)
            <tr>
                <td>{{ $e->judul }}</td>
                <td>
                    @if($e->link)
                        <a href="{{ $e->link }}" target="_blank">Buka</a>
                    @else
                        Halaman internal aplikasi
                    @endif
                </td>
                <td>
                    <form action="{{ route('admin.edukasi.delete', $e->id) }}" method="POST">
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
                <td colspan="3">Belum ada edukasi.</td>
            </tr>
        @endforelse
    </table>
</div>

@endsection