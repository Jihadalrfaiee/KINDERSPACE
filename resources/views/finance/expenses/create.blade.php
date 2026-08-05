@extends('layouts.app')
@section('title','إضافة مصروف')
@section('content')
<div class="container">
    <h3>إضافة مصروف</h3>
    <form method="POST" action="{{ route('expenses.store') }}">
        @csrf
        <div class="mb-3">
            <label class="form-label">الروضة</label>
            <select name="kindergarten_id" class="form-control">
                @foreach($kindergartens as $kg)
                    <option value="{{ $kg->id }}">{{ $kg->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">الفئة</label>
            <input name="category" class="form-control" value="{{ old('category') }}">
        </div>
        <div class="mb-3">
            <label class="form-label">الوصف</label>
            <input name="description" class="form-control" value="{{ old('description') }}">
        </div>
        <div class="mb-3">
            <label class="form-label">المبلغ</label>
            <input name="amount" class="form-control" value="{{ old('amount') }}">
        </div>
        <div class="mb-3">
            <label class="form-label">التاريخ</label>
            <input type="date" name="date" class="form-control" value="{{ old('date', now()->format('Y-m-d')) }}">
        </div>
        <div class="mb-3">
            <label class="form-label">رابط إيصال أو اسم الملف</label>
            <input name="receipt_image" class="form-control" value="{{ old('receipt_image') }}">
        </div>
        <button class="btn btn-primary">حفظ</button>
        <a href="{{ route('expenses.index') }}" class="btn btn-secondary">إلغاء</a>
    </form>
</div>
@endsection
