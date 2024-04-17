<?php

namespace App\Models;

use App\Models\FacltiesModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class StudentModel extends Model
{
    use HasFactory;
    protected $primaryKey = 'student_id';
    protected $table = 'tbl_students';

    protected $fillable = [
        'MSV',
        'studentname',
        'email_login',
        'password',
        'images',
        'studentphone',
        'status',
        'faculty_id',
        'academic_years_id',
        'role_id',
    ];

    protected $dates = ['created_at', 'updated_at'];
    public $timestamps = true;
    // Định nghĩa mối quan hệ với Faculty
    public function faculty()
    {
        return $this->belongsTo(FacltiesModel::class, 'faculty_id');
    }
    public function AcademicYear()
    {
        return $this->belongsTo(AcademicYearModel::class, 'academic_years_id');
    }
    public function role()
    {
        return $this->belongsTo(RoleModel::class, 'role_id');
    }

    public function isAdmin()
    {
        // Kiểm tra xem role_id của người dùng có bằng 1 hay không
        return $this->role_id === 1;
    }
    public static function find($id)
    {
        // Lấy người dùng đang đăng nhập
        $user = auth()->user();

        // Kiểm tra xem người dùng có phải là admin không
        if (!$user || !$user->isAdmin()) {
            // Nếu không phải admin, ném một ngoại lệ hoặc chuyển hướng người dùng về trang login
            // Ví dụ: ném một ngoại lệ
            throw new \Exception('You do not have permission to access this resource.');
        }

        // Tiếp tục thực hiện tìm kiếm theo id
        return static::where('student_id', $id)->first();
    }

}