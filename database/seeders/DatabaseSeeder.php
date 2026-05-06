<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Employee;
use App\Models\Attendance;
use App\Models\Leave;
use App\Models\Violation;
use App\Models\Performance;
use App\Models\Timesheet;
use App\Models\HrmsNotification;
use App\Models\UserRequest;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        // Use updateOrCreate to avoid duplicate unique-email seeding issues.
        $admin = User::updateOrCreate(
            ['email' => 'admin@hrms.ph'],
            [
                'name'     => 'Maria Admin',
                'password' => Hash::make('password'),
                'role'     => 'admin',
                'status'   => 'active',
            ]
        );


        $departments = ['Engineering','Finance','HR','Operations','Sales','IT','Legal','Marketing'];
        $positions   = ['Manager','Supervisor','Staff','Director','Analyst','Coordinator'];

        $empData = [
            ['Juan',    'dela Cruz', 'Engineering', 'Manager',    '2020-01-15', '1990-03-22'],
            ['Ana',     'Santos',    'Finance',     'Staff',      '2021-03-03', '1993-07-14'],
            ['Carlo',   'Reyes',     'Operations',  'Supervisor', '2024-02-01', '1995-11-05'],
            ['Maria',   'Lim',       'HR',          'Manager',    '2019-06-10', '1988-09-30'],
            ['Jose',    'Mendoza',   'Sales',       'Staff',      '2023-09-20', '1997-04-18'],
            ['Rosa',    'Garcia',    'IT',          'Director',   '2018-04-05', '1985-12-01'],
            ['Pedro',   'Bautista',  'Sales',       'Staff',      '2022-08-12', '1996-06-25'],
            ['Rico',    'Torres',    'Operations',  'Staff',      '2023-01-10', '1998-02-14'],
            ['Lena',    'Cruz',      'Finance',     'Analyst',    '2022-05-20', '1994-08-07'],
            ['Mark',    'Villanueva','Engineering', 'Staff',      '2021-11-15', '1992-01-19'],
        ];

        foreach ($empData as $i => [$fn, $ln, $dept, $pos, $hired, $dob]) {
            $email = strtolower("{$fn}.{$ln}@hrms.ph");

            $user = User::updateOrCreate(
                ['email' => $email],
                [
                    'name'     => "$fn $ln",
                    'password' => Hash::make('password'),
                    'role'     => 'employee',
                    'status'   => 'active',
                ]
            );

            $status = match($i) { 2 => 'probationary', 4 => 'contractual', default => 'active' };

            $employeeId = 'EMP-' . str_pad($i + 1, 4, '0', STR_PAD_LEFT);

            Employee::updateOrCreate(
                ['employee_id' => $employeeId],
                [
                    'user_id'          => $user->id,
                    'first_name'       => $fn,
                    'last_name'        => $ln,
                    'email'            => strtolower("{$fn}.{$ln}@hrms.ph"),
                    'department'       => $dept,
                    'position'         => $pos,
                    'date_hired'       => $hired,
                    'date_of_birth'    => $dob,
                    'status'           => $status,
                    'contract_expiry'  => $status === 'contractual' ? Carbon::parse($hired)->addYear() : null,
                    'sss_number'       => '33-' . rand(1000000, 9999999) . '-0',
                    'pagibig_number'   => rand(100000000000, 999999999999),
                    'philhealth_number'=> rand(10000000000, 99999999999),
                ]
            );

        }

        $employees = Employee::all();

        // Attendance — 7 days
        foreach ($employees as $emp) {
            for ($d = 6; $d >= 0; $d--) {
                $date = Carbon::today()->subDays($d);
                if ($date->isWeekend()) continue;

                $rand   = rand(1, 10);
                $status = match(true) {
                    $rand <= 7  => 'present',
                    $rand === 8 => 'late',
                    default     => 'absent',
                };

                Attendance::create([
                    'employee_id' => $emp->id,
                    'date'        => $date,
                    'time_in'     => $status !== 'absent' ? $date->copy()->setTime($status === 'late' ? 8 : 7, rand(55, 59)) : null,
                    'time_out'    => $status !== 'absent' ? $date->copy()->setTime(17, rand(0, 30)) : null,
                    'status'      => $status,
                ]);
            }
        }

        // Leaves
        Leave::create(['employee_id'=>7,'type'=>'vacation','start_date'=>Carbon::today()->addDays(2),'end_date'=>Carbon::today()->addDays(4),'days'=>3,'reason'=>'Family trip','status'=>'pending']);
        Leave::create(['employee_id'=>9,'type'=>'sick',    'start_date'=>Carbon::today()->addDay(), 'end_date'=>Carbon::today()->addDay(), 'days'=>1,'reason'=>'Medical appointment','status'=>'pending']);
        Leave::create(['employee_id'=>8,'type'=>'emergency','start_date'=>Carbon::today(),'end_date'=>Carbon::today()->addDay(),'days'=>2,'reason'=>'Family emergency','status'=>'pending']);
        Leave::create(['employee_id'=>5,'type'=>'vacation','start_date'=>Carbon::today()->subDays(10),'end_date'=>Carbon::today()->subDays(6),'days'=>5,'reason'=>'Annual leave','status'=>'approved','approved_by'=>$admin->id,'approved_at'=>now()]);

        // Violations
        Violation::create(['employee_id'=>3,'level'=>'Written Warning','offense'=>'Habitual tardiness','date'=>Carbon::today(),'offense_count'=>2,'status'=>'open','issued_by'=>$admin->id]);
        Violation::create(['employee_id'=>9,'level'=>'Verbal Warning', 'offense'=>'Unauthorized absence','date'=>Carbon::today()->subDays(2),'offense_count'=>1,'status'=>'open','issued_by'=>$admin->id]);
        Violation::create(['employee_id'=>10,'level'=>'Suspension',    'offense'=>'Insubordination','date'=>Carbon::today()->subDays(4),'offense_count'=>4,'status'=>'open','issued_by'=>$admin->id]);

        // Performance
        $perfData = [
            [1, 9.6, 'Outstanding'], [4, 9.3, 'Outstanding'], [2, 8.8, 'Outstanding'],
            [5, 7.2, 'Satisfactory'],[3, 5.4, 'Needs Improvement'],
        ];
        foreach ($perfData as [$eid, $score, $rating]) {
            Performance::create([
                'employee_id' => $eid, 'period' => 'Q3 2025',
                'score' => $score, 'rating' => $rating,
                'feedback' => "Performance review for Q3 2025.",
                'reviewed_by' => $admin->id,
            ]);
        }

        // Timesheets
        foreach ($employees->take(5) as $emp) {
            Timesheet::create([
                'employee_id'  => $emp->id,
                'week_start'   => Carbon::now()->startOfWeek(),
                'week_end'     => Carbon::now()->endOfWeek(),
                'week_label'   => Carbon::now()->startOfWeek()->format('M d') . '–' . Carbon::now()->endOfWeek()->format('M d'),
                'total_hours'  => rand(38, 46),
                'ot_hours'     => rand(0, 6),
                'status'       => 'pending',
                'submitted_at' => now(),
            ]);
        }

        // Notifications
        HrmsNotification::insert([
            ['title'=>'Leave request from Pedro Bautista','message'=>'Vacation leave Jul 16–18 awaiting approval.','type'=>'info','is_read'=>false,'user_id'=>$admin->id,'created_at'=>now(),'updated_at'=>now()],
            ['title'=>'New violation — Carlo Reyes','message'=>'Written Warning: Habitual tardiness, 2nd offense.','type'=>'danger','is_read'=>false,'user_id'=>$admin->id,'created_at'=>now()->subHour(),'updated_at'=>now()],
            ['title'=>'Account approval pending — Bea Ramos','message'=>'New user registration awaiting admin approval.','type'=>'warning','is_read'=>false,'user_id'=>$admin->id,'created_at'=>now()->subHours(3),'updated_at'=>now()],
            ['title'=>'Leave approved — Jose Mendoza','message'=>'Vacation Jul 7–11 has been approved.','type'=>'success','is_read'=>true,'user_id'=>$employees[4]->user_id,'created_at'=>now()->subDay(),'updated_at'=>now()],
            ['title'=>"Work anniversary — Juan dela Cruz",'message'=>'Celebrating 5 years today!','type'=>'success','is_read'=>true,'user_id'=>$employees[0]->user_id,'created_at'=>now()->subHours(8),'updated_at'=>now()],
        ]);

        // Pending user requests
        $pendingUser = User::create(['name'=>'Bea Ramos','email'=>'bea.ramos@hrms.ph','password'=>Hash::make('password'),'role'=>'employee','status'=>'pending']);
        UserRequest::create(['user_id'=>$pendingUser->id,'type'=>'Account Activation','details'=>'New employee registration','status'=>'pending']);
        UserRequest::create(['user_id'=>$employees[1]->user_id,'type'=>'Role Change','details'=>'Staff → Supervisor','status'=>'pending']);
    }
}
