<?php

namespace App\Http\Controllers;

use App\Models\Violation;
use App\Models\Employee;
use Illuminate\Http\Request;

class ViolationController extends Controller
{
    public function index(Request $request)
    {
        $query = Violation::with('employee')->latest();

        if ($s  = $request->search)
            $query->whereHas('employee', fn($q) => $q->where('first_name', 'like', "%$s%")->orWhere('last_name', 'like', "%$s%"));
        if ($l  = $request->level)      $query->where('level', $l);
        if ($dp = $request->department) $query->whereHas('employee', fn($q) => $q->where('department', $dp));
        if ($st = $request->status)     $query->where('status', $st);

        $violations  = $query->paginate(20)->appends($request->all());
        $departments = Employee::distinct()->pluck('department')->sort();

        return view('violations.index', compact('violations', 'departments'));
    }

    public function my()
    {
        $employee   = auth()->user()->employee;
        $violations = $employee
            ? Violation::where('employee_id', $employee->id)->latest()->paginate(15)
            : collect();

        return view('violations.my', compact('violations'));
    }

    public function create()
    {
        $employees = Employee::where('status', 'active')->orderBy('first_name')->get();
        return view('violations.create', compact('employees'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'level'       => 'required|string',
            'offense'     => 'required|string|max:255',
            'description' => 'nullable|string',
            'date'        => 'required|date',
        ]);

        $data['status']        = 'open';
        $data['issued_by']     = auth()->id();
        $data['offense_count'] = Violation::where('employee_id', $data['employee_id'])->count() + 1;

        Violation::create($data);

        return redirect()->route('violations.index')
            ->with('success', 'Violation recorded.');
    }

    public function show(Violation $violation)
    {
        return view('violations.show', compact('violation'));
    }

    public function resolve(Violation $violation)
    {
        $violation->update(['status' => 'resolved']);
        return back()->with('success', 'Violation marked as resolved.');
    }

    public function destroy(Violation $violation)
    {
        $violation->delete();
        return back()->with('success', 'Violation deleted.');
    }
}
