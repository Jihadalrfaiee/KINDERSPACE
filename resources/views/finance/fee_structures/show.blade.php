@extends('layouts.app')

@section('title','عرض هيكل الرسوم')

@section('content')
<div class="container">
    <h3>هيكل الرسوم - السنة: {{ $feeStructure->academic_year }}</h3>

    <ul class="list-group">
        <li class="list-group-item">الروضة: {{ $feeStructure->kindergarten->name ?? '-' }}</li>
        <li class="list-group-item">الرسوم: {{ $feeStructure->tuition_fee }}</li>
        <li class="list-group-item">مواصلات: {{ $feeStructure->transportation_fee }}</li>
        <li class="list-group-item">عدد الأقساط: {{ $feeStructure->installments_count }}</li>
    </ul>

    <a href="{{ route('fee-structures.index') }}" class="btn btn-link mt-3">العودة</a>
</div>
@endsection
