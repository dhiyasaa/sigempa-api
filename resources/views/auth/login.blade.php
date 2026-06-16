<!DOCTYPE html>
<html>
<head>
    <title>Login - SiGempa</title>

    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, #7C74FF, #675EF7);
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .box {
            background: white;
            width: 380px;
            padding: 30px;
            border-radius: 20px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
        }

        h2 {
            text-align: center;
            color: #675EF7;
            margin-bottom: 25px;
        }

        label {
            font-weight: 600;
            display: block;
            margin-bottom: 6px;
        }

        input {
            width: 100%;
            padding: 11px;
            margin-bottom: 15px;
            border-radius: 10px;
            border: 1px solid #ddd;
            box-sizing: border-box;
        }

        button {
            width: 100%;
            background: #675EF7;
            color: white;
            padding: 12px;
            border: none;
            border-radius: 10px;
            font-weight: 600;
            cursor: pointer;
        }

        button:hover {
            background: #5148d9;
        }

        .link {
            text-align: center;
            margin-top: 15px;
        }

        .link a {
            color: #675EF7;
            text-decoration: none;
            font-weight: 600;
        }

        .error {
            background: #f8d7da;
            color: #721c24;
            padding: 10px;
            border-radius: 10px;
            margin-bottom: 15px;
        }

        .success {
            background: #d4edda;
            color: #155724;
            padding: 10px;
            border-radius: 10px;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>

<div class="box">
    <h2>Login SiGempa</h2>

    @if(session('error'))
        <div class="error">{{ session('error') }}</div>
    @endif

    @if(session('success'))
        <div class="success">{{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div class="error">
            {{ $errors->first() }}
        </div>
    @endif

    <form action="{{ route('login.post') }}" method="POST">
        @csrf

        <label>Email</label>
        <input type="email" name="email" placeholder="Masukkan email" required>

        <label>Password</label>
        <input type="password" name="password" placeholder="Masukkan password" required>

        <button type="submit">Login</button>
    </form>

    <div class="link">
        Belum punya akun?
        <a href="{{ route('register') }}">Register</a>
    </div>
</div>

</body>
</html>