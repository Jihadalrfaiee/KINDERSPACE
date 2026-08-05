@extends('layouts.app')

@section('title','إنشاء هيكل رسوم')

@section('content')
<div class="container">
    <h3>إنشاء هيكل رسوم</h3>

    <form method="POST" action="{{ route('fee-structures.store') }}">
        @csrf

        <div class="mb-3">
            <label class="form-label">الروضة</label>
            <select name="kindergarten_id" class="form-control">
                @foreach($kindergartens as $k)
                    <option value="{{ $k->id }}">{{ $k->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">السنة الدراسية</label>
            <input name="academic_year" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">الرسوم الدراسية</label>
            <input name="tuition_fee" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">رسوم المواصلات</label>
            <input name="transportation_fee" class="form-control">
        </div>

        <div class="mb-3">
            <label class="form-label">عدد الأقساط</label>
            <input name="installments_count" value="5" class="form-control">
        </div>

        <button class="btn btn-primary">حفظ</button>
    </form>
</div>
@endsection
