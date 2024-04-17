<?php

namespace App\Http\Controllers;

use App\Models\FacltiesModel;
use App\Models\StaffModel;
use Illuminate\Database\Console\Migrations\ResetCommand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;

class FacltiesController extends Controller
{

    public function list_faculty()
    {
        $staff_id = session('staff_id');

        // Nếu không tìm thấy staff_id trong session, chuyển hướng đến trang đăng nhập
        if (!$staff_id) {
            return redirect()->route('login.login')->with('error', 'You login not yet.');
        }

        // Lấy thông tin nhân viên dựa trên staff_id
        $staff = StaffModel::where('staff_id', $staff_id)->first();

        // Nếu không tìm thấy thông tin nhân viên, trả về thông báo lỗi
        if (!$staff) {
            return redirect()->route('login.login')->with('error', 'Not found information of staff');
        }

        if ($staff->role_id != 1 && $staff->role_id != 2){
            return redirect()->route('login.login')->with('error', 'You can not access this function!');
        }
        $fac = FacltiesModel::all();
        return view('facultiess.list-faculty',['list_faculty'=>$fac]);



    }


    //add new faculty
    public function add_faculty()
    {
        $staff_id = session('staff_id');

        // Nếu không tìm thấy staff_id trong session, chuyển hướng đến trang đăng nhập
        if (!$staff_id) {
            return redirect()->route('login.login')->with('error', 'You login not yet.');
        }

        // Lấy thông tin nhân viên dựa trên staff_id
        $staff = StaffModel::where('staff_id', $staff_id)->first();

        // Nếu không tìm thấy thông tin nhân viên, trả về thông báo lỗi
        if (!$staff) {
            return redirect()->route('login.login')->with('error', 'Not found information of staff');
        }

        if ($staff->role_id != 1 && $staff->role_id != 2){
            return redirect()->route('login.login')->with('error', 'You can not access this function!');
        }
        return view('facultiess.add-faculty-form');
    }
    public function save_faculty(Request $request)
    {
        $staff_id = session('staff_id');

        // Nếu không tìm thấy staff_id trong session, chuyển hướng đến trang đăng nhập
        if (!$staff_id) {
            return redirect()->route('login.login')->with('error', 'You login not yet.');
        }

        // Lấy thông tin nhân viên dựa trên staff_id
        $staff = StaffModel::where('staff_id', $staff_id)->first();

        // Nếu không tìm thấy thông tin nhân viên, trả về thông báo lỗi
        if (!$staff) {
            return redirect()->route('login.login')->with('error', 'Not found information of staff');
        }

        if ($staff->role_id != 1 && $staff->role_id != 2){
            return redirect()->route('login.login')->with('error', 'You can not access this function!');
        }

        $data = array();
        $data['MSFaculties'] = $request->faculty_code;
        $data['faculty_name'] = $request->faculty_name;
        $data['status'] = $request->status;
        DB::table('tbl_faculties')->insert($data);
        Session::put('message' . 'add new faculty successful!');
        return Redirect::to('list-faculty');
    }



    //edit faculty
    public function edit($faculty_id)
    {
        $staff_id = session('staff_id');

        // Nếu không tìm thấy staff_id trong session, chuyển hướng đến trang đăng nhập
        if (!$staff_id) {
            return redirect()->route('login.login')->with('error', 'You login not yet.');
        }

        // Lấy thông tin nhân viên dựa trên staff_id
        $staff = StaffModel::where('staff_id', $staff_id)->first();

        // Nếu không tìm thấy thông tin nhân viên, trả về thông báo lỗi
        if (!$staff) {
            return redirect()->route('login.login')->with('error', 'Not found information of staff');
        }

        if ($staff->role_id != 1 && $staff->role_id != 2){
            return redirect()->route('login.login')->with('error', 'You can not access this function!');
        }
        $edit_faculties = DB::table('tbl_faculties')->where('faculty_id', $faculty_id)->get();
        $manager_faculties = view('facultiess.edit-faculty')->with('edit', $edit_faculties);
        return view('layout')->with('facultiess.edit-faculty', $manager_faculties);
    }
    public function update_faculty(Request $request, $faculty_id)
    {$staff_id = session('staff_id');

        // Nếu không tìm thấy staff_id trong session, chuyển hướng đến trang đăng nhập
        if (!$staff_id) {
            return redirect()->route('login.login')->with('error', 'You login not yet.');
        }

        // Lấy thông tin nhân viên dựa trên staff_id
        $staff = StaffModel::where('staff_id', $staff_id)->first();

        // Nếu không tìm thấy thông tin nhân viên, trả về thông báo lỗi
        if (!$staff) {
            return redirect()->route('login.login')->with('error', 'Not found information of staff');
        }

        if ($staff->role_id != 1 && $staff->role_id != 2){
            return redirect()->route('login.login')->with('error', 'You can not access this function!');
        }

        $data = array();
        $data['MSFaculties'] = $request->faculty_ms;
        $data['faculty_name'] = $request->faculty_name;
        DB::table('tbl_faculties')->where('faculty_id', $faculty_id)->update($data);
        Session::put('message', 'update faculty successful!');
        return Redirect::to('list-faculty');
    }

