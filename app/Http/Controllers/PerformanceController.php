<?php

namespace App\Http\Controllers;

use App\Models\Performance;
use App\Models\Employee;
use Illuminate\Http\Request;

class PerformanceController extends Controller
{
    public function index(Request $request)
    {
        $query = Performance::with('employee')->latest();

        if ($s  = $request->search)
            $query->whereHas('employee', fn($q) => $q->where('first_name', 'like', "%$s%")->orWhere('last_name', 'like', "%$s%"));
        if ($dp = $request->department) $query->whereHas('employee', fn($q) => $q->where('department', $dp));
        if ($p  = $request->period)     $query->where('period', $p);
        if ($r  = $request->rating)     $query->where('rating', $r);

        $records     = $query->paginate(20)->appends($request->all());
        $departments = Employee::distinct()->pluck('department')->sort();
        $periods     = Performance::distinct()->pluck('period')->sort()->reverse();

        return view('performance.index', compact('records', 'departments', 'periods'));
    }

    public function my()
    {
        $employee = auth()->user()->employee;
        $records  = $employee
            ? Performance::where('employee_id', $employee->id)->latest()->paginate(10)
            : collect();

        return view('performance.my', compact('records'));
    }

    public function create()
    {
        $employees = Employee::where('status', 'active')->orderBy('first_name')->get();
        return view('performance.create', compact('employees'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'period'      => 'required|string|max:50',
            'score'       => 'required|numeric|min:0|max:10',
            'feedback'    => 'nullable|string',
        ]);

        $score          = (float) $data['score'];
        $data['rating'] = match(true) {
            $score >= 9.0 => 'Outstanding',
            $score >= 7.0 => 'Satisfactory',
            $score >= 5.0 => 'Needs Improvement',
            default       => 'Poor',
        };
        $data['reviewed_by'] = auth()->id();

        Performance::create($data);

        return redirect()->route('performance.index')
            ->with('success', 'Performance review saved.');
    }

    public function show(Performance $performance)
    {
        return view('performance.show', compact('performance'));
    }

    public function destroy(Performance $performance)
    {
        $performance->delete();
        return back()->with('success', 'Record deleted.');
    }
}
