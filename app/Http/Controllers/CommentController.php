<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Models\ChatModel;
use App\Models\CommentModel;
use App\Models\StaffModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;

class CommentController extends Controller
{
    //GO TO CHAT PAGE
public function chatbox()
{
    // Lấy staff_id từ session
    $staff_id = session('staff_id');

    // Nếu không tìm thấy staff_id trong session, chuyển hướng đến trang đăng nhập
    if (!$staff_id) {
        return redirect()->route('login.login')->with('error', 'You login not yet.');
    }

    // Lấy thông tin nhân viên dựa trên staff_id
    $staff = StaffModel::where('staff_id', $staff_id)->first();

    // Nếu không tìm thấy thông tin nhân viên, trả về thông báo lỗi
    if (!$staff) {
        return redirect()->route('login.login')->with('error', 'Not found information of staff.');
    }

    // Nếu là nhân viên có quyền truy cập, lấy danh sách sinh viên có tin nhắn
  $latestMessages = ChatModel::select(
    'tbl_students.student_id',
    'tbl_students.studentname as studentname',
    'tbl_students.images as images',
    DB::raw('MAX(chatmessages.created_at) as latest_message_created_at'), // Phần này của truy vấn sử dụng hàm MAX()
    //để lấy giá trị lớn nhất của cột created_at từ bảng chatmessages.
    //Nó đổi tên giá trị tính toán này thành latest_message_created_at
    DB::raw('SUBSTRING_INDEX(GROUP_CONCAT(chatmessages.content ORDER BY chatmessages.created_at DESC), ",", 1) as latest_message_content')
    //Ở đây, chúng ta đang sử dụng DB::raw() để bao gồm các biểu thức SQL nguyên thủy trong truy vấn của chúng ta. Chúng ta đang sử dụng hàm GROUP_CONCAT() để nối tất cả các giá trị content
    //từ bảng chatmessages cho mỗi sinh viên, được sắp xếp theo created_at theo thứ tự giảm dần. Sau đó, chúng ta sử dụng SUBSTRING_INDEX để trích xuất tin
    //nhắn đầu tiên (mới nhất) từ chuỗi đã nối. Chúng ta đặt tên biểu thức này là latest_message_content.
)
->join('tbl_students', 'chatmessages.student_id', '=', 'tbl_students.student_id')
->groupBy('tbl_students.student_id', 'tbl_students.studentname', 'tbl_students.images')
->orderBy('latest_message_created_at', 'desc') // Sắp xếp theo ngày gửi tin nhắn giảm dần
->get();

return view('comments.chat', ['latestMessages' => $latestMessages]);




}





public function student_mess($id)
{
    // Cập nhật trạng thái của tin nhắn
    $data = ['STATUS' => 1];
    DB::table('chatmessages')->where('student_id', $id)->update($data);

    // Lấy tất cả các tin nhắn của sinh viên kèm theo thông tin nhân viên
    $messages = DB::table('chatmessages')
        ->join('tbl_staff', 'chatmessages.staff_id', '=', 'tbl_staff.staff_id')
        ->join('tbl_faculties', 'chatmessages.faculty_id', '=', 'tbl_faculties.faculty_id')
        ->join('tbl_students', 'chatmessages.student_id', '=', 'tbl_students.student_id')
        ->select('chatmessages.*',
                 'tbl_staff.staff_id', 'tbl_staff.staffname', 'tbl_staff.images as staff_image',
                 'tbl_students.student_id', 'tbl_students.studentname', 'tbl_students.MSV', 'tbl_students.images as student_image',
                 'tbl_faculties.faculty_id', 'tbl_faculties.faculty_name')
        ->where('chatmessages.student_id', $id)
        ->orderBy('chatmessages.created_at', 'desc')
        ->get();

    // Trả về view với danh sách tin nhắn của sinh viên
    return view('comments.sendMessage', ['sendMessage' => $messages]);
}



public function send_message(Request $request)
{
    $data = [
        'content' => $request->message,
        'sender_type' => 'staff',
        'staff_id' => $request->staff_id,
        'student_id' => $request->student_id,
        'faculty_id' => $request->faculty_id,
        'status' => $request->status ?: 1
    ];

    // Lưu tin nhắn vào cơ sở dữ liệu
    DB::table('chatmessages')->insert($data);

    // Chuyển hướng sau khi lưu tin nhắn
    return redirect()->route('student_mess', ['id' => $request->student_id]);
}












}
