@extends('layouts.app')

@section('title', 'تفاصيل المستخدم')

@section('content')
<div class="container-fluid px-4 py-3">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            {{-- رأس الصفحة --}}
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="mb-1">
                        <i class="fas fa-user-circle text-primary"></i>
                        تفاصيل المستخدم
                    </h2>
                    <p class="text-muted mb-0">{{ $user->name }}</p>
                </div>
                <div>
                    @hasPermission('users.edit')
                    <a href="{{ route('users.edit', $user) }}" class="btn btn-warning">
                        <i class="fas fa-edit"></i>
                        تعديل
                    </a>
                    @endhasPermission
                    <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-right"></i>
                        رجوع
                    </a>
                </div>
            </div>

            <div class="row g-4">
                {{-- معلومات المستخدم --}}
                <div class="col-md-6">
                    <div class="card shadow-sm h-100">
                        <div class="card-body">
                            <h5 class="card-title text-primary">
                                <i class="fas fa-info-circle"></i>
                                المعلومات الشخصية
                            </h5>
                            <hr>
                            <div class="mb-3">
                                <label class="text-muted small d-block">الاسم الكامل</label>
                                <strong class="fs-5">{{ $user->name }}</strong>
                            </div>
                            <div class="mb-3">
                                <label class="text-muted small d-block">البريد الإلكتروني</label>
                                <strong>{{ $user->email }}</strong>
                            </div>
                            <div class="mb-3">
                                <label class="text-muted small d-block">تاريخ إنشاء الحساب</label>
                                <strong>{{ $user->created_at->format('Y-m-d H:i') }}</strong>
                            </div>
                            @if($user->email_verified_at)
                            <div class="mb-3">
                                <label class="text-muted small d-block">تاريخ تأكيد البريد</label>
                                <strong class="text-success">
                                    <i class="fas fa-check-circle"></i>
                                    {{ $user->email_verified_at->format('Y-m-d H:i') }}
                                </strong>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- معلومات الدور --}}
                <div class="col-md-6">
                    <div class="card shadow-sm h-100">
                        <div class="card-body">
                            <h5 class="card-title text-primary">
                                <i class="fas fa-shield-alt"></i>
                                معلومات الدور والصلاحيات
                            </h5>
                            <hr>
                            
                            @if($user->role)
                                <div class="mb-3">
                                    <label class="text-muted small d-block">الدور الحالي</label>
                                    <span class="badge bg-info text-dark fs-6 p-2">
                                        <i class="fas fa-shield-alt"></i>
                                        {{ $user->role->display_name }}
                                    </span>
                                </div>
                                <div class="mb-3">
                                    <label class="text-muted small d-block">وصف الدور</label>
                                    <p class="mb-0">{{ $user->role->description ?? 'لا يوجد وصف' }}</p>
                                </div>
                                <div class="mb-3">
                                    <label class="text-muted small d-block">حالة الدور</label>
                                    <span class="badge bg-{{ $user->role->is_active ? 'success' : 'secondary' }}">
                                        {{ $user->role->is_active ? 'نشط' : 'غير نشط' }}
                                    </span>
                                </div>

                                <hr>
                                <h6 class="text-muted">الصلاحيات الممنوحة ({{ $user->role->permissions_count }})</h6>
                                <div class="row g-2">
                                    @forelse($user->role->permissions as $permission)
                                        <div class="col-6">
                                            <div class="border rounded p-2 bg-light small">
                                                <i class="fas fa-key text-warning"></i>
                                                {{ $permission->display_name }}
                                            </div>
                                        </div>
                                    @empty
                                        <div class="col-12 text-center text-muted py-3">
                                            لا توجد صلاحيات ممنوحة لهذا الدور.
                                        </div>
                                    @endforelse
                                </div>
                            @else
                                <div class="alert alert-warning">
                                    <i class="fas fa-exclamation-triangle"></i>
                                    هذا المستخدم ليس لديه دور معين.
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection