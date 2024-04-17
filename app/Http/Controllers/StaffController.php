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
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Database\QueryException;
class StaffController extends Controller
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
public function delete_staff($staff_id)
{
    try {
        // Tìm nhân viên để xóa
        $staff = StaffModel::find($staff_id);

        // Kiểm tra xem nhân viên có tồn tại không
        if($staff) {
            // Kiểm tra xem role_id của nhân viên có phải là 1 không
            if($staff->role_id != 1) {
                // Thử xóa nhân viên
                $staff->delete();
                // Xóa ảnh của nhân viên từ thư mục
                $imageName = $staff->images;
                if($imageName && file_exists(public_path('images/faces/staff/'.$imageName))) {
                    unlink(public_path('images/faces/staff/'.$imageName));
                }
                // Chuyển hướng về trang danh sách nhân viên và hiển thị thông báo thành công
                return redirect()->route('staffs.list-staff')->with('sussec', 'Successfully deleted employee.');
            } else {
                // Nếu role_id là 1, không cho phép xóa và hiển thị thông báo lỗi
                return redirect()->route('staffs.list-staff')->with('error', 'This employee cannot be deleted.');
            }
        } else {
            // Nếu không tìm thấy nhân viên, hiển thị thông báo lỗi
            return redirect()->route('staffs.list-staff')->with('error', 'Staff does not exist');
        }
    } catch (QueryException $e) {
        // Bắt lỗi nếu không thể xóa nhân viên do liên kết khóa ngoại
        if($e->errorInfo[1] == 1451) {
            return redirect()->route('staffs.list-staff')->with('error', 'This employee cannot be deleted due to associated data.');
        } else {
            return redirect()->route('staffs.list-staff')->with('error', 'An error occurred while deleting the employee.');
        }
    }
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

    //my

    public function profile_edit($profile_id)
    {
        // Lấy staff_id từ session
        $staff_id = session('staff_id');

        // Nếu không tìm thấy staff_id trong session, chuyển hướng đến trang đăng nhập
        if (!$staff_id) {
            return redirect()->route('login.login')->with('error', 'Bạn chưa đăng nhập.');
        }

        // Lấy thông tin nhân viên dựa trên staff_id
        $staff = StaffModel::where('staff_id', $staff_id)->first();

        // Nếu không tìm thấy thông tin nhân viên, trả về thông báo lỗi
        if (!$staff) {
            return redirect()->route('login.login')->with('error', 'Not found this information staff');
        }

        $profiles = DB::table('tbl_staff')->where('staff_id',$profile_id)->get();
        $profile = DB::table('tbl_staff')->where('staff_id', $staff->staff_id)->where('staff_id',$profile_id)->get();

        if ($staff->role_id == 1){
            $managemet_profile = view('staffs.profile-edit')-> with('profile_edit',$profile);
            return view('layout') ->with('staffs.profile-edit', $managemet_profile);
        }

        if ($profile != $profiles){
            Session::put('message_error1','YOU CAN NOT SEE THIS PROFILE');
            return  redirect('dashboard');
        }


        $managemet_profile = view('staffs.profile-edit')-> with('profile_edit',$profiles);
        return view('layout') ->with('staffs.profile-edit', $managemet_profile);
    }

    public function update_profile(Request $request, $profile_id)
    {
        $request ->validate([
           'staff_name' => 'required',
           'phone' =>'required',
            'Avatar' =>'image|mimes:jpeg,png,jpg,gif|max:2048',
        ],[
            'staff_name.required' => 'Enter your new name',
            'phone.required' => 'Enter your new phone',
        ]);

        $data = array();
        $data['staffname'] = $request -> staff_name;
        $data['phone'] = $request -> phone;

        $get_image = $request -> file('Avatar');
        if ($get_image){
            $get_name_image = $get_image -> getClientOriginalName();
            $name_image  = current(explode('.',$get_name_image));
            $new_image = $name_image.'.'.$get_image -> getClientOriginalExtension();
            $get_image -> move('resources/images/Faces/', $new_image);
            $data['images'] = $new_image;

            DB::table('tbl_staff') -> where('staff_id',$profile_id) -> update($data);

            Session::put('message_update','Profile information has been updated');
            return Redirect::to('/show-profile/{profile_id}');
        }

        DB::table('tbl_staff') -> where('staff_id',$profile_id) -> update($data);
        Session::put('message_update','Profile information has been updated');
        return redirect('/show-profile/{profile_id}');
    }


    //CANCEL
    public function cancel_edit_profile()
    {
//        Session::put('message_cancel','Cancel edit!');
        return Redirect::to('/show-profile/{profile_id}');

    }

    public function show_profile($profile_id)
    {
        // Lấy staff_id từ session
        $staff_id = session('staff_id');

        // Nếu không tìm thấy staff_id trong session, chuyển hướng đến trang đăng nhập
        if (!$staff_id) {
            return redirect()->route('login.login')->with('error', 'Bạn chưa đăng nhập.');
        }

        // Lấy thông tin nhân viên dựa trên staff_id
        $staff = StaffModel::where('staff_id', $staff_id)->first();

        // Nếu không tìm thấy thông tin nhân viên, trả về thông báo lỗi
        if (!$staff) {
            return redirect()->route('login.login')->with('error', 'Không tìm thấy thông tin nhân viên.');
        }

        $profiles = DB::table('tbl_staff')->where('staff_id',$profile_id)->get();
        $profile = DB::table('tbl_staff')->where('staff_id', $staff->staff_id)->get();

        if ($profile != $profiles){
            Session::put('message_error1','YOU CAN NOT SEE THIS PROFILE');
            return  Redirect::to('dashboard');
        }


        $mana_profile = view('staffs.show-profile')->with('show_profile',$profiles);
        return view('layout')->with('staffs.show-profile',$mana_profile);
    }

    public function profile_login_edit($profile_id)
    {
        // Lấy staff_id từ session
        $staff_id = session('staff_id');

        // Nếu không tìm thấy staff_id trong session, chuyển hướng đến trang đăng nhập
        if (!$staff_id) {
            return redirect()->route('login.login')->with('error', 'Bạn chưa đăng nhập.');
        }

        // Lấy thông tin nhân viên dựa trên staff_id
        $staff = StaffModel::where('staff_id', $staff_id)->first();

        // Nếu không tìm thấy thông tin nhân viên, trả về thông báo lỗi
        if (!$staff) {
            return redirect()->route('login.login')->with('error', 'Không tìm thấy thông tin nhân viên.');
        }

        $profiles = DB::table('tbl_staff')->where('staff_id',$profile_id)->get();
        $profile = DB::table('tbl_staff')->where('staff_id', $staff->staff_id)->get();

        if ($profile != $profiles){
            Session::put('message_error_profile','YOU CAN NOT SEE THIS PROFILE');
            return  Redirect::to('dashboard');
        }
        $mana_profile = view('staffs.profile-login-edit')->with('profile_login_edit',$profiles);
        return view('layout')->with('staffs.profile-login-edit',$mana_profile);

    }

    public function update_profile_login(Request $request, $profile_id)
    {


        $request->validate([
            'old_pass' => 'required|min:6|max:100',
            'new_pass' => 'required|min:6|max:100',
            'con_new_pass' => 'required|same:new_pass',

        ],[
            'old_pass.required' => 'Enter your old password please!',
            'new_pass.required' => 'Enter your new password please!',
            'con_new_pass.required' => 'Enter your new password again please!',
            'old_pass.min' => 'Password must be at least 6 characters',
            'new_pass.min' => 'Password must be at least 6 characters',
            'con_new_pass.same' => 'Confirmation password does not match!',



        ]);



        $data = array();
        $new_pass = $request -> new_pass;

        $decryptedData =  bcrypt($new_pass);
        $data['password'] = $decryptedData;
        DB::table('tbl_staff') -> where('staff_id',$profile_id)->update($data);
        return redirect()->route('login.login');



    }


}