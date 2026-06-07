<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'نظام روضة فضاء الأطفال')</title>
    
    <!-- Bootstrap & FontAwesome -->
       <!-- Bootstrap & FontAwesome -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Tailwind CSS (بديل Vite) -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    @stack('styles')
</head>
<body class="bg-light">

    {{-- شريط التنقل (Navbar) --}}
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold" href="{{ url('/') }}">
                <i class="fas fa-child"></i> روضة البراعم
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                {{-- القائمة اليمنى --}}
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    @auth
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('dashboard') }}">
                                <i class="fas fa-home"></i> الرئيسية
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('students.index') }}">
                                <i class="fas fa-users"></i> الطلاب
                            </a>
                        </li>
                        
                        {{-- رابط الصلاحيات للمدير فقط --}}
                        @hasRole('admin')
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('roles.index') }}">
                                    <i class="fas fa-user-shield"></i> الصلاحيات
                                </a>
                            </li>
                        @endhasRole
                    @endauth
                </ul>
                
                {{-- القائمة اليسرى (تسجيل الدخول/الخروج) --}}
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                    @guest
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">تسجيل الدخول</a>
                        </li>
                    @else
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                <i class="fas fa-user-circle"></i> {{ Auth::user()->name }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger">
                                            <i class="fas fa-sign-out-alt"></i> تسجيل الخروج
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    {{-- المحتوى الرئيسي --}}
    <main class="container-fluid py-4">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="bg-white text-center text-muted py-3 mt-5 border-top">
        <small>جميع الحقوق محفوظة &copy; {{ date('Y') }} نظام روضة البراعم النموذجية</small>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    @stack('scripts')
</body>
</html>