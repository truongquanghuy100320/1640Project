<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class UserModel extends Model implements Authenticatable
{
    use HasFactory;
    protected $primaryKey = 'user_id'; // Khai báo khóa chính của bảng
    protected $table = 'tbl_users';


    protected $fillable = [ // Khai báo các trường có thể được gán dữ liệu từ Mass Assignment
        'email',
        'password',
        'status',
        'user_type',
        'role_id',
        'images',
        'phone_number',
    ];

    protected $dates = ['created_at', 'updated_at']; // Khai báo các trường ngày tháng
    public $timestamps = true;
    // Định nghĩa mối quan hệ giữa User và Role
    public function role()
    {
        return $this->belongsTo(RoleModel::class, 'role_id');
    }
    public function getAuthIdentifierName()
    {
        return 'user_id'; // Tên cột chứa khóa chính
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
        return static::where('user_id', $id)->first();
    }


}
