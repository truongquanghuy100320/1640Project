<?php

namespace App\Http\Controllers;

use App\Models\RoleModel;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function list_role()
    {
        $list_role = RoleModel::all();
        return view('roles.list-role',['list_role'=>$list_role]);
    }

}
