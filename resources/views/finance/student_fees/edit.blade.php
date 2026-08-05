@extends('layouts.app')
@section('title','تعديل قيد الرسوم')
@section('content')
<div class="container">
    <h3>تعديل قيد الرسوم</h3>
    <form method="POST" action="{{ route('student-fees.update', $studentFee) }}">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">الطالب</label>
            <select name="student_id" class="form-control">
                @foreach($students as $s)
                    <option value="{{ $s->id }}" {{ $studentFee->student_id == $s->id ? 'selected' : '' }}>{{ $s->full_name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">الروضة</label>
            <select name="kindergarten_id" class="form-control">
                @foreach($kindergartens as $kg)
                    <option value="{{ $kg->id }}" {{ $studentFee->kindergarten_id == $kg->id ? 'selected' : '' }}>{{ $kg->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="row">
            <div class="col-md-4 mb-3">
                <label class="form-label">السنة الدراسية</label>
                <input name="academic_year" class="form-control" value="{{ old('academic_year', $studentFee->academic_year) }}">
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">إجمالي الرسوم</label>
                <input name="total_tuition" class="form-control" value="{{ old('total_tuition', $studentFee->total_tuition) }}">
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">المواصلات</label>
                <input name="total_transportation" class="form-control" value="{{ old('total_transportation', $studentFee->total_transportation) }}">
            </div>
        </div>

        <div class="row">
            <div class="col-md-4 mb-3">
                <label class="form-label">الخصم</label>
                <input name="discount_amount" class="form-control" value="{{ old('discount_amount', $studentFee->discount_amount) }}">
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">سبب الخصم</label>
                <input name="discount_reason" class="form-control" value="{{ old('discount_reason', $studentFee->discount_reason) }}">
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">الصافي</label>
                <input name="net_amount" class="form-control" value="{{ old('net_amount', $studentFee->net_amount) }}">
            </div>
        </div>

        <div class="row">
            <div class="col-md-4 mb-3">
                <label class="form-label">المدفوع</label>
                <input name="paid_amount" class="form-control" value="{{ old('paid_amount', $studentFee->paid_amount) }}">
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">المتبقي</label>
                <input name="remaining_amount" class="form-control" value="{{ old('remaining_amount', $studentFee->remaining_amount) }}">
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">الحالة</label>
                <select name="status" class="form-control">
                    <option value="pending" {{ $studentFee->status === 'pending' ? 'selected' : '' }}>معلق</option>
                    <option value="partial" {{ $studentFee->status === 'partial' ? 'selected' : '' }}>جزئي</option>
                    <option value="paid" {{ $studentFee->status === 'paid' ? 'selected' : '' }}>مدفوع</option>
                </select>
            </div>
        </div>

        <button class="btn btn-primary">حفظ التغييرات</button>
        <a href="{{ route('student-fees.index') }}" class="btn btn-secondary">إلغاء</a>
    </form>
</div>
@endsection
