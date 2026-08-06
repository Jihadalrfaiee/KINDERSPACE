@extends('layouts.app')
@section('title','إضافة قسط')
@section('content')
<div class="container">
    <h3>إضافة قسط</h3>
    <form method="POST" action="{{ route('installments.store') }}">
        @csrf
        <div class="mb-3">
            <label class="form-label">قيد الرسوم</label>
            <select name="student_fee_id" class="form-control">
                @foreach($fees as $fee)
                    <option value="{{ $fee->id }}">#{{ $fee->id }} - {{ $fee->student->full_name ?? 'طالب غير معروف' }} - {{ $fee->academic_year }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">الطالب</label>
            <select name="student_id" class="form-control">
                @foreach($fees as $fee)
                    <option value="{{ $fee->student_id }}">{{ $fee->student->full_name ?? 'طالب غير معروف' }}</option>
                @endforeach
            </select>
        </div>
        <div class="row">
            <div class="col-md-4 mb-3">
                <label class="form-label">رقم القسط</label>
                <input name="installment_number" class="form-control" value="{{ old('installment_number', 1) }}">
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">المبلغ</label>
                <input name="amount" class="form-control" value="{{ old('amount') }}">
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">تاريخ الاستحقاق</label>
                <input type="date" name="due_date" class="form-control" value="{{ old('due_date', now()->format('Y-m-d')) }}">
            </div>
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
        <a href="{{ route('installments.index') }}" class="btn btn-secondary">إلغاء</a>
    </form>
</div>
@endsection
