<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcademicYearModel extends Model
{
    use HasFactory;
    protected $table = 'tbl_academic_years';
    protected $primaryKey = 'academic_years_id';
    public $timestamps = true; // Nếu không sử dụng các cột created_at và updated_at

    protected $fillable = [
        'academic_year',
        'bridge_opening_day',
        'status',
        'closing_date',
    ];
}