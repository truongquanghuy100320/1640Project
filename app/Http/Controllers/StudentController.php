<?php

namespace App\Http\Controllers;

use App\Models\FacltiesModel;
use App\Models\RoleModel;
use App\Models\StudentModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use Illuminate\Database\QueryException;

class StudentController extends Controller
{
    public  function List_student()
    {
        $list_student = StudentModel::all();
        return view('students.list-student',['list_student'=>$list_student]);
    }
    public  function user_student()
    {
        $faculty = FacltiesModel::orderby('faculty_id')->get();
        $role = RoleModel::orderby('role_id')->get();
        return view('students.add-student',compact('faculty'), compact('role'));
    }
    public function save_student(Request $request )
    {
        // Validate input data
        $rules =  [
            'studentname' => 'required',
            'MSV' => 'required|unique:tbl_students,MSV', // Kiểm tra tính duy nhất của MSV trong bảng tbl_students
            'email_login' => 'required|email|unique:tbl_students,email', // Kiểm tra tính duy nhất của email trong bảng tbl_students
            'password' => 'required',
            'studentphone' => 'required',
            'faculty_id' => 'required',
            'status' => 'required',
            'images' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Kiểm tra định dạng và kích thước hình ảnh
        ];
        // Kiểm tra xem email có được cập nhật mới hay không
        $emailUpdated = $request->filled('email_login');

// Kiểm tra xem MSV có được cập nhật mới hay không
        $msvUpdated = $request->filled('MSV');

// Nếu email không được cập nhật mới, không cần kiểm tra quy tắc duy nhất
        if (!$emailUpdated) {
            unset($rules['email_login']);
        }

// Nếu MSV không được cập nhật mới, không cần kiểm tra quy tắc duy nhất
        if (!$msvUpdated) {
            unset($rules['MSV']);
        }

// Nếu email được cập nhật mới, thêm quy tắc duy nhất cho email
        if ($emailUpdated) {
            $rules['email_login'] = [
                'required',
                Rule::unique('tbl_students'),
            ];
        }

// Nếu MSV được cập nhật mới, thêm quy tắc duy nhất cho MSV
        if ($msvUpdated) {
            $rules['MSV'] = [
                'required',
                Rule::unique('tbl_students'),
            ];
        }

        $request->validate($rules);

        // Xử lý lưu ảnh
        $image = $request->file('images');
        $imageName = time() . '.' . $image->extension();
        $image->move(public_path('images/faces/student'), $imageName);

        // Tạo mới đối tượng Student và lưu vào database
        DB::table('tbl_students')->insert([
            'studentname' => $request->studentname,
            'MSV' => $request->MSV,
            'email_login' => $request->email_login,
            'password' => bcrypt($request->password),
            'studentphone' => $request->studentphone,
            'faculty_id' => $request->faculty_id,
            'status' => $request->status,
            'images' => $imageName,
            'role_id' => 4,
            'created_at' => now(), // Tự động thêm thời gian tạo

        ]);
        // Thêm thông báo thành công vào Session
        $request->session()->flash('success', 'Thêm học sinh thành công');

        // Redirect về trang thêm học sinh
        return redirect()->route('students.add-student');
    }
    public function edit_status_show_student($student_id)
    {
        $currentStudent = Auth::user();
        $studentEdit = StudentModel::find($student_id);
        if($studentEdit){
            if($currentStudent->role_id===1){
                if( $currentStudent->id !==$studentEdit){
                    if($studentEdit !== 1){
                        $studentEdit->status =1;
                        $studentEdit -> updated_at = now();
                        $studentEdit->save();
                        return redirect()->route('students.list-student')->with('success', 'User status shown successfully.');
                    }else{
                        return redirect()->back()->with('error', 'Student with the highest permissions cannot edit.');
                    }
                }else{
                    return redirect()->back()->with('error', 'You cannot show your own status.');
                }

            }else{
                return redirect()->back()->with('error', 'You do not have permission to perform this operation.');
            }
        }else{
            return redirect()->back()->with('error', 'User not found.');
        }

    }
    public  function edit_status_hide_student($Student_id)
    {
        $currentStudent = Auth::user();
        $studentEdit = StudentModel::find($Student_id);
        if($studentEdit){
            if($currentStudent->role_id===1){
                if($currentStudent ->id !==$Student_id){
                    if($studentEdit !==1){
                        $studentEdit -> status =0;
                        $studentEdit -> updated_at = now();
                        $studentEdit->save();
                        return redirect()->route('students.list-student')->with('success', 'User status shown successfully.');
                    }else{
                        return redirect()->back()->with('error', 'Student with the highest permissions cannot edit.');
                    }

                }else{
                    return redirect()->back()->with('error', 'You cannot show your own status.');
                }

            }else{
                return redirect()->back()->with('error', 'You do not have permission to perform this operation.');
            }

        }else{
            return redirect()->back()->with('error', 'User not found.');
        }

    }
    public function edit_student($student_id)
    {
        $faculty = FacltiesModel::orderBy('faculty_id')->get();
        $role = RoleModel::orderBy('role_id')->get();
        $edit = StudentModel::find($student_id);
        return view('students.edit-student', compact('faculty', 'role', 'edit'));
    }
    public function update_student(Request $request, $student_id)
    {
        $rules = [
            'studentname' => 'required',
            'password' => 'required',
            'studentphone' => 'required',
            'faculty_id' => 'required',
            'status' => 'required',
            'images' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];

        // Kiểm tra xem email có được cập nhật mới hay không
        $emailUpdated = $request->filled('email_login');

// Kiểm tra xem MSV có được cập nhật mới hay không
        $msvUpdated = $request->filled('MSV');

// Nếu email không được cập nhật mới, không cần kiểm tra quy tắc duy nhất
        if (!$emailUpdated) {
            unset($rules['email_login']);
        }

// Nếu MSV không được cập nhật mới, không cần kiểm tra quy tắc duy nhất
        if (!$msvUpdated) {
            unset($rules['MSV']);
        }

// Nếu email được cập nhật mới, thêm quy tắc duy nhất cho email
        if ($emailUpdated) {
            $rules['email_login'] = [
                'required',
                Rule::unique('tbl_students')->ignore($student_id, 'student_id'),
            ];
        }

// Nếu MSV được cập nhật mới, thêm quy tắc duy nhất cho MSV
        if ($msvUpdated) {
            $rules['MSV'] = [
                'required',
                Rule::unique('tbl_students')->ignore($student_id, 'student_id'),
            ];
        }

        $request->validate($rules);


        $request->validate($rules);
        // Lấy thông tin sinh viên cần cập nhật
        $student = StudentModel::find($student_id);

        // Cập nhật các thông tin
        $student->studentname = $request->studentname;
        $student->MSV = $request->MSV;
        $student->password = bcrypt($request->password);
        $student->studentphone = $request->studentphone;
        $student->faculty_id = $request->faculty_id;
        $student->status = $request->status;
        $student->updated_at = now();


        // Chỉ cập nhật ảnh nếu được cung cấp
        if ($request->hasFile('images')) {
            // Xóa ảnh cũ nếu tồn tại
            if ($student->images) {
                $oldImagePath = public_path('images/faces/student/' . $student->images);
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }

            // Upload ảnh mới
            $image = $request->file('images');
            $imageName = time() . '.' . $image->extension();
            $image->move(public_path('images/faces/student'), $imageName);

            // Cập nhật đường dẫn ảnh mới vào cơ sở dữ liệu
            $student->images = $imageName;
        }



        // Lưu thông tin đã cập nhật vào cơ sở dữ liệu
        $student->save();

        // Thêm thông báo thành công vào Session
        $request->session()->flash('sussec', 'Chỉnh sửa học sinh thành công');

        // Redirect về trang chỉnh sửa học sinh
        return redirect()->route('students.list-student', $student_id);
    }

public function delete_student($student_id)
{
    try {
        // Tìm sinh viên để xóa
        $student = StudentModel::find($student_id);

        // Kiểm tra xem sinh viên có tồn tại không
        if($student) {
            // Kiểm tra xem role_id của sinh viên có phải là 1 không
            if($student->role_id != 1) {
                // Thử xóa sinh viên
                $student->delete();

                // Xóa ảnh của sinh viên từ thư mục
                $imageName = $student->images;
                if($imageName && file_exists(public_path('images/faces/student/'.$imageName))) {
                    unlink(public_path('images/faces/student/'.$imageName));
                }

                // Chuyển hướng về trang danh sách sinh viên và hiển thị thông báo thành công
                return redirect()->route('students.list-student')->with('sussec', 'Successfully deleted student.');
            } else {
                // Nếu role_id là 1, không cho phép xóa và hiển thị thông báo lỗi
                return redirect()->route('students.list-student')->with('error', 'This student cannot be deleted.');
            }
        } else {
            // Nếu không tìm thấy sinh viên, hiển thị thông báo lỗi
            return redirect()->route('students.list-student')->with('error', 'Student does not exist.');
        }
    } catch (QueryException $e) {
        // Bắt lỗi nếu không thể xóa sinh viên do liên kết khóa ngoại
        if($e->errorInfo[1] == 1451) {
            return redirect()->route('students.list-student')->with('error', 'This student cannot be deleted due to associated data.');
        } else {
            return redirect()->route('students.list-student')->with('error', 'An error occurred while deleting the student.');
        }
    }
}

}
