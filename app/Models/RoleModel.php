<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoleModel extends Model
{
    use HasFactory;
    protected $table = 'tbl_role';

    protected $primaryKey = 'role_id';

    public $timestamps = true;

    protected $fillable = ['role_name', 'status'];
}
