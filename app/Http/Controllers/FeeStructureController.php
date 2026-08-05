<?php

namespace App\Http\Controllers;

use App\Models\FeeStructure;
use App\Models\Kindergarten;
use Illuminate\Http\Request;

class FeeStructureController extends Controller
{
    public function index()
    {
        $feeStructures = FeeStructure::with('kindergarten')->paginate(20);
        return view('finance.fee_structures.index', compact('feeStructures'));
    }

    public function create()
    {
        $kindergartens = Kindergarten::all();
        return view('finance.fee_structures.create', compact('kindergartens'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'kindergarten_id' => 'required|exists:kindergartens,id',
            'academic_year' => 'required|string',
            'tuition_fee' => 'required|numeric|min:0',
            'transportation_fee' => 'nullable|numeric|min:0',
            'installments_count' => 'required|integer|min:1',
            'is_active' => 'nullable|boolean',
        ]);

        FeeStructure::create($data);

        return redirect()->route('fee-structures.index')->with('success', 'تم إنشاء هيكل الرسوم بنجاح');
    }

    public function edit(FeeStructure $feeStructure)
    {
        $kindergartens = Kindergarten::all();
        return view('finance.fee_structures.edit', compact('feeStructure', 'kindergartens'));
    }

    public function update(Request $request, FeeStructure $feeStructure)
    {
        $data = $request->validate([
            'kindergarten_id' => 'required|exists:kindergartens,id',
            'academic_year' => 'required|string',
            'tuition_fee' => 'required|numeric|min:0',
            'transportation_fee' => 'nullable|numeric|min:0',
            'installments_count' => 'required|integer|min:1',
            'is_active' => 'nullable|boolean',
        ]);

        $feeStructure->update($data);

        return redirect()->route('fee-structures.index')->with('success', 'تم تحديث هيكل الرسوم');
    }

    public function show(FeeStructure $feeStructure)
    {
        return view('finance.fee_structures.show', compact('feeStructure'));
    }

    public function destroy(FeeStructure $feeStructure)
    {
        $feeStructure->delete();
        return redirect()->route('fee-structures.index')->with('success', 'تم حذف هيكل الرسوم');
    }
}
