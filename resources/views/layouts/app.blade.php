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
                        @if(Auth::check() && in_array(Auth::user()->role, ['super_admin', 'admin', 'accountant']))
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="financeDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fas fa-wallet"></i> المحاسبة
                                </a>
                                <ul class="dropdown-menu" aria-labelledby="financeDropdown">
                                    <li><a class="dropdown-item" href="{{ route('fee-structures.index') }}">هيكل الرسوم</a></li>
                                    <li><a class="dropdown-item" href="{{ route('student-fees.index') }}">قيود الرسوم</a></li>
                                    <li><a class="dropdown-item" href="{{ route('installments.index') }}">الأقساط</a></li>
                                    <li><a class="dropdown-item" href="{{ route('expenses.index') }}">المصاريف</a></li>
                                    <li><a class="dropdown-item" href="{{ route('salaries.index') }}">الرواتب</a></li>
                                </ul>
                            </li>
                        @endif
                        
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
    <div class="container-fluid py-4">
        <div class="row">
            @auth
                <aside class="col-12 col-lg-2 mb-4 mb-lg-0">
                    <div class="card shadow-sm rounded-3 border-0 h-100">
                        <div class="card-body p-3">
                            <h5 class="card-title mb-3">القائمة الرئيسية</h5>
                            <div class="list-group list-group-flush">
                                <a href="{{ route('dashboard') }}" class="list-group-item list-group-item-action">
                                    <i class="fas fa-home me-2"></i> الرئيسية
                                </a>
                                <a href="{{ route('students.index') }}" class="list-group-item list-group-item-action">
                                    <i class="fas fa-users me-2"></i> الطلاب
                                </a>
                                @if(Auth::check() && in_array(Auth::user()->role, ['super_admin', 'admin', 'accountant']))
                                    <div class="mt-3 mb-2 text-secondary small">المحاسبة</div>
                                    <a href="{{ route('fee-structures.index') }}" class="list-group-item list-group-item-action">
                                        <i class="fas fa-wallet me-2"></i> هيكل الرسوم
                                    </a>
                                    <a href="{{ route('student-fees.index') }}" class="list-group-item list-group-item-action">
                                        <i class="fas fa-file-invoice-dollar me-2"></i> قيود الرسوم
                                    </a>
                                    <a href="{{ route('installments.index') }}" class="list-group-item list-group-item-action">
                                        <i class="fas fa-calendar-check me-2"></i> الأقساط
                                    </a>
                                    <a href="{{ route('expenses.index') }}" class="list-group-item list-group-item-action">
                                        <i class="fas fa-money-bill-wave me-2"></i> المصاريف
                                    </a>
                                    <a href="{{ route('salaries.index') }}" class="list-group-item list-group-item-action">
                                        <i class="fas fa-hand-holding-usd me-2"></i> الرواتب
                                    </a>
                                    <a href="{{ route('reports.monthly') }}" class="list-group-item list-group-item-action">
                                        <i class="fas fa-chart-line me-2"></i> تقارير شهرية
                                    </a>
                                @endif
                                @hasRole('admin')
                                    <div class="mt-3 mb-2 text-secondary small">الإدارة</div>
                                    <a href="{{ route('roles.index') }}" class="list-group-item list-group-item-action">
                                        <i class="fas fa-user-shield me-2"></i> الصلاحيات
                                    </a>
                                @endhasRole
                            </div>
                        </div>
                    </div>
                </aside>
            @endauth

            <main class="col-12 @auth col-lg-10 offset-lg-2 @endauth">
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
        </div>
    </div>

    {{-- Footer --}}
    <footer class="bg-white text-center text-muted py-3 mt-5 border-top">
        <small>جميع الحقوق محفوظة &copy; {{ date('Y') }} نظام روضة البراعم النموذجية</small>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    @stack('scripts')
</body>
</html>