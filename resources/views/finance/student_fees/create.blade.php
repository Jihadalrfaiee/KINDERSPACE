@extends('layouts.app')
@section('title','إضافة قيد رسوم')
@section('content')
<div class="container">
    <h3>إضافة قيد رسوم</h3>
    <form method="POST" action="{{ route('student-fees.store') }}">
        @csrf
        <div class="mb-3">
            <label class="form-label">الطالب</label>
            <select name="student_id" class="form-control">
                @foreach($students as $s)
                <option value="{{ $s->id }}">{{ $s->full_name }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">السنة الدراسية</label>
            <input name="academic_year" class="form-control" value="{{ config('app.current_academic_year', '2024-2025') }}">
        </div>
        <div class="mb-3">
            <label class="form-label">مجموع الرسوم</label>
            <input name="total_tuition" class="form-control">
        </div>
        <div class="mb-3">
            <label class="form-label">صافي</label>
            <input name="net_amount" class="form-control">
        </div>
        <button class="btn btn-primary">حفظ</button>
    </form>
</div>
@endsection
