@extends('layouts.app')
@section('title','قيود رسوم الطلاب')
@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>قيود رسوم الطلاب</h3>
        <a href="{{ route('student-fees.create') }}" class="btn btn-primary">إضافة قيد</a>
    </div>

    <table class="table table-striped">
        <thead><tr><th>#</th><th>الطالب</th><th>السنة</th><th>صافي</th><th>مدفوع</th><th>المتبقي</th><th>الحالة</th><th></th></tr></thead>
        <tbody>
            @foreach($fees as $f)
            <tr>
                <td>{{ $f->id }}</td>
                <td>{{ $f->student->full_name ?? '-' }}</td>
                <td>{{ $f->academic_year }}</td>
                <td>{{ $f->net_amount }}</td>
                <td>{{ $f->paid_amount }}</td>
                <td>{{ $f->remaining_amount }}</td>
                <td>{{ ucfirst($f->status) }}</td>
                <td>
                    <a href="{{ route('student-fees.show', $f) }}" class="btn btn-sm btn-info">عرض</a>
                    <a href="{{ route('student-fees.edit', $f) }}" class="btn btn-sm btn-secondary">تعديل</a>
                    <form method="POST" action="{{ route('student-fees.destroy', $f) }}" style="display:inline-block">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger">حذف</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    {{ $fees->links() }}
</div>
@endsection
