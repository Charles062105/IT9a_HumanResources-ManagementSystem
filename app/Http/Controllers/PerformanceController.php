<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Performance;
use Illuminate\Http\Request;

class PerformanceController extends Controller
{
    public function index(Request $request)
    {
        $query = Performance::with('employee')->latest();

        // Employees can only see their own performance reviews
        if (auth()->user()->isEmployee()) {
            $employee = auth()->user()->employee;
            if ($employee) {
                $query->where('employee_id', $employee->id);
            } else {
                $query->whereRaw('1=0'); // No results if no employee record
            }
        }

        if ($s = $request->search) {
            $query->whereHas('employee', fn ($q) => $q->where('first_name', 'like', "%$s%")->orWhere('last_name', 'like', "%$s%"));
        }
        if ($dp = $request->department) {
            $query->whereHas('employee', fn ($q) => $q->where('department', $dp));
        }
        if ($p = $request->period) {
            $query->where('period', $p);
        }
        if ($r = $request->rating) {
            $query->where('rating', $r);
        }

        $records = $query->paginate(20)->appends($request->all());
        $departments = Employee::distinct()->pluck('department')->sort();
        $periods = Performance::distinct()->pluck('period')->sort()->reverse();

        return view('performance.index', compact('records', 'departments', 'periods'));
    }

    public function my()
    {
        $employee = auth()->user()->employee;
        $records = $employee
            ? Performance::where('employee_id', $employee->id)->latest()->paginate(10)
            : collect();

        return view('performance.my', compact('records'));
    }

    public function create()
    {
        // Only admins can create performance reviews
        if (! auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized: Only admins can create performance reviews.');
        }

        $employees = Employee::where('status', 'active')->orderBy('first_name')->get();

        return view('performance.create', compact('employees'));
    }

    public function edit(Performance $performance)
    {
        if (! auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized: Only admins can edit performance reviews.');
        }

        $employees = Employee::where('status', 'active')->orderBy('first_name')->get();

        return view('performance.edit', compact('performance', 'employees'));
    }

    public function update(Request $request, Performance $performance)
    {
        if (! auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized: Only admins can update performance reviews.');
        }

        $data = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'period' => 'required|string|max:50',
            'score' => 'required|numeric|min:0|max:10',
            'feedback' => 'nullable|string',
        ]);

        $score = (float) $data['score'];
        $data['rating'] = match (true) {
            $score >= 9.0 => 'Outstanding',
            $score >= 7.0 => 'Satisfactory',
            $score >= 5.0 => 'Needs Improvement',
            default => 'Poor',
        };

        $performance->update($data);

        return redirect()->route('performance.index')
            ->with('success', 'Performance review updated.');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'period' => 'required|string|max:50',
            'score' => 'required|numeric|min:0|max:10',
            'feedback' => 'nullable|string',
        ]);

        $score = (float) $data['score'];
        $data['rating'] = match (true) {
            $score >= 9.0 => 'Outstanding',
            $score >= 7.0 => 'Satisfactory',
            $score >= 5.0 => 'Needs Improvement',
            default => 'Poor',
        };
        $data['reviewed_by'] = auth()->id();

        Performance::create($data);

        return redirect()->route('performance.index')
            ->with('success', 'Performance review saved.');
    }

    public function show(Performance $performance)
    {
        if (auth()->user()->isEmployee()) {
            $employee = auth()->user()->employee;
            if (! $employee || $employee->id !== $performance->employee_id) {
                abort(403, 'Unauthorized.');
            }
        }

        return view('performance.show', compact('performance'));
    }

    public function destroy(Performance $performance)
    {
        if (! auth()->user()->isAdmin()) {
            abort(403, 'Only admins can delete performance reviews.');
        }

        $performance->delete();

        return back()->with('success', 'Record deleted.');
    }
}
