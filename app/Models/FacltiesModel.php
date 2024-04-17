<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FacltiesModel extends Model
{
    protected $table = 'tbl_faculties';
    protected $primaryKey = 'faculty_id';

    // Các trường có thể gán dữ liệu
    protected $fillable = [
        'faculty_name',
        'status',
        'MSFaculties',

    ];

    // Các trường ngày tháng
    protected $dates = ['created_at', 'updated_at'];

}
