@extends('layouts.app')

@section('content')
<div class="container">
    <h2>رصيد الطالب: {{ $student->first_name }} {{ $student->family_name }}</h2>

    <div class="card mb-3">
        <div class="card-body">
            <p><strong>إجمالي المستحق:</strong> {{ number_format($totalNet, 2) }}</p>
            <p><strong>المسدد:</strong> {{ number_format($totalPaid, 2) }}</p>
            <p><strong>المتبقي:</strong> {{ number_format($totalRemaining, 2) }}</p>
        </div>
    </div>

    <h4>الأقساط المستحقة</h4>
    <table class="table">
        <thead>
            <tr>
                <th>قيد</th>
                <th>رقم القسط</th>
                <th>المبلغ</th>
                <th>تاريخ الاستحقاق</th>
                <th>الحالة</th>
            </tr>
        </thead>
        <tbody>
            @foreach($dueInstallments as $ins)
                <tr>
                    <td>{{ $ins->student_fee_id }}</td>
                    <td>{{ $ins->installment_number }}</td>
                    <td>{{ number_format($ins->amount,2) }}</td>
                    <td>{{ $ins->due_date }}</td>
                    <td>{{ $ins->status }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection