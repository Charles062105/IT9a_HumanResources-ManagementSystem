<?php
// ── app/Models/Employee.php ──────────────────────────────
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;

class Employee extends Model {
    use HasFactory;
    protected $fillable = [
        'employee_id','user_id','first_name','last_name','email',
        'phone','address','department','position',
        'date_hired','date_of_birth','contract_expiry',
        'status','sss_number','pagibig_number','philhealth_number',
    ];
    protected $casts = ['date_hired'=>'date','date_of_birth'=>'date','contract_expiry'=>'date'];

    public function user()         { return $this->belongsTo(User::class); }
    public function attendances()  { return $this->hasMany(Attendance::class); }
    public function leaves()       { return $this->hasMany(Leave::class); }
    public function violations()   { return $this->hasMany(Violation::class); }
    public function performances() { return $this->hasMany(Performance::class); }
    public function timesheets()   { return $this->hasMany(Timesheet::class); }

    public function getFullNameAttribute() { return "$this->first_name $this->last_name"; }
    public function getInitialsAttribute()  { return strtoupper(substr($this->first_name,0,1).substr($this->last_name,0,1)); }
    public function getYearsOfServiceAttribute() { return Carbon::parse($this->date_hired)->diffInYears(now()); }
}
