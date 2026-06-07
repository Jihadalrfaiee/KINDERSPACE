@extends('layouts.app')

@section('title', 'تفاصيل الدور')

@section('content')
<div class="container-fluid px-4 py-3">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="mb-1">
                        <i class="fas fa-shield-alt text-primary"></i>
                        تفاصيل الدور
                    </h2>
                    <p class="text-muted mb-0">{{ $role->display_name }}</p>
                </div>
                <div>
                    <a href="{{ route('roles.edit', $role) }}" class="btn btn-warning">
                        <i class="fas fa-edit"></i> تعديل
                    </a>
                    <a href="{{ route('roles.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-right"></i> رجوع
                    </a>
                </div>
            </div>

            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card shadow-sm h-100">
                        <div class="card-body">
                            <h5 class="card-title text-primary">معلومات الدور</h5>
                            <hr>
                            <p><strong>الاسم البرمجي:</strong> {{ $role->name }}</p>
                            <p><strong>الحالة:</strong> 
                                <span class="badge bg-{{ $role->is_active ? 'success' : 'secondary' }}">
                                    {{ $role->is_active ? 'نشط' : 'غير نشط' }}
                                </span>
                            </p>
                            <p><strong>الوصف:</strong><br>{{ $role->description ?? 'لا يوجد وصف' }}</p>
                            <p><strong>عدد المستخدمين:</strong> {{ $role->users_count }}</p>
                        </div>
                    </div>
                </div>

                <div class="col-md-8">
                    <div class="card shadow-sm h-100">
                        <div class="card-body">
                            <h5 class="card-title text-primary">الصلاحيات الممنوحة ({{ $role->permissions_count }})</h5>
                            <hr>
                            <div class="row g-2">
                                @forelse($role->permissions as $permission)
                                    <div class="col-md-6 col-lg-4">
                                        <div class="border rounded p-2 bg-light">
                                            <small class="text-muted d-block">{{ __('permissions.groups.' . $permission->group, [], 'ar') }}</small>
                                            <strong>{{ $permission->display_name }}</strong>
                                        </div>
                                    </div>
                                @empty
                                    <div class="col-12 text-center text-muted py-4">
                                        لا توجد صلاحيات ممنوحة لهذا الدور.
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection