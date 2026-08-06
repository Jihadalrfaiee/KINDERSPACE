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
                        <div class="card-body p-2">
                            <h6 class="card-title mb-2 px-2">القائمة الرئيسية</h6>

                            <div class="accordion" id="mainSidebarAccordion">

                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="headingHome">
                                        <button class="accordion-button collapsed p-2" type="button" data-bs-toggle="collapse" data-bs-target="#collapseHome" aria-expanded="true" aria-controls="collapseHome">
                                            <i class="fas fa-home me-2"></i> صفحة سريعة
                                        </button>
                                    </h2>
                                    <div id="collapseHome" class="accordion-collapse collapse show" aria-labelledby="headingHome" data-bs-parent="#mainSidebarAccordion">
                                        <div class="accordion-body p-2">
                                            <a href="{{ route('dashboard') }}" class="d-block py-1 px-2 {{ Request::routeIs('dashboard') ? 'fw-bold text-primary' : '' }}"><i class="fas fa-home me-1"></i> الرئيسية</a>
                                        </div>
                                    </div>
                                </div>

                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="headingStudents">
                                        <button class="accordion-button collapsed p-2" type="button" data-bs-toggle="collapse" data-bs-target="#collapseStudents" aria-expanded="false" aria-controls="collapseStudents">
                                            <i class="fas fa-users me-2"></i> الطلاب
                                        </button>
                                    </h2>
                                    <div id="collapseStudents" class="accordion-collapse collapse" aria-labelledby="headingStudents" data-bs-parent="#mainSidebarAccordion">
                                        <div class="accordion-body p-2">
                                            <a href="{{ route('students.index') }}" class="d-block py-1 px-2 {{ Request::routeIs('students.*') ? 'fw-bold text-primary' : '' }}">قائمة الطلاب</a>
                                            <a href="{{ route('students.create') }}" class="d-block py-1 px-2">إضافة طالب</a>
                                            <a href="{{ route('student-fees.index') }}" class="d-block py-1 px-2">قيود ورسوم</a>
                                        </div>
                                    </div>
                                </div>

                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="headingTeaching">
                                        <button class="accordion-button collapsed p-2" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTeaching" aria-expanded="false" aria-controls="collapseTeaching">
                                            <i class="fas fa-chalkboard-teacher me-2"></i> المعلمات والمواد
                                        </button>
                                    </h2>
                                    <div id="collapseTeaching" class="accordion-collapse collapse" aria-labelledby="headingTeaching" data-bs-parent="#mainSidebarAccordion">
                                        <div class="accordion-body p-2">
                                            <a href="{{ route('users.index') }}" class="d-block py-1 px-2">المستخدمون</a>
                                            <a href="{{ route('roles.index') }}" class="d-block py-1 px-2">الصلاحيات</a>
                                            <a href="{{ url('/teacher-assignments') }}" class="d-block py-1 px-2">ربط المعلمات</a>
                                        </div>
                                    </div>
                                </div>

                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="headingProgram">
                                        <button class="accordion-button collapsed p-2" type="button" data-bs-toggle="collapse" data-bs-target="#collapseProgram" aria-expanded="false" aria-controls="collapseProgram">
                                            <i class="fas fa-calendar-week me-2"></i> البرنامج الأسبوعي
                                        </button>
                                    </h2>
                                    <div id="collapseProgram" class="accordion-collapse collapse" aria-labelledby="headingProgram" data-bs-parent="#mainSidebarAccordion">
                                        <div class="accordion-body p-2">
                                            <a href="{{ url('/weekly-program') }}" class="d-block py-1 px-2">عرض البرنامج</a>
                                            <a href="{{ url('/weekly-program/create') }}" class="d-block py-1 px-2">إضافة/تعديل الحصص</a>
                                        </div>
                                    </div>
                                </div>

                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="headingAttendance">
                                        <button class="accordion-button collapsed p-2" type="button" data-bs-toggle="collapse" data-bs-target="#collapseAttendance" aria-expanded="false" aria-controls="collapseAttendance">
                                            <i class="fas fa-sign-in-alt me-2"></i> الحضور والانصراف
                                        </button>
                                    </h2>
                                    <div id="collapseAttendance" class="accordion-collapse collapse" aria-labelledby="headingAttendance" data-bs-parent="#mainSidebarAccordion">
                                        <div class="accordion-body p-2">
                                            <a href="{{ url('/attendance') }}" class="d-block py-1 px-2">تسجيل الحضور</a>
                                            <a href="{{ url('/attendance/report') }}" class="d-block py-1 px-2">تقارير الحضور</a>
                                        </div>
                                    </div>
                                </div>

                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="headingSections">
                                        <button class="accordion-button collapsed p-2" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSections" aria-expanded="false" aria-controls="collapseSections">
                                            <i class="fas fa-layer-group me-2"></i> الشُعب والفئات
                                        </button>
                                    </h2>
                                    <div id="collapseSections" class="accordion-collapse collapse" aria-labelledby="headingSections" data-bs-parent="#mainSidebarAccordion">
                                        <div class="accordion-body p-2">
                                            <a href="{{ url('/sections') }}" class="d-block py-1 px-2">قائمة الشُعب</a>
                                            <a href="{{ url('/sections/create') }}" class="d-block py-1 px-2">إضافة شعبة</a>
                                        </div>
                                    </div>
                                </div>

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