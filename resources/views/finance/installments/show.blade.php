@extends('layouts.app')
@section('title','عرض القسط')
@section('content')
<div class="container">
    <h3>عرض القسط #{{ $installment->id }}</h3>
    <ul class="list-group mb-3">
        <li class="list-group-item">قيد الرسوم: <a href="{{ route('student-fees.show', $installment->studentFee) }}">#{{ $installment->student_fee_id }}</a></li>
        <li class="list-group-item">الطالب: {{ $installment->student->full_name ?? '-' }}</li>
        <li class="list-group-item">رقم القسط: {{ $installment->installment_number }}</li>
        <li class="list-group-item">المبلغ: {{ $installment->amount }}</li>
        <li class="list-group-item">تاريخ الاستحقاق: {{ $installment->due_date }}</li>
        <li class="list-group-item">تاريخ الدفع: {{ $installment->paid_date ?? '-' }}</li>
        <li class="list-group-item">المبلغ المدفوع: {{ $installment->paid_amount }}</li>
        <li class="list-group-item">الحالة: {{ $installment->status }}</li>
        <li class="list-group-item">المستلم: {{ $installment->receiver->name ?? '-' }}</li>
        <li class="list-group-item">ملاحظات: {{ $installment->notes ?? '-' }}</li>
    </ul>
    <a href="{{ route('installments.index') }}" class="btn btn-secondary">العودة</a>
</div>
@endsection
