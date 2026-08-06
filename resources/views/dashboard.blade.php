@extends('layouts.app')

@section('title', 'لوحة التحكم - نظام إدارة رياض الأطفال')

@section('content')
    <div class="container-fluid px-4 py-4">
        <div class="card bg-primary text-white rounded-3 shadow-sm p-4 mb-4">
            <h2 class="h4 mb-2">مرحباً بك! 👋</h2>
            <p class="mb-0 opacity-90">أهلاً بك في نظام إدارة رياض الأطفال المتكامل.</p>
        </div>

        <div class="row g-3 mb-4">
            <div class="col-12 col-md-6 col-xl-3">
                <div class="card shadow-sm border-top border-primary rounded-3 p-3 h-100">
                    <div class="d-flex align-items-start justify-content-between">
                        <div>
                            <p class="text-muted mb-1">إجمالي الطلاب</p>
                            <h3 class="mb-0">{{ $stats['total_students'] }}</h3>
                        </div>
                        <div class="bg-primary text-white rounded-3 p-3">
                            <i class="fas fa-child fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-6 col-xl-3">
                <div class="card shadow-sm border-top border-success rounded-3 p-3 h-100">
                    <div class="d-flex align-items-start justify-content-between">
                        <div>
                            <p class="text-muted mb-1">المعلمات</p>
                            <h3 class="mb-0">{{ $stats['total_teachers'] }}</h3>
                        </div>
                        <div class="bg-success text-white rounded-3 p-3">
                            <i class="fas fa-chalkboard-teacher fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-6 col-xl-3">
                <div class="card shadow-sm border-top border-warning rounded-3 p-3 h-100">
                    <div class="d-flex align-items-start justify-content-between">
                        <div>
                            <p class="text-muted mb-1">الروضات</p>
                            <h3 class="mb-0">{{ $stats['total_kindergartens'] }}</h3>
                        </div>
                        <div class="bg-warning text-white rounded-3 p-3">
                            <i class="fas fa-school fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-6 col-xl-3">
                <div class="card shadow-sm border-top border-info rounded-3 p-3 h-100">
                    <div class="d-flex align-items-start justify-content-between">
                        <div>
                            <p class="text-muted mb-1">أولياء الأمور</p>
                            <h3 class="mb-0">{{ $stats['total_parents'] }}</h3>
                        </div>
                        <div class="bg-info text-white rounded-3 p-3">
                            <i class="fas fa-user-friends fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-3">
            <div class="col-12 col-md-6 col-xl-3">
                <a href="{{ route('students.create') }}" class="text-decoration-none">
                    <div class="card shadow-sm rounded-3 p-4 h-100 text-center hover-shadow">
                        <i class="fas fa-user-plus fa-2x text-primary mb-3"></i>
                        <h5 class="mb-0">إضافة طالب</h5>
                    </div>
                </a>
            </div>
            <div class="col-12 col-md-6 col-xl-3">
                <a href="{{ route('students.index') }}" class="text-decoration-none">
                    <div class="card shadow-sm rounded-3 p-4 h-100 text-center hover-shadow">
                        <i class="fas fa-users fa-2x text-success mb-3"></i>
                        <h5 class="mb-0">قائمة الطلاب</h5>
                    </div>
                </a>
            </div>
            <div class="col-12 col-md-6 col-xl-3">
                <a href="{{ route('fee-structures.index') }}" class="text-decoration-none">
                    <div class="card shadow-sm rounded-3 p-4 h-100 text-center hover-shadow">
                        <i class="fas fa-wallet fa-2x text-warning mb-3"></i>
                        <h5 class="mb-0">هيكل الرسوم</h5>
                    </div>
                </a>
            </div>
            <div class="col-12 col-md-6 col-xl-3">
                <a href="{{ route('student-fees.index') }}" class="text-decoration-none">
                    <div class="card shadow-sm rounded-3 p-4 h-100 text-center hover-shadow">
                        <i class="fas fa-file-invoice-dollar fa-2x text-info mb-3"></i>
                        <h5 class="mb-0">قيود الرسوم</h5>
                    </div>
                </a>
            </div>
        </div>
    </div>
@endsection