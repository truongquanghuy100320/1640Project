<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommentModel extends Model
{
    use HasFactory;
    protected $primaryKey = 'comment_id'; // Khai báo khóa chính của bảng
    protected $table = 'tbl_comments';
    protected $fillable = [ // Khai báo các trường có thể được gán dữ liệu từ Mass Assignment
        'comment',
        'image_url',
        'status',
        'user_id',
        'student_id',
        'staff_id',
        'contribution_id',
    ];

    protected $dates = ['created_at', 'updated_at']; // Khai báo các trường ngày tháng

    // Định nghĩa mối quan hệ với User
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function staff()
    {
        return $this->belongsTo(User::class, 'staff_id');
    }
    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    // Định nghĩa mối quan hệ với Contribution
    public function contribution()
    {
        return $this->belongsTo(ContributionModel::class, 'contribution_id');
    }
}
