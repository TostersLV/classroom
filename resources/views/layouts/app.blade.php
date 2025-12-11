<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" id="html-root">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>{{ $title ?? config('app.name', 'ClassIt') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* stronger dark theme overrides (covers common Tailwind utilities) */
        .dark body { background: #0b1220 !important; color: #e6eef8 !important; }
        .dark .bg-white,
        .dark .bg-gray-50,
        .dark .bg-gray-100,
        .dark .bg-gray-200,
        .dark .bg-gray-300,
        .dark .bg-gray-800,
        .dark .bg-slate-50,
        .dark .bg-slate-100 {
            background: #0f1724 !important;
            color: #e6eef8 !important;
        }

        .dark .text-gray-900,
        .dark .text-gray-800,
        .dark .text-gray-700 { color: #e6eef8 !important; }

        .dark .text-gray-600,
        .dark .text-gray-500,
        .dark .text-gray-400 { color: #9aa6b2 !important; }

        /* Make small muted text slightly brighter */
        .dark .text-sm, .dark small { color: #9aa6b2 !important; }

        /* Comment card background + border */
        .dark .comment-card { background:#071022 !important; border-color:#233241 !important; color:#e6eef8 !important; }

        /* Ensure avatars are visible: add border and background */
        .dark .avatar {
            background: #071022;
            border: 1px solid rgba(255,255,255,0.06);
            object-fit: cover;
        }

        /* Inputs / textareas inside dark cards */
        .dark input, .dark textarea, .dark select {
            background: #071022 !important;
            color: #e6eef8 !important;
            border-color: #233241 !important;
        }

        /* small utilities */
        .dark .shadow, .dark .shadow-sm { box-shadow: none !important; }

        /* make author name explicit so theme toggles can't accidentally invert it */
        .author-name { color: #111827; }             /* light mode */
        .dark .author-name { color: #e6eef8 !important; } /* dark mode override */

        /* ensure comment card text defaults in light mode */
        .comment-card { background: #ffffff; color: #111827; }
        .dark .comment-card { background: #071022 !important; color: #e6eef8 !important; }

        /* Inputs / textareas — force light-mode defaults, keep dark overrides above */
        input, textarea, select {
            background: #ffffff;
            color: #111827;
            border: 1px solid #e5e7eb;
        }
        input::placeholder, textarea::placeholder {
            color: #9ca3af; /* light placeholder */
        }

        /* keep your existing dark overrides (important) */
        .dark input, .dark textarea, .dark select {
            background: #071022 !important;
            color: #e6eef8 !important;
            border-color: #233241 !important;
        }
        .dark input::placeholder, .dark textarea::placeholder { color: #6b7280 !important; }

        /* comment-specific text */
        .comment-body { color: #111827; } /* light mode body */
        .dark .comment-body { color: #e6eef8 !important; }

        /* Force light-mode appearance when root does NOT have .dark */
        #html-root:not(.dark) .comment-textarea,
        #html-root:not(.dark) .comment-card textarea,
        #html-root:not(.dark) textarea,
        #html-root:not(.dark) input {
            background: #ffffff !important;
            color: #111827 !important;
            border-color: #e5e7eb !important;
        }

        #html-root:not(.dark) .comment-textarea::placeholder,
        #html-root:not(.dark) textarea::placeholder,
        #html-root:not(.dark) input::placeholder {
            color: #9ca3af !important;
        }

        /* comment body text in light mode */
        #html-root:not(.dark) .comment-body,
        #html-root:not(.dark) .author-name {
            color: #111827 !important;
        }
    </style>
</head>
<body class="antialiased">
    <!-- small toggle button in top-left; move to your header if needed -->
    <div style="position:fixed;left:12px;top:12px;z-index:60;">
        <button id="dark-toggle" aria-label="Toggle dark mode" style="background:transparent;border:1px solid rgba(0,0,0,0.12);padding:6px 8px;border-radius:6px;">
            Toggle dark
        </button>
    </div>

    <div class="min-h-screen bg-gray-100">
        @include('layouts.navigation')

        <!-- Page Heading -->
        @if (isset($header))
            <header class="bg-white shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endif

        <!-- Page Content -->
        <main>
        
        @isset($slot)
            {{ $slot }}
        @else
            @yield('content')
        @endisset
    </main>
    </div>

    <!-- dark toggle script -->
    <script>
        (function(){
            const root = document.documentElement;
            const cookieName = 'site_dark_mode';
            function readCookie(name){
                const m = document.cookie.match('(^|;)\\s*' + name + '\\s*=\\s*([^;]+)');
                return m ? decodeURIComponent(m.pop()) : null;
            }
            function writeCookie(name, value, days=365){
                const d = new Date();
                d.setTime(d.getTime() + (days*24*60*60*1000));
                document.cookie = name + "=" + encodeURIComponent(value) + ";path=/;expires=" + d.toUTCString();
            }

            // prefer explicit cookie; if none, use system preference
            const pref = readCookie(cookieName);
            const systemPrefDark = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;

            if (pref === '1' || (pref === null && systemPrefDark)) {
                root.classList.add('dark');
            }

            const btn = document.getElementById('dark-toggle');
            if (!btn) return;

            function updateButton() {
                btn.textContent = root.classList.contains('dark') ? 'Light' : 'Dark';
            }

            btn.addEventListener('click', function(){
                const isDark = root.classList.toggle('dark');
                writeCookie(cookieName, isDark ? '1' : '0');
                updateButton();
            });

            updateButton();
        })();
    </script>

</body>
</html>
