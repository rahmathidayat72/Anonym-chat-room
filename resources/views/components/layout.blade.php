<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>EphemChat</title>
    @fonts
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <style>
        :root {
            --primary: #E5E4DF;
            --secondary: #7B7A74;
            --tertiary: #5FB5D6;
            --neutral: #161614;
            --surface: #1D1D1B;
            --violet: #8B5CF6;
            --emerald: #10B981;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Inter', sans-serif;
            background: var(--neutral);
            color: var(--primary);
            min-height: 100vh;
        }
        .font-mono { font-family: 'JetBrains Mono', monospace; }
        .toast {
            position: fixed;
            bottom: 24px;
            left: 50%;
            transform: translateX(-50%);
            background: var(--surface);
            color: var(--primary);
            padding: 12px 24px;
            border-radius: 6px;
            font-size: 0.85rem;
            opacity: 0;
            transition: opacity 0.3s;
            z-index: 999;
            border: 1px solid var(--secondary);
        }
        .toast.show { opacity: 1; }
    </style>
</head>
<body>
    {{ $slot }}

    <div id="toast" class="toast"></div>

    <script>
        function showToast(msg) {
            const t = document.getElementById('toast');
            t.textContent = msg;
            t.classList.add('show');
            setTimeout(() => t.classList.remove('show'), 3000);
        }

        axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').content;
        axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
    </script>
</body>
</html>
