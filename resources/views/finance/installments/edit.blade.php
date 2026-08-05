@extends('layouts.app')
@section('title','تعديل القسط')
@section('content')
<div class="container">
    <h3>تعديل القسط</h3>
    <form method="POST" action="{{ route('installments.update', $installment) }}">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">قيد الرسوم</label>
            <select name="student_fee_id" class="form-control">
                @foreach($fees as $fee)
                    <option value="{{ $fee->id }}" {{ $installment->student_fee_id == $fee->id ? 'selected' : '' }}>#{{ $fee->id }} - {{ $fee->student->full_name ?? 'طالب غير معروف' }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">الطالب</label>
            <select name="student_id" class="form-control">
                @foreach($fees as $fee)
                    <option value="{{ $fee->student_id }}" {{ $installment->student_id == $fee->student_id ? 'selected' : '' }}>{{ $fee->student->full_name ?? 'طالب غير معروف' }}</option>
                @endforeach
            </select>
        </div>
        <div class="row">
            <div class="col-md-4 mb-3">
                <label class="form-label">رقم القسط</label>
                <input name="installment_number" class="form-control" value="{{ old('installment_number', $installment->installment_number) }}">
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">المبلغ</label>
                <input name="amount" class="form-control" value="{{ old('amount', $installment->amount) }}">
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">تاريخ الاستحقاق</label>
                <input type="date" name="due_date" class="form-control" value="{{ old('due_date', $installment->due_date?->format('Y-m-d')) }}">
            </div>
        </div>
        <div class="row">
            <div class="col-md-4 mb-3">
                <label class="form-label">تاريخ الدفع</label>
                <input type="date" name="paid_date" class="form-control" value="{{ old('paid_date', $installment->paid_date?->format('Y-m-d')) }}">
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">المبلغ المدفوع</label>
                <input name="paid_amount" class="form-control" value="{{ old('paid_amount', $installment->paid_amount) }}">
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">الحالة</label>
                <select name="status" class="form-control">
                    <option value="pending" {{ $installment->status === 'pending' ? 'selected' : '' }}>معلق</option>
                    <option value="partial" {{ $installment->status === 'partial' ? 'selected' : '' }}>جزئي</option>
                    <option value="paid" {{ $installment->status === 'paid' ? 'selected' : '' }}>مدفوع</option>
                </select>
            </div>
        </div>
        <button class="btn btn-primary">حفظ</button>
        <a href="{{ route('installments.index') }}" class="btn btn-secondary">إلغاء</a>
    </form>
</div>
@endsection
