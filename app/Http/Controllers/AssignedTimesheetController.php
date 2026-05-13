<?php

namespace App\Http\Controllers;

use App\Models\AssignedTimesheet;
use App\Models\Employee;
use App\Models\HrmsNotification;
use Illuminate\Http\Request;

class AssignedTimesheetController extends Controller
{
    public function index(Request $request)
    {
        $query = AssignedTimesheet::with('employee')->latest();

        if ($s = $request->search) {
            $query->where('title', 'like', "%$s%");
        }
        if ($e = $request->employee_id) {
            $query->where('employee_id', $e);
        }
        if ($st = $request->status) {
            $query->where('status', $st);
        }

        $records = $query->paginate(20)->appends($request->all());
        $employees = Employee::where('status', 'active')->orderBy('first_name')->get();

        return view('assigned-timesheets.index', compact('records', 'employees'));
    }

    public function create()
    {
        $employees = Employee::where('status', 'active')->orderBy('first_name')->get();

        return view('assigned-timesheets.create', compact('employees'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'expected_hours' => 'nullable|numeric|min:0|max:200',
            'due_date' => 'required|date',
            'admin_notes' => 'nullable|string',
        ]);

        $data['status'] = 'pending';
        $record = AssignedTimesheet::create($data);

        HrmsNotification::create([
            'title' => 'New Task Assigned',
            'message' => "You have been assigned a new task: \"{$record->title}\" (due {$record->due_date->format('M j, Y')}).",
            'type' => 'info',
            'user_id' => optional($record->employee)->user_id,
        ]);

        return redirect()->route('assigned-timesheets.index')
            ->with('success', 'Task assigned to employee.');
    }

    public function show(AssignedTimesheet $assignedTimesheet)
    {
        $assignedTimesheet->load(['employee', 'timesheets']);

        return view('assigned-timesheets.show', compact('assignedTimesheet'));
    }

    public function edit(AssignedTimesheet $assignedTimesheet)
    {
        $employees = Employee::where('status', 'active')->orderBy('first_name')->get();

        return view('assigned-timesheets.edit', compact('assignedTimesheet', 'employees'));
    }

    public function update(Request $request, AssignedTimesheet $assignedTimesheet)
    {
        $data = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'expected_hours' => 'nullable|numeric|min:0|max:200',
            'due_date' => 'required|date',
            'status' => 'nullable|in:pending,in_progress,submitted,approved,rejected',
            'admin_notes' => 'nullable|string',
        ]);

        $assignedTimesheet->update($data);

        return redirect()->route('assigned-timesheets.show', $assignedTimesheet)
            ->with('success', 'Task updated.');
    }

    public function destroy(AssignedTimesheet $assignedTimesheet)
    {
        $assignedTimesheet->delete();

        return redirect()->route('assigned-timesheets.index')
            ->with('success', 'Assigned task deleted.');
    }
}
