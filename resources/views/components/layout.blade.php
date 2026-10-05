<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>EphemChat — Anonymous Chat</title>
    @fonts
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <style>
        :root {
            --bg:        #0d0f14;
            --bg2:       #111318;
            --surface:   #181b22;
            --surface2:  #1f2330;
            --border:    #2a2f3d;
            --border2:   #353c50;
            --primary:   #e8e9f0;
            --secondary: #7a7f96;
            --muted:     #4a4f64;
            --accent:    #6c8ef5;
            --accent2:   #8b5cf6;
            --accent3:   #06d6a0;
            --bubble-me: #2b3875;
            --bubble-me-text: #c8d5ff;
            --red:       #f87171;
            --red-bg:    #2d1515;
            --green:     #34d399;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        html { scroll-behavior: smooth; }

        body {
            font-family: 'Instrument Sans', 'Inter', sans-serif;
            background: var(--bg);
            color: var(--primary);
            min-height: 100vh;
            -webkit-font-smoothing: antialiased;
        }

        /* Scrollbar */
        ::-webkit-scrollbar { width: 4px; height: 4px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: var(--border2); border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: var(--secondary); }

        /* Buttons */
        .btn {
            display: inline-flex; align-items: center; justify-content: center; gap: 6px;
            padding: 10px 20px; border-radius: 8px; font-size: 0.875rem; font-weight: 500;
            cursor: pointer; border: none; transition: all 0.2s ease; letter-spacing: 0.01em;
        }
        .btn-primary {
            background: linear-gradient(135deg, var(--accent), var(--accent2));
            color: #fff;
            box-shadow: 0 0 20px rgba(108,142,245,0.2);
        }
        .btn-primary:hover {
            box-shadow: 0 0 28px rgba(108,142,245,0.4);
            transform: translateY(-1px);
        }
        .btn-primary:active { transform: translateY(0); }
        .btn-ghost {
            background: transparent;
            border: 1px solid var(--border);
            color: var(--secondary);
        }
        .btn-ghost:hover { border-color: var(--border2); color: var(--primary); background: var(--surface); }
        .btn-danger {
            background: transparent;
            border: 1px solid var(--red-bg);
            color: var(--red);
        }
        .btn-danger:hover { background: var(--red-bg); border-color: var(--red); }

        /* Inputs */
        .input {
            width: 100%; padding: 11px 14px; background: var(--surface2);
            border: 1px solid var(--border); border-radius: 8px; color: var(--primary);
            font-size: 0.875rem; outline: none; transition: border-color 0.2s, box-shadow 0.2s;
            font-family: inherit;
        }
        .input:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(108,142,245,0.12);
        }
        .input::placeholder { color: var(--muted); }

        /* Card */
        .card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 12px;
        }

        /* Badge */
        .badge {
            display: inline-flex; align-items: center; gap: 5px;
            padding: 3px 10px; border-radius: 999px; font-size: 0.72rem; font-weight: 500;
        }
        .badge-green { background: rgba(52,211,153,0.12); color: var(--green); }
        .badge-accent { background: rgba(108,142,245,0.12); color: var(--accent); }
        .badge-muted { background: var(--surface2); color: var(--secondary); }

        /* Checkbox custom */
        .checkbox-label {
            display: flex; align-items: center; gap: 10px;
            font-size: 0.875rem; color: var(--secondary); cursor: pointer;
            user-select: none;
        }
        .checkbox-label input[type="checkbox"] {
            appearance: none; width: 16px; height: 16px; border: 1.5px solid var(--border2);
            border-radius: 4px; cursor: pointer; transition: all 0.2s; background: var(--surface2);
            position: relative; flex-shrink: 0;
        }
        .checkbox-label input[type="checkbox"]:checked {
            background: var(--accent); border-color: var(--accent);
        }
        .checkbox-label input[type="checkbox"]:checked::after {
            content: ''; position: absolute; left: 4px; top: 1px;
            width: 5px; height: 9px; border: 2px solid #fff; border-top: none; border-left: none;
            transform: rotate(45deg);
        }
        .checkbox-label:hover input[type="checkbox"] { border-color: var(--accent); }

        /* Toast */
        .toast {
            position: fixed; bottom: 28px; left: 50%; transform: translateX(-50%) translateY(8px);
            background: var(--surface2); color: var(--primary);
            padding: 12px 20px; border-radius: 10px; font-size: 0.85rem;
            opacity: 0; transition: opacity 0.25s ease, transform 0.25s ease;
            z-index: 9999; border: 1px solid var(--border2);
            backdrop-filter: blur(12px);
            box-shadow: 0 8px 32px rgba(0,0,0,0.4);
            white-space: nowrap;
        }
        .toast.show { opacity: 1; transform: translateX(-50%) translateY(0); }

        /* Utility */
        .mono { font-family: 'JetBrains Mono', 'Fira Code', monospace; }
        .text-accent { color: var(--accent); }
        .text-muted { color: var(--secondary); }
        .text-danger { color: var(--red); }
        .dot { width: 7px; height: 7px; border-radius: 50%; background: var(--green); display: inline-block; animation: pulse-dot 2s infinite; }
        @keyframes pulse-dot {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.4; }
        }

        /* Glow effect on accent elements */
        .glow { box-shadow: 0 0 24px rgba(108,142,245,0.15); }
    </style>
</head>
<body>
    {{ $slot }}

    <div id="toast" class="toast"></div>

    <script>
        function showToast(msg, type) {
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
