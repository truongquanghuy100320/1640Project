<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserModel;
use App\Models\RoleModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use function Laravel\Prompts\table;
use Illuminate\Database\QueryException;
class UserController extends Controller
{

    public function List_user()
    {
        $list_user =  UserModel::all();

        return view('Users.list-user',['list_user'=>$list_user]);
    }
    public function edit_Marketing_Coordinator($userId)
    {
        // Kiểm tra người dùng hiện tại
        $currentUser = Auth::user();

        // Lấy thông tin của người dùng được chỉnh sửa
        $userToEdit = UserModel::find($userId);

        // Kiểm tra xem $userToEdit có tồn tại không
        if ($userToEdit) {
            // Kiểm tra xem người dùng hiện tại có role_id là 1 (Super Admin) không
            if ($currentUser->role_id === 1) {
                // Kiểm tra xem người dùng được chỉnh sửa có khác với người dùng hiện tại không
                if ($currentUser->id !== $userId) {
                    // Kiểm tra xem người dùng được chỉnh sửa có role_id khác 1 không
                    if ($userToEdit->role_id !== 1) {
                        // Kiểm tra xem người dùng được chỉnh sửa có role_id là 3 (ROLE_COORDINATOR_MARKETING) không
                        if ($userToEdit->role_id !== 3) {
                            // Nếu không phải là người dùng hiện tại, không phải là Super Admin, và không phải là ROLE_COORDINATOR_MARKETING, thực hiện nâng cấp người dùng lên làm Coordinator Marketing
                            $userToEdit->role_id = 3;
                            $userToEdit->user_type = 'ROLE_COORDINATOR_MARKETING';


                            $userToEdit->save();

                            // Redirect hoặc hiển thị thông báo thành công
                            return redirect()->route('Users.list-user')->with('success', 'User upgraded successfully.');
                        } else {
                            // Nếu người dùng được chỉnh sửa đã ở quyền hạn hiện tại, hiển thị thông báo lỗi
                            return redirect()->back()->with('error', 'NThis user is already at current permissions. You cannot perform an upgrade.');
                        }
                    } else {
                        // Nếu người dùng được chỉnh sửa có role_id = 1, hiển thị thông báo lỗi
                        return redirect()->back()->with('error', 'Users with the highest permissions cannot edit.');
                    }
                } else {
                    // Nếu người dùng hiện tại đang cố gắng chỉnh sửa chính mình, quay trở lại trang trước và hiển thị thông báo lỗi
                    return redirect()->back()->with('error', 'You cannot upgrade yourself to Marketing Coordinator.');
                }
            } else {
                // Nếu người dùng hiện tại không phải là Super Admin, chuyển hướng về trang trước và hiển thị thông báo lỗi
                return redirect()->back()->with('error', 'You do not have permission to perform this operation.');
            }
        } else {
            // Nếu không tìm thấy người dùng được chỉnh sửa, chuyển hướng về trang trước và hiển thị thông báo lỗi
            return redirect()->back()->with('error', 'This user is already at current permissions. You cannot perform an upgrade.');
        }
    }


    public function edit_admin($userId)
    {
        // Kiểm tra người dùng hiện tại
        $currentUser = Auth::user();

        // Lấy thông tin của người dùng được chỉnh sửa
        $userToEdit = UserModel::find($userId);

        // Kiểm tra xem $userToEdit có tồn tại không
        if ($userToEdit) {
            // Kiểm tra xem người dùng hiện tại có role_id là 1 (Super Admin) không
            if ($currentUser->role_id === 1) {
                // Kiểm tra xem người dùng được chỉnh sửa có khác với người dùng hiện tại không
                if ($currentUser->id !== $userId) {
                    // Kiểm tra xem người dùng được chỉnh sửa có role_id khác 1 không
                    if ($userToEdit->role_id !== 1) {
                        // Kiểm tra xem người dùng được chỉnh sửa có role_id là 3 (ROLE_COORDINATOR_MARKETING) không
                        if ($userToEdit->role_id !== 2) {
                            // Nếu không phải là người dùng hiện tại, không phải là Super Admin, và không phải là ROLE_COORDINATOR_MARKETING, thực hiện nâng cấp người dùng lên làm Coordinator Marketing
                            $userToEdit->role_id = 2;
                            $userToEdit->user_type = 'ROLE_ADMINISTRATORS';
                            $userToEdit->updated_at = now();
                            $userToEdit->save();

                            // Redirect hoặc hiển thị thông báo thành công
                            return redirect()->route('Users.list-user')->with('success', 'User upgraded successfully.');
                        } else {
                            // Nếu người dùng được chỉnh sửa đã ở quyền hạn hiện tại, hiển thị thông báo lỗi
                            return redirect()->back()->with('error', 'This user is already at current permissions. You cannot perform an upgrade.');
                        }
                    } else {
                        // Nếu người dùng được chỉnh sửa có role_id = 1, hiển thị thông báo lỗi
                        return redirect()->back()->with('error', 'Users with the highest permissions cannot edit.');
                    }
                } else {
                    // Nếu người dùng hiện tại đang cố gắng chỉnh sửa chính mình, quay trở lại trang trước và hiển thị thông báo lỗi
                    return redirect()->back()->with('error', 'You cannot upgrade yourself to Admin.');
                }
            } else {
                // Nếu người dùng hiện tại không phải là Super Admin, chuyển hướng về trang trước và hiển thị thông báo lỗi
                return redirect()->back()->with('error', 'You do not have permission to perform this operation.');
            }
        } else {
            // Nếu không tìm thấy người dùng được chỉnh sửa, chuyển hướng về trang trước và hiển thị thông báo lỗi
            return redirect()->back()->with('error', 'This user is already at current permissions. You cannot perform an upgrade.');
        }
    }

