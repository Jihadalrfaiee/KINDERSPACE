@extends('layouts.app')
@section('title','عرض الراتب')
@section('content')
<div class="container">
    <h3>عرض الراتب #{{ $salary->id }}</h3>
    <ul class="list-group mb-3">
        <li class="list-group-item">الموظف: {{ $salary->user->name ?? '-' }}</li>
        <li class="list-group-item">الراتب الأساسي: {{ $salary->base_salary }}</li>
        <li class="list-group-item">المكافأة: {{ $salary->bonus }}</li>
        <li class="list-group-item">الاستقطاعات: {{ $salary->deductions }}</li>
        <li class="list-group-item">الصافي: {{ $salary->net_salary }}</li>
        <li class="list-group-item">الشهر/السنة: {{ $salary->month }}/{{ $salary->year }}</li>
        <li class="list-group-item">تاريخ الدفع: {{ $salary->paid_date ?? '-' }}</li>
        <li class="list-group-item">الحالة: {{ $salary->status }}</li>
        <li class="list-group-item">معتمد بواسطة: {{ $salary->approver->name ?? '-' }}</li>
        <li class="list-group-item">ملاحظات: {{ $salary->notes ?? '-' }}</li>
    </ul>
    <a href="{{ route('salaries.index') }}" class="btn btn-secondary">العودة</a>
</div>
@endsection
