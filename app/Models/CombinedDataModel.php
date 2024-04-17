<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CombinedDataModel extends Model
{
    use HasFactory;
    protected $table = 'tbl_combined_data';
    protected $primaryKey = 'combined_data_id';
    public $timestamps = false; // Nếu không sử dụng các cột created_at và updated_at

    protected $fillable = [
        'comment_id',
        'contribution_id',
        'staff_id',
        'faculty_id',
        'student_id',
        'academic_year_id',
        'status',
    ];
    public function faculty()
    {
        return $this->belongsTo(FacltiesModel::class, 'faculty_id');
    }
    public function staff()
    {
        return $this->belongsTo(StaffModel::class, 'staff_id');
    }
    public function student()
    {
        return $this->belongsTo(StudentModel::class, 'student_id');
    }
    public function contribution()
    {
        return $this->belongsTo(ContributionModel::class, 'contribution_id');
    }
    public function comment()
    {
        return $this->belongsTo(ContributionModel::class, 'comment_id');
    }
    public function academic_year()
    {
        return $this->belongsTo(AcademicYearModel::class, 'academic_years_id');
    }
}
