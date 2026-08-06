@extends('layouts.app')
@section('title','تعديل الراتب')
@section('content')
<div class="container">
    <h3>تعديل الراتب</h3>
    <form method="POST" action="{{ route('salaries.update', $salary) }}">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">الموظف</label>
            <select name="user_id" class="form-control">
                @foreach($users as $user)
                    <option value="{{ $user->id }}" {{ $salary->user_id == $user->id ? 'selected' : '' }}>{{ $user->name }} ({{ $user->role }})</option>
                @endforeach
            </select>
        </div>

        <div class="row">
            <div class="col-md-4 mb-3">
                <label class="form-label">الراتب الأساسي</label>
                <input name="base_salary" class="form-control" value="{{ old('base_salary', $salary->base_salary) }}">
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">المكافأة</label>
                <input name="bonus" class="form-control" value="{{ old('bonus', $salary->bonus) }}">
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">الاستقطاعات</label>
                <input name="deductions" class="form-control" value="{{ old('deductions', $salary->deductions) }}">
            </div>
        </div>

        <div class="row">
            <div class="col-md-4 mb-3">
                <label class="form-label">الصافي</label>
                <input name="net_salary" class="form-control" value="{{ old('net_salary', $salary->net_salary) }}">
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">الشهر</label>
                <input type="number" name="month" min="1" max="12" class="form-control" value="{{ old('month', $salary->month) }}">
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">السنة</label>
                <input type="number" name="year" min="2000" class="form-control" value="{{ old('year', $salary->year) }}">
            </div>
        </div>

        <div class="row">
            <div class="col-md-4 mb-3">
                <label class="form-label">تاريخ الدفع</label>
                <input type="date" name="paid_date" class="form-control" value="{{ old('paid_date', $salary->paid_date?->format('Y-m-d')) }}">
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">الحالة</label>
                <select name="status" class="form-control">
                    <option value="pending" {{ $salary->status === 'pending' ? 'selected' : '' }}>قيد الانتظار</option>
                    <option value="paid" {{ $salary->status === 'paid' ? 'selected' : '' }}>مدفوع</option>
                </select>
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">معتمد بواسطة</label>
                <select name="approved_by" class="form-control">
                    <option value="">---</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ $salary->approved_by == $user->id ? 'selected' : '' }}>{{ $user->name }} ({{ $user->role }})</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">ملاحظات</label>
            <textarea name="notes" class="form-control" rows="3">{{ old('notes', $salary->notes) }}</textarea>
        </div>

        <button class="btn btn-primary">حفظ</button>
        <a href="{{ route('salaries.index') }}" class="btn btn-secondary">إلغاء</a>
    </form>
</div>
@endsection
