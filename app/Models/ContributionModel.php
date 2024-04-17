<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContributionModel extends Model
{
    use HasFactory;
    protected $primaryKey = 'contribution_id';
    protected $table = 'tbl_contributions';


    protected $fillable = [
        'content',
        'word_document',
        'title',
        'image_url',
        'status',
        'staff_id',
        'student_id',
        'faculty_id',
        'academic_years_id',
        'downloaded',
        'expiration_date',
        'download_date',
        'start_day',
        'MSV',
        'condition_checkbox',
    ];

    protected $dates = ['created_at', 'updated_at'];

    // Định nghĩa mối quan hệ với User
    public function staff()
    {
        return $this->belongsTo(StaffModel::class, 'staff_id');
    }
    public function student()
    {
        return $this->belongsTo(StudentModel::class, 'student_id');
    }

    // Định nghĩa mối quan hệ với Faculty
    public function faculty()
    {
        return $this->belongsTo(FacltiesModel::class, 'faculty_id');
    }
    public function AcademicYear()
    {
        return $this->belongsTo(AcademicYearModel::class, 'academic_years_id');
    }
}