    public function cancel_edit_faculty()
    {
        $staff_id = session('staff_id');

        // Nếu không tìm thấy staff_id trong session, chuyển hướng đến trang đăng nhập
        if (!$staff_id) {
            return redirect()->route('login.login')->with('error', 'You login not yet.');
        }

        // Lấy thông tin nhân viên dựa trên staff_id
        $staff = StaffModel::where('staff_id', $staff_id)->first();

        // Nếu không tìm thấy thông tin nhân viên, trả về thông báo lỗi
        if (!$staff) {
            return redirect()->route('login.login')->with('error', 'Not found information of staff');
        }

        if ($staff->role_id != 1 || $staff->role_id != 2){
            return redirect()->route('login.login')->with('error', 'You can not access this function!');
        }

        Session::put('message_cancel_f','Canceled   !');
        return Redirect::to('list-faculty');
    }

    public function delete_faculties($faculty_id)
    {
        $staff_id = session('staff_id');

        // Nếu không tìm thấy staff_id trong session, chuyển hướng đến trang đăng nhập
        if (!$staff_id) {
            return redirect()->route('login.login')->with('error', 'You login not yet.');
        }

        // Lấy thông tin nhân viên dựa trên staff_id
        $staff = StaffModel::where('staff_id', $staff_id)->first();

        // Nếu không tìm thấy thông tin nhân viên, trả về thông báo lỗi
        if (!$staff) {
            return redirect()->route('login.login')->with('error', 'Not found information of staff');
        }

        if ($staff->role_id != 1 && $staff->role_id != 2){
            return redirect()->route('login.login')->with('error', 'You can not access this function!');
        }

        DB::table('tbl_faculties')->where('faculty_id', $faculty_id)->delete();
        Session::put('message', 'Delete faculty successful!');
        return Redirect::to('list-faculty');
    }
    public function edit_status_show_faclties($faculty_id){
        $staff_id = session('staff_id');

        // Nếu không tìm thấy staff_id trong session, chuyển hướng đến trang đăng nhập
        if (!$staff_id) {
            return redirect()->route('login.login')->with('error', 'You login not yet.');
        }

        // Lấy thông tin nhân viên dựa trên staff_id
        $staff = StaffModel::where('staff_id', $staff_id)->first();

        // Nếu không tìm thấy thông tin nhân viên, trả về thông báo lỗi
        if (!$staff) {
            return redirect()->route('login.login')->with('error', 'Not found information of staff');
        }

        if ($staff->role_id != 1 && $staff->role_id != 2){
            return redirect()->route('login.login')->with('error', 'You can not access this function!');
        }
        DB::table('tbl_faculties')->where('faculty_id',$faculty_id)->update(['status'=>1]);
        Session::put('message','Failed to Activate the Faculty successfully');
        return Redirect::to('list-faculty');
    }
    public function edit_status_hide_faclties($faculty_id){
        $staff_id = session('staff_id');

        // Nếu không tìm thấy staff_id trong session, chuyển hướng đến trang đăng nhập
        if (!$staff_id) {
            return redirect()->route('login.login')->with('error', 'You login not yet.');
        }

        // Lấy thông tin nhân viên dựa trên staff_id
        $staff = StaffModel::where('staff_id', $staff_id)->first();

        // Nếu không tìm thấy thông tin nhân viên, trả về thông báo lỗi
        if (!$staff) {
            return redirect()->route('login.login')->with('error', 'Not found information of staff');
        }

        if ($staff->role_id != 1 && $staff->role_id != 2){
            return redirect()->route('login.login')->with('error', 'You can not access this function!');
        }
        DB::table('tbl_faculties')->where('faculty_id',$faculty_id)->update(['status'=>0]);
        Session::put('message','Activate the Faculty successfully!');
        return Redirect::to('list-faculty');
    }


}