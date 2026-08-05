@extends('layouts.app')
@section('title','المصروفات')
@section('content')
<div class="container">
    <div class="d-flex justify-content-between mb-3">
        <h3>قائمة المصروفات</h3>
        <a href="{{ route('expenses.create') }}" class="btn btn-primary">إضافة مصروف</a>
    </div>
    <table class="table table-sm">
        <thead><tr><th>#</th><th>الروضة</th><th>الفئة</th><th>المبلغ</th><th>التاريخ</th><th></th></tr></thead>
        <tbody>
            @foreach($expenses as $e)
            <tr>
                <td>{{ $e->id }}</td>
                <td>{{ $e->kindergarten->name ?? '-' }}</td>
                <td>{{ $e->category }}</td>
                <td>{{ $e->amount }}</td>
                <td>{{ $e->date }}</td>
                <td>
                    <a href="{{ route('expenses.show', $e) }}" class="btn btn-sm btn-info">عرض</a>
                    <a href="{{ route('expenses.edit', $e) }}" class="btn btn-sm btn-secondary">تعديل</a>
                    <form method="POST" action="{{ route('expenses.destroy', $e) }}" style="display:inline-block">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger">حذف</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    {{ $expenses->links() }}
</div>
@endsection
