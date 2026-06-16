@extends('admin.layout')

@section('content')

<h2>Berita Gempa</h2>

@if(session('success'))
    <div class="alert-success">{{ session('success') }}</div>
@endif

@if($errors->any())
    <div style="background:#f8d7da;color:#721c24;padding:10px;border-radius:10px;margin-bottom:15px;">
        {{ $errors->first() }}
    </div>
@endif

<div class="card">
    <h3>Tambah Berita dari Link</h3>

    <form action="{{ route('admin.berita.store') }}" method="POST">
        @csrf

        <input 
            type="url" 
            name="link" 
            placeholder="Masukkan link berita, contoh: https://www.kompas.com/..."
            required
            style="width:100%;padding:10px;margin-bottom:10px;border:1px solid #ddd;border-radius:10px;"
        >

        <button type="submit" class="btn btn-upload">
            Ambil & Simpan Berita
        </button>
    </form>
</div>

<div class="card">
    <h3>Daftar Berita</h3>

    <table>
        <tr>
            <th>Gambar</th>
            <th>Judul</th>
            <th>Sumber</th>
            <th>Tanggal</th>
            <th>Ringkasan</th>
            <th>Link</th>
            <th>Aksi</th>
        </tr>

        @forelse($data as $b)
            <tr>
                <td>
                    @if($b->gambar)
                        <img src="{{ $b->gambar }}" style="width:90px;height:60px;object-fit:cover;border-radius:8px;">
                    @else
                        -
                    @endif
                </td>
                <td>{{ $b->judul }}</td>
                <td>{{ $b->sumber }}</td>
                <td>{{ $b->tanggal }}</td>
                <td>{{ $b->ringkasan }}</td>
                <td>
                    <a href="{{ $b->link }}" target="_blank">Buka</a>
                </td>
                <td>
                    <form action="{{ route('admin.berita.delete', $b->id) }}" method="POST">
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
                <td colspan="7">Belum ada berita.</td>
            </tr>
        @endforelse
    </table>
</div>

@endsection