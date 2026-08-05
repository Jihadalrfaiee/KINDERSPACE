@extends('layouts.app')
@section('title','الرواتب')
@section('content')
<div class="container">
    <div class="d-flex justify-content-between mb-3">
        <h3>قائمة الرواتب</h3>
        <a href="{{ route('salaries.create') }}" class="btn btn-primary">إضافة راتب</a>
    </div>
    <table class="table table-sm">
        <thead><tr><th>#</th><th>الموظف</th><th>المبلغ</th><th>الشهر</th><th>الحالة</th></tr></thead>
        <tbody>
            @foreach($salaries as $s)
            <tr>
                <td>{{ $s->id }}</td>
                <td>{{ $s->employee->full_name ?? '-' }}</td>
                <td>{{ $s->amount }}</td>
                <td>{{ $s->month }}</td>
                <td>{{ $s->status }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    {{ $salaries->links() }}
</div>
@endsection
