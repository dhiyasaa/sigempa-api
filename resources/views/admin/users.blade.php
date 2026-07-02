@extends('layouts.app')

@section('content')

<h2>Data Administrator</h2>

@if(session('success'))
<div class="alert-success">
    {{ session('success') }}
</div>
@endif

<div class="card">

    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;">

        <h3 style="margin:0;">
            Daftar Administrator
        </h3>

        <button
            onclick="document.getElementById('modalTambah').style.display='block'"
            class="btn btn-dec">
            + Tambah Administrator
        </button>

    </div>

    <table>

        <thead>

        <tr>

            <th width="60">No</th>

            <th>Nama</th>

            <th>Email</th>

            <th width="180">Aksi</th>

        </tr>

        </thead>

        <tbody>

        @forelse($data as $i => $user)

        <tr>

            <td>{{ $i+1 }}</td>

            <td>{{ $user->name }}</td>

            <td>{{ $user->email }}</td>

            <td>

                <div class="action-row">

                    <button
                        class="btn btn-dec"
                        onclick="editUser(
                            '{{ $user->id }}',
                            '{{ $user->name }}',
                            '{{ $user->email }}'
                        )">

                        Edit

                    </button>

                    <form
                        action="{{ route('admin.users.delete',$user->id) }}"
                        method="POST"
                        onsubmit="return confirm('Hapus administrator ini?')">

                        @csrf
                        @method('DELETE')

                        <button
                            class="btn btn-delete">

                            Hapus

                        </button>

                    </form>

                </div>

            </td>

        </tr>

        @empty

        <tr>

            <td colspan="4">

                Belum ada data administrator.

            </td>

        </tr>

        @endforelse

        </tbody>

    </table>

</div>

<!-- ========================= -->
<!-- Modal Tambah -->
<!-- ========================= -->

<div id="modalTambah"
style="
display:none;
position:fixed;
left:0;
top:0;
width:100%;
height:100%;
background:rgba(0,0,0,.45);
">

<div
style="
background:white;
width:450px;
margin:60px auto;
padding:25px;
border-radius:15px;
">

<h3>Tambah Administrator</h3>

<form
action="{{ route('admin.users.store') }}"
method="POST">

@csrf

<div style="margin-bottom:15px;">

<label>Nama</label>

<input
type="text"
name="name"
required
style="
width:100%;
padding:10px;
border:1px solid #ddd;
border-radius:8px;
">

</div>

<div style="margin-bottom:15px;">

<label>Email</label>

<input
type="email"
name="email"
required
style="
width:100%;
padding:10px;
border:1px solid #ddd;
border-radius:8px;
">

</div>

<div style="margin-bottom:20px;">

<label>Password</label>

<input
type="password"
name="password"
required
style="
width:100%;
padding:10px;
border:1px solid #ddd;
border-radius:8px;
">

</div>

<div
style="
display:flex;
justify-content:flex-end;
gap:10px;
">

<button
type="button"
class="btn btn-delete"
onclick="
document.getElementById('modalTambah').style.display='none'
">

Batal

</button>

<button
type="submit"
class="btn btn-dec">

Simpan

</button>

</div>

</form>

</div>

</div>
<!-- ========================= -->
<!-- Modal Edit -->
<!-- ========================= -->

<div id="modalEdit"
style="
display:none;
position:fixed;
left:0;
top:0;
width:100%;
height:100%;
background:rgba(0,0,0,.45);
">

<div
style="
background:white;
width:450px;
margin:60px auto;
padding:25px;
border-radius:15px;
">

<h3>Edit Administrator</h3>

<form
id="formEdit"
method="POST">

@csrf
@method('PUT')

<div style="margin-bottom:15px;">

<label>Nama</label>

<input
type="text"
id="edit_name"
name="name"
required
style="
width:100%;
padding:10px;
border:1px solid #ddd;
border-radius:8px;
">

</div>

<div style="margin-bottom:15px;">

<label>Email</label>

<input
type="email"
id="edit_email"
name="email"
required
style="
width:100%;
padding:10px;
border:1px solid #ddd;
border-radius:8px;
">

</div>

<div style="margin-bottom:20px;">

<label>Password Baru (Opsional)</label>

<input
type="password"
name="password"
placeholder="Kosongkan jika tidak diubah"
style="
width:100%;
padding:10px;
border:1px solid #ddd;
border-radius:8px;
">

</div>

<div
style="
display:flex;
justify-content:flex-end;
gap:10px;
">

<button
type="button"
class="btn btn-delete"
onclick="
document.getElementById('modalEdit').style.display='none'
">

Batal

</button>

<button
type="submit"
class="btn btn-dec">

Update

</button>

</div>

</form>

</div>

</div>
<script>

function editUser(id,nama,email){

document.getElementById("edit_name").value=nama;

document.getElementById("edit_email").value=email;

document.getElementById("formEdit").action="/admin/users/"+id;

document.getElementById("modalEdit").style.display="block";

}

window.onclick=function(event){

let tambah=document.getElementById("modalTambah");

let edit=document.getElementById("modalEdit");

if(event.target==tambah){

tambah.style.display="none";

}

if(event.target==edit){

edit.style.display="none";

}

}

</script>
@endsection