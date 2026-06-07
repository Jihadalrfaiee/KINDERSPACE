@extends('layouts.app')

@section('title', 'إضافة مستخدم جديد')

@section('content')
<div class="container-fluid px-4 py-3">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            {{-- رأس الصفحة --}}
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="mb-1">
                        <i class="fas fa-user-plus text-primary"></i>
                        إضافة مستخدم جديد
                    </h2>
                    <p class="text-muted mb-0">قم بإنشاء حساب مستخدم جديد وتعيين دوره</p>
                </div>
                <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-right"></i>
                    رجوع
                </a>
            </div>

            {{-- نموذج الإضافة --}}
            <div class="card shadow-sm">
                <div class="card-body p-4">
                    <form action="{{ route('users.store') }}" method="POST">
                        @csrf

                        <div class="row g-3">
                            {{-- الاسم --}}
                            <div class="col-md-6">
                                <label class="form-label">الاسم الكامل <span class="text-danger">*</span></label>
                                <input type="text" name="name" 
                                       class="form-control @error('name') is-invalid @enderror" 
                                       value="{{ old('name') }}" 
                                       placeholder="مثال: سارة محمد" 
                                       required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- البريد الإلكتروني --}}
                            <div class="col-md-6">
                                <label class="form-label">البريد الإلكتروني <span class="text-danger">*</span></label>
                                <input type="email" name="email" 
                                       class="form-control @error('email') is-invalid @enderror" 
                                       value="{{ old('email') }}" 
                                       placeholder="example@domain.com" 
                                       required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- كلمة المرور --}}
                            <div class="col-md-6">
                                <label class="form-label">كلمة المرور <span class="text-danger">*</span></label>
                                <input type="password" name="password" 
                                       class="form-control @error('password') is-invalid @enderror" 
                                       placeholder="8 أحرف على الأقل" 
                                       required>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">يجب أن تكون 8 أحرف على الأقل</small>
                            </div>

                            {{-- تأكيد كلمة المرور --}}
                            <div class="col-md-6">
                                <label class="form-label">تأكيد كلمة المرور <span class="text-danger">*</span></label>
                                <input type="password" name="password_confirmation" 
                                       class="form-control" 
                                       placeholder="أعد كتابة كلمة المرور" 
                                       required>
                            </div>

                            {{-- الدور --}}
                            <div class="col-12">
                                <label class="form-label">تعيين الدور <span class="text-danger">*</span></label>
                                <select name="role_id" 
                                        class="form-select @error('role_id') is-invalid @enderror" 
                                        required>
                                    <option value="">-- اختر الدور --</option>
                                    @foreach($roles as $role)
                                        <option value="{{ $role->id }}" 
                                                {{ old('role_id') == $role->id ? 'selected' : '' }}>
                                            {{ $role->display_name }}
                                            @if($role->description)
                                                - {{ $role->description }}
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                                @error('role_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- أزرار الإجراءات --}}
                            <div class="col-12 d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
                                <a href="{{ route('users.index') }}" class="btn btn-light">
                                    <i class="fas fa-times"></i>
                                    إلغاء
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i>
                                    حفظ المستخدم
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection