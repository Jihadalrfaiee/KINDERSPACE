@extends('layouts.app')
@section('title','تعديل المصروف')
@section('content')
<div class="container">
    <h3>تعديل المصروف</h3>
    <form method="POST" action="{{ route('expenses.update', $expense) }}">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label class="form-label">الروضة</label>
            <select name="kindergarten_id" class="form-control">
                @foreach($kindergartens as $kg)
                    <option value="{{ $kg->id }}" {{ $expense->kindergarten_id == $kg->id ? 'selected' : '' }}>{{ $kg->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">الفئة</label>
            <input name="category" class="form-control" value="{{ old('category', $expense->category) }}">
        </div>
        <div class="mb-3">
            <label class="form-label">الوصف</label>
            <input name="description" class="form-control" value="{{ old('description', $expense->description) }}">
        </div>
        <div class="mb-3">
            <label class="form-label">المبلغ</label>
            <input name="amount" class="form-control" value="{{ old('amount', $expense->amount) }}">
        </div>
        <div class="mb-3">
            <label class="form-label">التاريخ</label>
            <input type="date" name="date" class="form-control" value="{{ old('date', $expense->date?->format('Y-m-d')) }}">
        </div>
        <div class="mb-3">
            <label class="form-label">رابط إيصال أو اسم الملف</label>
            <input name="receipt_image" class="form-control" value="{{ old('receipt_image', $expense->receipt_image) }}">
        </div>
        <button class="btn btn-primary">حفظ</button>
        <a href="{{ route('expenses.index') }}" class="btn btn-secondary">إلغاء</a>
    </form>
</div>
@endsection
