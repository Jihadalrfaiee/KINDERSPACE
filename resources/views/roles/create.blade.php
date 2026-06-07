@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-dark mb-1"><i class="fas fa-plus-circle text-primary me-2"></i> إضافة دور جديد</h3>
            <p class="text-muted mb-0">إنشاء دور وتحديد الصلاحيات المخصصة له</p>
        </div>
        <a href="{{ route('roles.index') }}" class="btn btn-light shadow-sm rounded-pill">
            <i class="fas fa-arrow-right me-1"></i> رجوع
        </a>
    </div>

    <form action="{{ route('roles.store') }}" method="POST">
        @csrf
        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-body p-4">
                <h5 class="fw-bold border-bottom pb-3 mb-4">المعلومات الأساسية</h5>
                <div class="row g-4">
                    <div class="col-md-6">
                        <label class="form-label fw-bold">اسم الدور (للعرض) <span class="text-danger">*</span></label>
                        <input type="text" name="display_name" class="form-control @error('display_name') is-invalid @enderror" value="{{ old('display_name') }}" placeholder="مثال: مديرة الروضة" required>
                        @error('display_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">الاسم البرمجي (باللغة الإنجليزية) <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="مثال: manager" required>
                        <small class="text-muted">يجب أن يكون بدون مسافات.</small>
                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-bold">الوصف</label>
                        <textarea name="description" class="form-control" rows="2" placeholder="وصف مهام هذا الدور">{{ old('description') }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-body p-4">
                <h5 class="fw-bold border-bottom pb-3 mb-4">تحديد الصلاحيات</h5>
                <div class="row g-4">
                    @foreach($permissions as $group => $perms)
                    <div class="col-md-6 col-lg-4">
                        <div class="p-3 bg-light rounded-3 border">
                            <h6 class="fw-bold text-primary mb-3">
                                <i class="fas fa-folder-open me-2"></i> {{ __('permissions.groups.' . $group) ?? $group }}
                            </h6>
                            @foreach($perms as $perm)
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" name="permissions[]" value="{{ $perm->id }}" id="perm_{{ $perm->id }}">
                                <label class="form-check-label" for="perm_{{ $perm->id }}">
                                    {{ $perm->display_name }}
                                </label>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="text-end">
            <button type="submit" class="btn btn-primary rounded-pill px-5 py-2 shadow-sm fw-bold">
                <i class="fas fa-save me-1"></i> حفظ الدور
            </button>
        </div>
    </form>
</div>
@endsection