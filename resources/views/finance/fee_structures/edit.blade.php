@extends('layouts.app')

@section('title','تعديل هيكل رسوم')

@section('content')
<div class="container">
    <h3>تعديل هيكل رسوم</h3>

    <form method="POST" action="{{ route('fee-structures.update', $feeStructure) }}">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">الروضة</label>
            <select name="kindergarten_id" class="form-control">
                @foreach($kindergartens as $k)
                    <option value="{{ $k->id }}" @if($k->id == $feeStructure->kindergarten_id) selected @endif>{{ $k->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">السنة الدراسية</label>
            <input name="academic_year" value="{{ $feeStructure->academic_year }}" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">الرسوم الدراسية</label>
            <input name="tuition_fee" value="{{ $feeStructure->tuition_fee }}" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">رسوم المواصلات</label>
            <input name="transportation_fee" value="{{ $feeStructure->transportation_fee }}" class="form-control">
        </div>

        <div class="mb-3">
            <label class="form-label">عدد الأقساط</label>
            <input name="installments_count" value="{{ $feeStructure->installments_count }}" class="form-control">
        </div>

        <button class="btn btn-primary">تحديث</button>
    </form>
</div>
@endsection
