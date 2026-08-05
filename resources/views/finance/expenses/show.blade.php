@extends('layouts.app')
@section('title','عرض المصروف')
@section('content')
<div class="container">
    <h3>عرض المصروف #{{ $expense->id }}</h3>
    <ul class="list-group mb-3">
        <li class="list-group-item">الروضة: {{ $expense->kindergarten->name ?? '-' }}</li>
        <li class="list-group-item">الفئة: {{ $expense->category }}</li>
        <li class="list-group-item">الوصف: {{ $expense->description }}</li>
        <li class="list-group-item">المبلغ: {{ $expense->amount }}</li>
        <li class="list-group-item">التاريخ: {{ $expense->date }}</li>
        <li class="list-group-item">المُسجِّل: {{ $expense->creator->name ?? '-' }}</li>
        <li class="list-group-item">إيصال: {{ $expense->receipt_image ?? '-' }}</li>
    </ul>
    <a href="{{ route('expenses.index') }}" class="btn btn-secondary">العودة</a>
</div>
@endsection
