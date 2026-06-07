@extends('layouts.app')

@section('content')
<div class="container py-4">
    <!-- رأس الصفحة -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-dark mb-1"><i class="fas fa-user-shield text-primary me-2"></i> إدارة الأدوار والصلاحيات</h3>
            <p class="text-muted mb-0">إدارة أدوار المستخدمين وصلاحياتهم في النظام</p>
        </div>
        <a href="{{ route('roles.create') }}" class="btn btn-primary shadow-sm rounded-pill px-4">
            <i class="fas fa-plus me-1"></i> إضافة دور جديد
        </a>
    </div>

    <!-- رسائل التنبيه -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- شبكة الأدوار -->
    <div class="row g-4">
        @foreach($roles as $role)
        <div class="col-md-6 col-lg-4">
            <div class="card h-100 border-0 shadow-sm rounded-4 hover-lift">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="d-flex align-items-center">
                            <div class="bg-primary bg-opacity-10 text-primary rounded-circle p-3 me-3">
                                <i class="fas fa-shield-alt fa-lg"></i>
                            </div>
                            <div>
                                <h5 class="fw-bold mb-0">{{ $role->display_name }}</h5>
                                <span class="badge bg-light text-dark border font-monospace mt-1">{{ $role->name }}</span>
                            </div>
                        </div>
                    </div>

                    <p class="text-muted small mb-4" style="min-height: 40px;">
                        {{ $role->description ?? 'لا يوجد وصف متاح لهذا الدور.' }}
                    </p>

                    <div class="row text-center border-top pt-3 mb-4">
                        <div class="col-6 border-end">
                            <h4 class="fw-bold text-dark mb-0">{{ $role->users_count ?? 0 }}</h4>
                            <span class="text-muted small">مستخدم</span>
                        </div>
                        <div class="col-6">
                            <h4 class="fw-bold text-primary mb-0">{{ $role->permissions_count ?? 0 }}</h4>
                            <span class="text-muted small">صلاحية</span>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('roles.edit', $role) }}" class="btn btn-outline-primary btn-sm rounded-pill px-3">
                            <i class="fas fa-edit"></i> تعديل
                        </a>
                        @if($role->name !== 'admin')
                        <form action="{{ route('roles.destroy', $role) }}" method="POST" class="d-inline" onsubmit="return confirm('هل أنت متأكد من حذف هذا الدور؟');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger btn-sm rounded-pill px-3">
                                <i class="fas fa-trash"></i> حذف
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

<style>
    .hover-lift { transition: transform 0.2s ease, box-shadow 0.2s ease; }
    .hover-lift:hover { transform: translateY(-5px); box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important; }
</style>
@endsection