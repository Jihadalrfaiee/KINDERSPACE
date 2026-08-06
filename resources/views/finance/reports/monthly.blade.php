@extends('layouts.app')

@section('content')
<div class="container">
    <h2>تقرير مالي شهري: {{ $start->format('F Y') }}</h2>

    <div class="card">
        <div class="card-body">
            <p><strong>إجمالي الإيرادات المتوقعة (الأقساط المستحقة):</strong> {{ number_format($expectedIncome, 2) }}</p>
            <p><strong>إجمالي المصاريف:</strong> {{ number_format($expenses, 2) }}</p>
            <p><strong>إجمالي الرواتب:</strong> {{ number_format($salaries, 2) }}</p>
            <p><strong>صافي التشغيل:</strong> {{ number_format($expectedIncome - ($expenses + $salaries), 2) }}</p>
        </div>
    </div>
</div>
@endsection