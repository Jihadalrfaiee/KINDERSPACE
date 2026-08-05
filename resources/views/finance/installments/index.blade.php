@extends('layouts.app')
@section('title','الأقساط')
@section('content')
<div class="container">
    <div class="d-flex justify-content-between mb-3">
        <h3>قائمة الأقساط</h3>
        <a href="{{ route('installments.create') }}" class="btn btn-primary">إنشاء قسط</a>
    </div>
    <table class="table table-sm">
        <thead><tr><th>#</th><th>طالب</th><th>قيمة</th><th>تاريخ الاستحقاق</th><th>الحالة</th></tr></thead>
        <tbody>
            @foreach($installments as $inst)
            <tr>
                <td>{{ $inst->id }}</td>
                <td>{{ $inst->student->full_name ?? '-' }}</td>
                <td>{{ $inst->amount }}</td>
                <td>{{ $inst->due_date }}</td>
                <td>{{ $inst->status }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    {{ $installments->links() }}
</div>
@endsection
