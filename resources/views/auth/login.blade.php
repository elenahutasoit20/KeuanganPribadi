<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Login - Keuangan</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body{ margin:0; height:100vh; display:flex; }
        .left{ width:40%; background:#2b79c9; display:flex; align-items:center; justify-content:center; color:#fff; font-size:48px; font-weight:700;}
        .right{ width:60%; display:flex; align-items:center; justify-content:center; }
        .box{ width:420px; background:#fff; padding:28px; border-radius:12px; box-shadow:0 6px 18px rgba(0,0,0,0.08);}
    </style>
</head>
<body>
    <div class="left">
        @if (file_exists(public_path('image/keuangan.jpg')))
            <img src="{{ asset('image/keuangan.jpg') }}" alt="{{ config('app.name', 'Keuangan') }}" style="max-width:220px; max-height:120px; object-fit:contain;">
        @else
            <div style="font-size:32px; font-weight:700">{{ config('app.name', 'Keuangan') }}</div>
        @endif
    </div>
    <div class="right">
        <div class="box">
            <h3>Sign In</h3>
            <p class="text-muted">Sign in to your account</p>

            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ $errors->first() }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <form action="{{ url('/login') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label>Email</label>
                    <input name="email" type="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required>
                    @error('email')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label>Password</label>
                    <input name="password" type="password" class="form-control @error('password') is-invalid @enderror" required>
                    @error('password')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
                <button class="btn btn-primary w-100">Sign In</button>
            </form>

            <div class="mt-3 text-center">
                <small>Don't have an account? <a href="{{ url('/register') }}">Register</a></small>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
