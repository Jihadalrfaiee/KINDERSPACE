@extends('layouts.app')

@section('title','هيكل الرسوم')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>قوائم هياكل الرسوم</h3>
        <a href="{{ route('fee-structures.create') }}" class="btn btn-primary">إنشاء هيكل جديد</a>
    </div>

    <table class="table table-striped">
        <thead>
            <tr>
                <th>#</th>
                <th>الروضة</th>
                <th>السنة</th>
                <th>الرسوم</th>
                <th>مواصلات</th>
                <th>أقساط</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach($feeStructures as $fs)
            <tr>
                <td>{{ $fs->id }}</td>
                <td>{{ $fs->kindergarten->name ?? '-' }}</td>
                <td>{{ $fs->academic_year }}</td>
                <td>{{ $fs->tuition_fee }}</td>
                <td>{{ $fs->transportation_fee }}</td>
                <td>{{ $fs->installments_count }}</td>
                <td>
                    <a href="{{ route('fee-structures.edit', $fs) }}" class="btn btn-sm btn-secondary">تعديل</a>
                    <form method="POST" action="{{ route('fee-structures.destroy', $fs) }}" style="display:inline-block">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger">حذف</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{ $feeStructures->links() }}
</div>
@endsection
