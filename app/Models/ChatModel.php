<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatModel extends Model
{
    use HasFactory;
    protected $primaryKey = 'message_id '; // Khai báo khóa chính của bảng
    protected $table = 'chatmessages';
    protected $fillable = [ // Khai báo các trường có thể được gán dữ liệu từ Mass Assignment
        'content',
        'STATUS',
        'student_id',
        'staff_id',
        'faculty_id',
        'sender_type'
    ];

    protected $dates = ['created_at']; // Khai báo các trường ngày tháng

    // Định nghĩa mối quan hệ với User

    public function user()
    {
        return $this->belongsTo(User::class, 'faculty_id');
    }
    public function staff()
    {
        return $this->belongsTo(User::class, 'staff_id');
    }
    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public static function find($id)
    {
        // Tiếp tục thực hiện tìm kiếm theo id
        return static::where('message_id', $id)->first();
    }
}