   public  function edit_user($userId)
    {
        $currentUser = Auth::user();

        // Lấy thông tin của người dùng được chỉnh sửa
        $userToEdit = UserModel::find($userId);

        if ($userToEdit){
            if($currentUser->role_id ===1){
                if($currentUser ->id !== $userId){
                    if($userToEdit !==1){
                        if($userToEdit !==4){
                            $userToEdit->role_id = 4;
                            $userToEdit->user_type = 'ROLE_USERS';
                            $userToEdit->updated_at = now();
                            $userToEdit->save();
                            return redirect()->route('Users.list-user')->with('success', 'User upgraded successfully.');
                        }else{
                            return redirect()->back()->with('error', 'This user is already at current permissions. You cannot perform an upgrade.');
                        }

                    }else{
                        return redirect()->back()->with('error', 'Users with the highest permissions cannot edit.');
                    }
                }else{
                    return redirect()->back()->with('error', 'You cannot upgrade yourself to User.');
                }

            }else{
                return redirect()->back()->with('error', 'You do not have permission to perform this operation.');
            }

        }else{
            return redirect()->back()->with('error', 'This user is already at current permissions. You cannot perform an upgrade.');
        }
    }

    public function edit_status_hide($userId)
    {
        $currentUser = Auth::user();

        // Lấy thông tin của người dùng được chỉnh sửa
        $userToEdit = UserModel::find($userId);

        if ($userToEdit) {
            if ($currentUser->role_id === 1) {
                if ($currentUser->id !== $userId) {
                    if ($userToEdit->role_id !== 1) { // Kiểm tra nếu role_id không phải là 1 (Super Admin)
                        $userToEdit->status_user = 0; // Ẩn trạng thái của người dùng
                        $userToEdit->save();
                        return redirect()->route('Users.list-user')->with('success', 'User status hidden successfully.');
                    } else {
                        return redirect()->back()->with('error', 'Users with the highest permissions cannot edit.');
                    }
                } else {
                    return redirect()->back()->with('error', 'You cannot hide your own status.');
                }
            } else {
                return redirect()->back()->with('error', 'You do not have permission to perform this operation.');
            }
        } else {
            return redirect()->back()->with('error', 'User not found.');
        }
    }

    public function edit_status_show($userId)
    {
        $currentUser = Auth::user();

        // Lấy thông tin của người dùng được chỉnh sửa
        $userToEdit = UserModel::find($userId);

        // Kiểm tra xem người dùng có tồn tại không
        if ($userToEdit) {
            // Kiểm tra quyền hạn của người dùng hiện tại
            if ($currentUser->role_id === 1) { // Nếu là Super Admin
                // Kiểm tra xem người dùng hiện tại có phải là chính người dùng được chỉnh sửa hay không
                if ($currentUser->id !== $userId) {
                    // Kiểm tra xem người dùng được chỉnh sửa có role_id không phải là 1 (Super Admin) hay không
                    if ($userToEdit->role_id !== 1) {
                        // Cập nhật trạng thái của người dùng để hiển thị (hoặc bật)
                        $userToEdit->status_user = 1; // Giả sử 1 là trạng thái để hiển thị (hoặc bật)
                        $userToEdit->save(); // Lưu thay đổi vào cơ sở dữ liệu

                        // Redirect hoặc trả về một phản hồi thành công
                        return redirect()->route('Users.list-user')->with('success', 'User status shown successfully.');
                    } else {
                        // Nếu người dùng có role_id là 1 (Super Admin), không thể chỉnh sửa
                        return redirect()->back()->with('error', 'Users with the highest permissions cannot edit.');
                    }
                } else {
                    // Nếu người dùng cố gắng chỉnh sửa chính họ
                    return redirect()->back()->with('error', 'You cannot show your own status.');
                }
            } else {
                // Nếu người dùng hiện tại không phải là Super Admin, không có quyền thực hiện thao tác này
                return redirect()->back()->with('error', 'You do not have permission to perform this operation.');
            }
        } else {
            // Nếu không tìm thấy người dùng trong cơ sở dữ liệu
            return redirect()->back()->with('error', 'User not found.');
        }
    }

 public function user_add()
 {

     $roles = RoleModel::orderBy('role_id')->get();
     return view('Users.add-user', compact('roles'));
 }
 public function delete_user($user_id)
{
    try {
        // Tìm người dùng để xóa
        $user = UserModel::find($user_id);

        // Kiểm tra xem người dùng có tồn tại không
        if($user) {
            // Kiểm tra xem role_id của người dùng có phải là 1 không
            if($user->role_id != 1) {
                // Thử xóa người dùng
                $user->delete();

                // Chuyển hướng về trang danh sách người dùng và hiển thị thông báo thành công
                return redirect()->route('Users.list-user')->with('success', 'Successfully deleted user.');
            } else {
                // Nếu role_id là 1, không cho phép xóa và hiển thị thông báo lỗi
                return redirect()->route('Users.list-user')->with('error', 'This user cannot be deleted.');
            }
        } else {
            // Nếu không tìm thấy người dùng, hiển thị thông báo lỗi
            return redirect()->route('Users.list-user')->with('error', 'User does not exist.');
        }
    } catch (QueryException $e) {
        // Bắt lỗi nếu không thể xóa người dùng do liên kết khóa ngoại
        if($e->errorInfo[1] == 1451) {
            return redirect()->route('Users.list-user')->with('error', 'This user cannot be deleted due to associated data.');
        } else {
            return redirect()->route('Users.list-user')->with('error', 'An error occurred while deleting the user.');
        }
    }
}


}
