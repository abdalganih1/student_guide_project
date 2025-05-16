<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'لوحة التحكم - دليل الطالب')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <!-- Scripts -->
    {{-- @vite(['resources/css/app.css', 'resources/js/app.js']) --}}
    <style>
        body { font-family: 'Cairo', sans-serif; /* مثال لخط عربي */ }
        .sidebar {
            height: 100vh;
            position: fixed;
            top: 0;
            right: 0; /* للغة العربية */
            width: 250px;
            background-color: #f8f9fa;
            padding-top: 1rem;
        }
        .content {
            margin-right: 250px; /* للغة العربية */
            padding: 1rem;
        }
    </style>
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100">
        @include('admin.partials._sidebar') {{-- افترض وجود هذا الملف --}}

        <!-- Page Content -->
        <main class="content">
            @include('admin.partials._navbar') {{-- افترض وجود هذا الملف --}}
            @include('admin.partials._messages') {{-- لعرض رسائل النجاح والخطأ --}}
            @yield('content')
        </main>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>