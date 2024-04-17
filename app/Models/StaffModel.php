<?php

namespace App\Models;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StaffModel extends Model implements Authenticatable
{
    use HasFactory;
    protected $primaryKey = 'staff_id';
    protected $table = 'tbl_staff';
    protected $fillable = [
        'staffname',
        'email',
        'password',
        'phone',
        'images',
        'status',
        'faculty_id',
        'academic_years_id',
        'role_id ',
    ];

    protected $dates = ['created_at', 'updated_at'];

    // Định nghĩa mối quan hệ với UserModels
    public function staff()
    {
        return $this->belongsTo(UserModel::class, 'staff_id');
    }
    public function role()
    {
        return $this->belongsTo(RoleModel::class, 'role_id');
    }
    public function faculty()
    {
        return $this->belongsTo(FacltiesModel::class, 'faculty_id');
    }
    public function AcademicYear()
    {
        return $this->belongsTo(AcademicYearModel::class, 'academic_years_id');
    }
    public function getAuthIdentifierName()
    {
        return 'staff_id'; // Tên cột chứa khóa chính
    }

    public function getAuthIdentifier()
    {
        return $this->getKey(); // Trả về giá trị của khóa chính
    }

    public function getAuthPassword()
    {
        return $this->password; // Trả về mật khẩu đã được mã hóa
    }


    public function getRememberToken()
    {
        // TODO: Implement getRememberToken() method.
    }

    public function setRememberToken($value)
    {
        // TODO: Implement setRememberToken() method.
    }

    public function getRememberTokenName()
    {
        // TODO: Implement getRememberTokenName() method.
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
        return static::where('staff_id', $id)->first();
    }
}