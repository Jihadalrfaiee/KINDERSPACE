@extends('layouts.app')
@section('title','الأقساط')
@section('content')
<div class="container">
    <div class="d-flex justify-content-between mb-3">
        <h3>قائمة الأقساط</h3>
        <a href="{{ route('installments.create') }}" class="btn btn-primary">إنشاء قسط</a>
    </div>
    <table class="table table-sm">
        <thead><tr><th>#</th><th>قيد الرسوم</th><th>طالب</th><th>قيمة</th><th>تاريخ الاستحقاق</th><th>الحالة</th><th></th></tr></thead>
        <tbody>
            @foreach($installments as $inst)
            <tr>
                <td>{{ $inst->id }}</td>
                <td><a href="{{ route('student-fees.show', $inst->studentFee) }}">#{{ $inst->student_fee_id }}</a></td>
                <td>{{ $inst->student->full_name ?? '-' }}</td>
                <td>{{ $inst->amount }}</td>
                <td>{{ $inst->due_date }}</td>
                <td>{{ ucfirst($inst->status) }}</td>
                <td>
                    <a href="{{ route('installments.show', $inst) }}" class="btn btn-sm btn-info">عرض</a>
                    <a href="{{ route('installments.edit', $inst) }}" class="btn btn-sm btn-secondary">تعديل</a>
                    <form method="POST" action="{{ route('installments.destroy', $inst) }}" style="display:inline-block">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger">حذف</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    {{ $installments->links() }}
</div>
@endsection
