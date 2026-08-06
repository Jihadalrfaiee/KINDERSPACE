@extends('layouts.app')
@section('title','إضافة قيد رسوم')
@section('content')
<div class="container">
    <h3>إضافة قيد رسوم</h3>
    <form method="POST" action="{{ route('student-fees.store') }}">
        @csrf
        <div class="mb-3">
            <label class="form-label">الطالب</label>
            <select name="student_id" class="form-control">
                @foreach($students as $s)
                <option value="{{ $s->id }}">{{ $s->full_name }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">السنة الدراسية</label>
            <input name="academic_year" class="form-control" value="{{ config('app.current_academic_year', '2024-2025') }}">
        </div>
        <div class="mb-3">
            <label class="form-label">مجموع الرسوم</label>
            <input name="total_tuition" class="form-control" value="{{ old('total_tuition') }}">
        </div>
        <div class="mb-3">
            <label class="form-label">مجموع مواصلات</label>
            <input name="total_transportation" class="form-control" value="{{ old('total_transportation', 0) }}">
        </div>
        <div class="mb-3">
            <label class="form-label">الخصم</label>
            <input name="discount_amount" class="form-control" value="{{ old('discount_amount', 0) }}">
        </div>
        <div class="mb-3">
            <label class="form-label">سبب الخصم</label>
            <input name="discount_reason" class="form-control" value="{{ old('discount_reason') }}">
        </div>
        <div class="mb-3">
            <label class="form-label">صافي</label>
            <input name="net_amount" class="form-control" value="{{ old('net_amount') }}">
        </div>
        <div class="mb-3">
            <label class="form-label">المدفوع</label>
            <input name="paid_amount" class="form-control" value="{{ old('paid_amount', 0) }}">
        </div>
        <div class="mb-3">
            <label class="form-label">المتبقي</label>
            <input name="remaining_amount" class="form-control" value="{{ old('remaining_amount', old('net_amount')) }}">
        </div>
        <div class="mb-3">
            <label class="form-label">الحالة</label>
            <select name="status" class="form-control">
                <option value="pending" selected>معلق</option>
                <option value="partial">جزئي</option>
                <option value="paid">مدفوع</option>
            </select>
        </div>
        <button class="btn btn-primary">حفظ</button>
    </form>
</div>
@endsection
