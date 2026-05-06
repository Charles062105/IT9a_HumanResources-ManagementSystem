<?php

namespace App\Http\Controllers;

use App\Models\Timesheet;
use App\Models\Employee;
use Illuminate\Http\Request;
use Carbon\Carbon;

class TimesheetController extends Controller
{
    public function index(Request $request)
    {
        $query = Timesheet::with('employee')->latest();

        if ($s  = $request->search)
            $query->whereHas('employee', fn($q) => $q->where('first_name', 'like', "%$s%")->orWhere('last_name', 'like', "%$s%"));
        if ($dp = $request->department) $query->whereHas('employee', fn($q) => $q->where('department', $dp));
        if ($w  = $request->week)       $query->where('week_label', $w);
        if ($st = $request->status)     $query->where('status', $st);

        $records     = $query->paginate(20)->appends($request->all());
        $departments = Employee::distinct()->pluck('department')->sort();
        $weeks       = Timesheet::distinct()->pluck('week_label')->sort()->reverse();

        return view('timesheets.index', compact('records', 'departments', 'weeks'));
    }

    public function my()
    {
        $employee = auth()->user()->employee;
        $records  = $employee
            ? Timesheet::where('employee_id', $employee->id)->latest()->paginate(10)
            : collect();

        return view('timesheets.my', compact('records'));
    }

    public function create()
    {
        $isAdmin = auth()->user()->isAdmin();
        $employees = $isAdmin ? Employee::where('status', 'active')->orderBy('first_name')->get() : null;
        return view('timesheets.create', compact('isAdmin', 'employees'));
    }

    public function store(Request $request)
    {
        $isAdmin = auth()->user()->isAdmin();
        
        $validationRules = [
            'week_start'  => 'required|date',
            'week_end'    => 'required|date|after_or_equal:week_start',
            'total_hours' => 'required|numeric|min:0|max:120',
            'ot_hours'    => 'nullable|numeric|min:0',
            'notes'       => 'nullable|string',
        ];
        
        if ($isAdmin) {
            $validationRules['employee_id'] = 'required|exists:employees,id';
        }

        $data = $request->validate($validationRules);

        if (!$isAdmin) {
            $employee = auth()->user()->employee;
            if (!$employee) return back()->with('error', 'No employee record linked to your account.');
            $data['employee_id'] = $employee->id;
        }

        $data['week_label']   = Carbon::parse($data['week_start'])->format('M d')
            . '–' . Carbon::parse($data['week_end'])->format('M d');
        $data['status']       = 'pending';
        $data['submitted_at'] = now();

        Timesheet::create($data);

        return redirect()->route('timesheets.index')
            ->with('success', 'Timesheet submitted.');
    }

    public function approve(Timesheet $timesheet)
    {
        $timesheet->update(['status' => 'approved', 'approved_by' => auth()->id()]);
        return back()->with('success', 'Timesheet approved.');
    }

    public function reject(Timesheet $timesheet)
    {
        $timesheet->update(['status' => 'rejected', 'approved_by' => auth()->id()]);
        return back()->with('success', 'Timesheet rejected.');
    }

    public function destroy(Timesheet $timesheet)
    {
        $timesheet->delete();
        return back()->with('success', 'Timesheet deleted.');
    }
}
