<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Keuangan Pribadi</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Favicon (default) -->
    <link id="site-favicon" rel="icon" href="{{ asset('image/keuangan.jpg') }}" type="image/jpeg">

    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body { background:#e9f2f8; }
        .sidebar {
            width: 240px; position: fixed; top:0; left:0; bottom:0;
            background: linear-gradient(180deg,#3f99e6,#2b79c9); color:#fff; padding:28px;
        }
        .sidebar h2{ font-weight:700; margin-bottom:18px; }
        .sidebar a { color:#fff; display:block; padding:8px 0; text-decoration:none; }
        .content { margin-left:260px; padding:28px; }
        .card-box{ border-radius:14px; }
        .table-wrap { background:#fff; padding:16px; border-radius:12px; }
    </style>
</head>
<body>

<div class="sidebar">
    
    <a href="{{ route('dashboard') }}">Dashboard</a>
    <a href="{{ route('transactions.index') }}">Transactions</a>
    <form action="{{ route('logout') }}" method="POST" class="mt-4">
        @csrf
        <button class="btn btn-sm btn-light w-100">Logout</button>
    </form>
</div>

<div class="content">
    @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
    @if($errors->any())<div class="alert alert-danger">{{ $errors->first() }}</div>@endif

    @yield('content')
</div>

<!-- Bootstrap JS bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    // Swap the page favicon to keuangan.jpg when user clicks anywhere in the page.
    (function(){
        const faviconId = 'site-favicon';
        const newHref = '{{ asset('image/keuangan.jpg') }}';
        let hasSwapped = false;

        function setFavicon(href){
            let link = document.getElementById(faviconId);
            if(!link){
                link = document.createElement('link');
                link.id = faviconId;
                link.rel = 'icon';
                document.head.appendChild(link);
            }
            link.href = href;
        }

        // If the current favicon is already the keuangan image, skip.
        function ensureSwap(){
            if(hasSwapped) return;
            setFavicon(newHref);
            hasSwapped = true;
        }

        document.addEventListener('click', function(e){
            // only swap on user-initiated clicks
            ensureSwap();
        }, {capture: true});
    })();
</script>

@stack('scripts')
</body>
</html>
