@extends('layouts.app')
@section('title','عرض قيد الرسوم')
@section('content')
<div class="container">
    <h3>قيد الرسوم للطالب: {{ $studentFee->student->full_name ?? '-' }}</h3>

    <ul class="list-group">
        <li class="list-group-item">السنة: {{ $studentFee->academic_year }}</li>
        <li class="list-group-item">المبلغ الصافي: {{ $studentFee->net_amount }}</li>
        <li class="list-group-item">المدفوع: {{ $studentFee->paid_amount }}</li>
        <li class="list-group-item">المتبقي: {{ $studentFee->remaining_amount }}</li>
    </ul>

    <h5 class="mt-3">الأقساط</h5>
    <table class="table table-sm">
        <thead><tr><th>#</th><th>رقم</th><th>المبلغ</th><th>تاريخ الاستحقاق</th><th>الحالة</th></tr></thead>
        <tbody>
            @foreach($studentFee->installments as $inst)
            <tr>
                <td>{{ $inst->id }}</td>
                <td>{{ $inst->installment_number }}</td>
                <td>{{ $inst->amount }}</td>
                <td>{{ $inst->due_date }}</td>
                <td>{{ $inst->status }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <a href="{{ route('student-fees.index') }}" class="btn btn-link">العودة</a>
</div>
@endsection
