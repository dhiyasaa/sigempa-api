<!DOCTYPE html>
<html>
<head>
    <title>SiGempa Admin</title>

    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', sans-serif;
            background: #F5F6FA;
        }

        .container {
            display: flex;
        }

        .sidebar {
            width: 220px;
            background: white;
            height: 100vh;
            padding: 20px;
            border-right: 1px solid #eee;
            position: fixed;
            left: 0;
            top: 0;
        }

        .sidebar h3 {
            color: #675EF7;
            margin-bottom: 10px;
        }

        .user-info {
            font-size: 13px;
            color: #666;
            margin-bottom: 25px;
        }

        .sidebar a {
            display: block;
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 10px;
            text-decoration: none;
            color: #444;
            font-weight: 500;
        }

        .sidebar a:hover {
            background: #F0EEFF;
            color: #675EF7;
        }

        .sidebar a.active {
            background: linear-gradient(135deg, #7C74FF, #675EF7);
            color: white;
        }

        .logout-btn {
            width: 100%;
            padding: 10px;
            background: #FF4D4F;
            color: white;
            border: none;
            border-radius: 10px;
            font-weight: 600;
            cursor: pointer;
            margin-top: 10px;
        }

        .logout-btn:hover {
            background: #d9363e;
        }

        .content {
            flex: 1;
            padding: 30px;
            margin-left: 260px;
        }

        .card {
            background: white;
            padding: 20px;
            border-radius: 20px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.05);
            margin-bottom: 20px;
            overflow-x: auto;
        }

        h2 {
            margin-top: 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 12px;
            border-bottom: 1px solid #eee;
            text-align: center;
        }

        th {
            background: #675EF7;
            color: white;
        }

        tr:hover {
            background: #F9F9FF;
        }

        .btn {
            padding: 7px 13px;
            border-radius: 8px;
            border: none;
            cursor: pointer;
            font-size: 13px;
            text-decoration: none;
            display: inline-block;
            font-weight: 600;
        }

        .btn-delete {
            background: #FF4D4F;
            color: white;
        }

        .btn-delete:hover {
            background: #d9363e;
        }

        .btn-dec {
            background: #675EF7;
            color: white;
        }

        .btn-dec:hover {
            background: #5148d9;
        }

        .btn-refresh {
            background: #0d6efd;
            color: white;
            margin-bottom: 15px;
        }

        .btn-upload {
            background: #20c997;
            color: white;
            margin-bottom: 15px;
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
            padding: 10px;
            border-radius: 10px;
            margin-bottom: 15px;
        }

        .action-row {
            display: flex;
            gap: 8px;
            justify-content: center;
            align-items: center;
        }

        input[type="file"] {
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 10px;
            background: white;
        }
    </style>
</head>

<body>

<div class="container">

    <div class="sidebar">
        <h3>SiGempa</h3>

        <div class="user-info">
            Login sebagai:<br>
            <b>{{ Auth::user()->name ?? 'Admin' }}</b>
        </div>

        <a href="/admin" class="{{ request()->is('admin') ? 'active' : '' }}">
            Dashboard
        </a>

        <a href="/admin/gempa" class="{{ request()->is('admin/gempa') ? 'active' : '' }}">
            Data Gempa
        </a>

        <a href="/admin/history" class="{{ request()->is('admin/history') ? 'active' : '' }}">
            History Gempa
        </a>

        <a href="/admin/upload" class="{{ request()->is('admin/upload') ? 'active' : '' }}">
            Upload Excel
        </a>

        <a href="/admin/mitigasi" class="{{ request()->is('admin/mitigasi') ? 'active' : '' }}">
            Mitigasi
        </a>

        <a href="/admin/map" class="{{ request()->is('admin/map') ? 'active' : '' }}">
            Peta Gempa
        </a>

        <a href="/admin/berita" class="{{ request()->is('admin/berita') ? 'active' : '' }}">
    Berita Gempa
</a>

<a href="/admin/edukasi" class="{{ request()->is('admin/edukasi') ? 'active' : '' }}">
    Edukasi Gempa
</a>

<a href="/admin/umpan-balik" class="{{ request()->is('admin/umpan-balik') ? 'active' : '' }}">
    Umpan Balik
</a>
<a href="/admin/users" class="{{ request()->is('admin/users') ? 'active' : '' }}">
    Administrator
</a>

        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="logout-btn">
                Logout
            </button>
        </form>
    </div>

    <div class="content">
        @yield('content')
    </div>

</div>

</body>
</html>