@extends('layouts.app')
@section('title','الرواتب')
@section('content')
<div class="container">
    <div class="d-flex justify-content-between mb-3">
        <h3>قائمة الرواتب</h3>
        <a href="{{ route('salaries.create') }}" class="btn btn-primary">إضافة راتب</a>
    </div>
    <table class="table table-sm">
        <thead><tr><th>#</th><th>الموظف</th><th>الراتب الصافي</th><th>الشهر</th><th>الحالة</th><th></th></tr></thead>
        <tbody>
            @foreach($salaries as $s)
            <tr>
                <td>{{ $s->id }}</td>
                <td>{{ $s->user->name ?? '-' }}</td>
                <td>{{ $s->net_salary }}</td>
                <td>{{ $s->month }}/{{ $s->year }}</td>
                <td>{{ ucfirst($s->status) }}</td>
                <td>
                    <a href="{{ route('salaries.show', $s) }}" class="btn btn-sm btn-info">عرض</a>
                    <a href="{{ route('salaries.edit', $s) }}" class="btn btn-sm btn-secondary">تعديل</a>
                    <form method="POST" action="{{ route('salaries.destroy', $s) }}" style="display:inline-block">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger">حذف</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    {{ $salaries->links() }}
</div>
@endsection
