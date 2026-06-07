@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-dark mb-1"><i class="fas fa-users-cog text-primary me-2"></i> إدارة المستخدمين</h3>
            <p class="text-muted mb-0">إضافة وتعديل حسابات المعلمات والموظفين</p>
        </div>
        <a href="{{ route('users.create') }}" class="btn btn-primary shadow-sm rounded-pill px-4">
            <i class="fas fa-user-plus me-1"></i> إضافة مستخدم
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }} 
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm">
            <i class="fas fa-exclamation-triangle me-2"></i>{{ session('error') }} 
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="px-4 py-3">الاسم</th>
                            <th class="py-3">البريد الإلكتروني</th>
                            <th class="py-3">الدور (الصلاحية)</th>
                            <th class="py-3 text-center">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                        <tr>
                            <td class="px-4 py-3 fw-bold">
                                <i class="fas fa-user-circle text-secondary me-2 fs-5"></i> {{ $user->name }}
                            </td>
                            <td class="py-3 text-muted">{{ $user->email }}</td>
                            <td class="py-3">
                                {{-- التعديل الاستراتيجي هنا: التحقق من وجود أدوار للمستخدم وعرضها بحلقة تكرارية --}}
                                @if($user->roles && $user->roles->count() > 0)
                                    @foreach($user->roles as $role)
                                        <span class="badge bg-primary bg-opacity-10 text-primary border border-primary px-3 py-2 rounded-pill d-inline-block mb-1">
                                            {{ $role->display_name ?? $role->name }}
                                        </span>
                                    @endforeach
                                @else
                                    <span class="badge bg-secondary px-3 py-2 rounded-pill">بدون دور</span>
                                @endif
                            </td>
                            <td class="py-3 text-center">
                                <a href="{{ route('users.show', $user) }}" class="btn btn-sm btn-outline-info rounded-pill px-3 mb-1">
                                    <i class="fas fa-eye"></i> عرض
                                </a>
                                <a href="{{ route('users.edit', $user) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3 mb-1">
                                    <i class="fas fa-edit"></i> تعديل
                                </a>
                                @if(auth()->id() !== $user->id)
                                <form action="{{ route('users.destroy', $user) }}" method="POST" class="d-inline" onsubmit="return confirm('هل أنت متأكد من رغبتك في حذف هذا الحساب؟');">
                                    @csrf 
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill px-3 mb-1">
                                        <i class="fas fa-trash"></i> حذف
                                    </button>
                                </form>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @if($users->hasPages())
        <div class="card-footer bg-white border-top p-3">
            {{ $users->links() }}
        </div>
        @endif
    </div>
</div>
@endsection