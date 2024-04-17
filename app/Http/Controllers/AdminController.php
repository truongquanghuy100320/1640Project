<?php

namespace App\Http\Controllers;

use App\Models\FacltiesModel;
use App\Models\RoleModel;
use App\Models\StaffModel;
use App\Models\StudentModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class AdminController extends Controller
{


    public function list_staff()
    {
        $list_staff = StaffModel::all();
        return view('staffs.list-staff',['list_staff'=>$list_staff]);
    }

    public function create_staff()
    {
        $faculty = FacltiesModel::orderby('faculty_id')->get();
        $role = RoleModel::orderby('role_id')->get();
        return view('staffs.create-staff',compact('faculty'), compact('role'));
    }

    public  function  save_staff(Request $request)
    {
        $rules = [
            'staffname' => 'required',
            'MSStaff' => 'required|unique:tbl_staff,MSStaff', // Kiểm tra tính duy nhất của MSV trong bảng tbl_students
            'email' => 'required|email|unique:tbl_staff,email', // Kiểm tra tính duy nhất của email trong bảng tbl_students
            'password' => 'required',
            'phone' => 'required',
            'faculty_id' => 'required',
            'role_id' => 'required',
            'status' => 'required',
            'images' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Kiểm tra định dạng và kích thước hình ảnh
        ];
        $emailUpdated = $request->filled('email');

// Kiểm tra xem MSV có được cập nhật mới hay không
        $msvUpdated = $request->filled('MSStaff');

// Nếu email không được cập nhật mới, không cần kiểm tra quy tắc duy nhất
        if (!$emailUpdated) {
            unset($rules['email']);
        }

// Nếu MSV không được cập nhật mới, không cần kiểm tra quy tắc duy nhất
        if (!$msvUpdated) {
            unset($rules['MSStaff']);
        }

// Nếu email được cập nhật mới, thêm quy tắc duy nhất cho email
        if ($emailUpdated) {
            $rules['email'] = [
                'required',
                Rule::unique('tbl_staff'),
            ];
        }

// Nếu MSV được cập nhật mới, thêm quy tắc duy nhất cho MSV
        if ($msvUpdated) {
            $rules['MSStaff'] = [
                'required',
                Rule::unique('tbl_staff'),
            ];
        }

        $request->validate($rules);
        $image = $request->file('images');
        $imageName = time() . '.' . $image->extension();
        $image->move(public_path('images/faces/staff'), $imageName);

        DB::table('tbl_staff')->insert([
            'staffname' => $request->staffname,
            'MSStaff' => $request->MSStaff,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'phone' => $request->phone,
            'faculty_id' => $request->faculty_id,
            'status' => $request->status,
            'images' => $imageName,
            'role_id' => $request->role_id,
            'created_at' => now(), // Tự động thêm thời gian tạo

        ]);
        $request->session()->flash('success', 'Thêm nhân viên thành công');

        // Redirect về trang thêm học sinh
        return redirect()->route('staffs.create-staff');
    }

    public function edit_staff($staff_id)
    {
        $edit = StaffModel::find($staff_id);

        // Kiểm tra nếu role_id của nhân viên là 1
        if ($edit && $edit->role_id == 1) {
            // Nếu là role_id = 1, chuyển hướng về trang list-staff với thông báo
            return redirect()->route('staffs.list-staff')->with('error', 'Bạn không thể chỉnh sửa quyền cao nhất.');
        }

        // Nếu không phải role_id = 1, tiếp tục lấy dữ liệu và trả về view
        $faculty = FacltiesModel::orderBy('faculty_id')->get();
        $role = RoleModel::orderBy('role_id')->get();

        return view('staffs.edit-staff', compact('role', 'faculty', 'edit'));
    }


    public function update_staff(Request $request, $staff_id)
    {
        $rules = [
            'staffname' => 'required',
            'password' => 'required',
            'phone' => 'required',
            'faculty_id' => 'required',
            'role_id' => 'required',
            'status' => 'required',
            'images' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];
        // Kiểm tra xem email có được cập nhật mới hay không
        $emailUpdated = $request->filled('email');

// Kiểm tra xem MSV có được cập nhật mới hay không
        $msvUpdated = $request->filled('MSStaff');

// Nếu email không được cập nhật mới, không cần kiểm tra quy tắc duy nhất
        if (!$emailUpdated) {
            unset($rules['email']);
        }

// Nếu MSV không được cập nhật mới, không cần kiểm tra quy tắc duy nhất
        if (!$msvUpdated) {
            unset($rules['MSStaff']);
        }

// Nếu email được cập nhật mới, thêm quy tắc duy nhất cho email
        if ($emailUpdated) {
            $rules['email'] = [
                'required',
                Rule::unique('tbl_staff')->ignore($staff_id, 'staff_id'),
            ];
        }

// Nếu MSV được cập nhật mới, thêm quy tắc duy nhất cho MSV
        if ($msvUpdated) {
            $rules['MSStaff'] = [
                'required',
                Rule::unique('tbl_staff')->ignore($staff_id, 'staff_id'),
            ];
        }
        $request->validate($rules);
        $staff = StaffModel::find($staff_id);

        // Cập nhật các thông tin
        $staff->staffname = $request->staffname;
        $staff->MSStaff = $request->MSStaff;
        $staff->password = bcrypt($request->password);
        $staff->phone = $request->phone;
        $staff->email = $request->email;
        $staff->faculty_id = $request->faculty_id;
        $staff->role_id = $request->role_id;
        $staff->status = $request->status;
        $staff->updated_at = now();


        // Chỉ cập nhật ảnh nếu được cung cấp
        if ($request->hasFile('images')) {
            // Xóa ảnh cũ nếu tồn tại
            if ($staff->images) {
                $oldImagePath = public_path('images/faces/staff/' . $staff->images);
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }

            // Upload ảnh mới
            $image = $request->file('images');
            $imageName = time() . '.' . $image->extension();
            $image->move(public_path('images/faces/staff'), $imageName);

            // Cập nhật đường dẫn ảnh mới vào cơ sở dữ liệu
            $staff->images = $imageName;
        }



        // Lưu thông tin đã cập nhật vào cơ sở dữ liệu
        $staff->save();

        // Thêm thông báo thành công vào Session
        $request->session()->flash('sussec', 'Chỉnh sửa nhân viên thành công');

        // Redirect về trang chỉnh sửa học sinh
        return redirect()->route('staffs.list-staff', $staff_id);
    }

    public  function edit_status_show_staff($staff_id)
    {

    }
    public function edit_status_hide_staff($staff_id)
    {
        $currentStaff = Auth::user();
        $staffEdit = StaffModel::find($staff_id);

        if (!$staffEdit) {
            return redirect()->back()->with('error', 'User not found.');
        }

        // Kiểm tra role_id của người dùng hiện tại và của người dùng cần chỉnh sửa
        if ($currentStaff->role_id === 1) {
            if ($currentStaff->id !== $staffEdit->id) { // Không được phép chỉnh sửa chính mình
                if ($staffEdit->role_id !== 1) { // Không được phép chỉnh sửa nhân viên có role_id là 1
                    $staffEdit->status = 0;
                    $staffEdit->updated_at = now();
                    $staffEdit->save();
                    return redirect()->route('staffs.list-staff')->with('success', 'User status hidden successfully.');
                } else {
                    return redirect()->route('staffs.list-staff')->with('error', 'Staff with the highest permissions cannot be edited.');
                }
            } else {
                return redirect()->route('staffs.list-staff')->with('error', 'You cannot hide your own status.');
            }
        } else {
            return redirect()->route('staffs.list-staff')->with('error', 'You do not have permission to perform this operation.');
        }
    }


}