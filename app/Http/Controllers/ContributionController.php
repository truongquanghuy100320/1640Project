<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ContributionModel;
use App\Models\FacltiesModel;
use ZipArchive;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use App\Models\StaffModel;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class ContributionController extends Controller
{
    public function list_contribution()
    {
        // Lấy danh sách các bản ghi có dữ liệu trong cột 'word_document'
        $list_contribution = ContributionModel::all();

        // Duyệt qua từng contribution trong danh sách và xử lý dữ liệu
        foreach ($list_contribution as $contribution) {
            // Kiểm tra xem contribution có dữ liệu word_document không
            if ($contribution->word_document) {
                // Tiến hành xử lý và lưu tệp tạm thời nếu cần thiết
                $fileData = $contribution->word_document;

                // Tạo thư mục tạm thời nếu chưa tồn tại
                $tempDirectory = 'C:\xampp\htdocs\arduino\resources\temp\document';
                if (!file_exists($tempDirectory)) {
                    mkdir($tempDirectory, 0777, true);
                }
                $tempDirectory1 = 'C:\xampp\htdocs\arduino\resources\temp\ZIP';
                if (!file_exists($tempDirectory1)) {
                    mkdir($tempDirectory1, 0777, true);
                }
                $faculty = FacltiesModel::where('faculty_id', $contribution->faculty_id)->first();
           $facultyName = $faculty ? $faculty->faculty_name : 'Unknown';

                // Tạo tên file tạm thời dựa trên ID của bản ghi
                $tempFileName = 'document_' . $contribution->contribution_id . '_' . $contribution->student_id . '_' . $contribution->faculty_id . '_' . $facultyName  . '.docx';
                $tempFileName1 = 'contributions_' . $contribution->contribution_id . '_' . $contribution->student_id . '_' . $contribution->faculty_id . '_' . $facultyName  . '.zip';

                // Tạo đường dẫn tệp tạm thời
                $tempFilePath = $tempDirectory . '/' . $tempFileName;
                $tempFilePath1 = $tempDirectory1 . '/' . $tempFileName1;

                // Ghi dữ liệu vào tệp tạm thời
                file_put_contents($tempFilePath, $fileData);
                file_put_contents($tempFilePath1, $fileData);

                // Lưu tên tệp và đường dẫn vào đối tượng contribution
                $contribution->file_name_docx = $tempFileName;
                $contribution->file_path_docx = $tempFilePath;
                $contribution->file_name_zip = $tempFileName1;
                $contribution->file_path_zip = $tempFilePath1;
            }
        }

        // Trả về view với danh sách bản ghi đã lọc và xử lý
        return view('contributions.list-contribution', ['list_contribution' => $list_contribution]);
    }

    public function download_All_Contributions(Request $request)
    {
        // Lấy tất cả contribution_id của bài tập đã hết hạn dựa vào expiration_date
        $expiredContributionIds = ContributionModel::where('expiration_date', '<', now())->pluck('contribution_id')->toArray();

    // Tạo thư mục lưu trữ tệp docx đã hết hạn
    $docxDirectory = 'C:\xampp\htdocs\arduino\resources\temp\documentOut';
    if (!file_exists($docxDirectory)) {
        mkdir($docxDirectory, 0777, true);
    }

    // Lấy thông tin của các bài tập dựa trên contribution_id đã hết hạn và lưu vào thư mục documentOut
    foreach ($expiredContributionIds as $contributionId) {
        $contribution = ContributionModel::where('contribution_id', $contributionId)->first();
        $faculty = FacltiesModel::where('faculty_id', $contribution->faculty_id)->first();
        $facultyName = $faculty ? $faculty->faculty_name : 'Unknown';

        if ($contribution) {
            $docxFileName = 'documentOut_' . $contribution->contribution_id . '_' . $contribution->student_id . '_' . $contribution->faculty_id . '_' . $facultyName .  '_' . $contribution->faculty_id . '.docx';
            $docxFilePath = $docxDirectory . DIRECTORY_SEPARATOR . $docxFileName;

            // Kiểm tra xem tệp docx đã tồn tại hay chưa trước khi lưu
            if (!file_exists($docxFilePath)) {
                // Lưu tệp docx
                $fileData = $contribution->word_document;
                file_put_contents($docxFilePath, $fileData);
            }
        }
    }

    // Tạo tệp ZIP chứa các tệp docx đã hết hạn
    $zip = new ZipArchive;
    $zipFileName = 'all_expired_contributions_' . now()->format('YmdHis') . '.zip';
    $zipFilePath = 'C:\xampp\htdocs\arduino\resources\temp\ZIPDOWNLOAD\\' . $zipFileName;

    if ($zip->open($zipFilePath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== TRUE) {
        return response()->json(['error' => 'Failed to create ZIP file'], 500);
    }

    // Thêm các tệp docx đã hết hạn vào tệp ZIP từ thư mục documentOut
    $docxFiles = glob($docxDirectory . '/*.docx');
    foreach ($docxFiles as $docxFilePath) {
        $docxFileName = pathinfo($docxFilePath, PATHINFO_BASENAME);
        $zip->addFile($docxFilePath, $docxFileName);
    }

    $zip->close();

    // Xóa các tệp docx đã lưu trong thư mục documentOut
    array_map('unlink', glob("$docxDirectory/*.*"));
    rmdir($docxDirectory);

    ContributionModel::whereIn('contribution_id', $expiredContributionIds)->update([
        'downloaded' => true,
        'download_date' => now()
    ]);

    // Trả về tệp ZIP đã tạo
    return response()->download($zipFilePath, $zipFileName);




    }

public function download_contribution_by_id( int $contributionId){
    $contribution = ContributionModel::find($contributionId);
    if (!$contribution) {
        return response()->json(['error' => 'Contribution not found'], 404);
    }

    // Tạo thư mục lưu trữ tệp docx đã hết hạn
    $docxDirectory = 'C:\xampp\htdocs\arduino\resources\temp\documentOut';
    if (!file_exists($docxDirectory)) {
        mkdir($docxDirectory, 0777, true);
    }

    // Tạo tên tệp ZIP
    $zipFileName = 'contribution_' . $contributionId . '_expired_' . now()->format('YmdHis') . '.zip';
    $zipFilePath = 'C:\xampp\htdocs\arduino\resources\temp\ZIPDOWNLOAD\\' . $zipFileName;

    // Tạo một tệp ZIP mới
    $zip = new ZipArchive;
    if ($zip->open($zipFilePath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== TRUE) {
        return response()->json(['error' => 'Failed to create ZIP file'], 500);
    }

    // Thêm tệp docx của contribution vào tệp ZIP
    $docxFileName = 'documentOut_' . $contribution->contribution_id . '_' . $contribution->student_id . '_' . $contribution->faculty_id . '.docx';
    $docxFilePath = $docxDirectory . DIRECTORY_SEPARATOR . $docxFileName;

    if (!file_exists($docxFilePath)) {
        // Lưu tệp docx nếu chưa tồn tại
        file_put_contents($docxFilePath, $contribution->word_document);
    }

    $zip->addFile($docxFilePath, $docxFileName);
    $zip->close();

    // Xóa tệp docx sau khi đã thêm vào tệp ZIP
    unlink($docxFilePath);
    ContributionModel::where('contribution_id', $contributionId)->update([
        'downloaded' => true,
        'download_date' => now()
    ]);

    // Trả về tệp ZIP đã tạo để tải xuống
    return response()->download($zipFilePath, $zipFileName);


}

public function test_mail(Request $request){
    // Lấy staff_id từ session
    $staffId = Session::get('staff_id');

    // Kiểm tra xem staff_id có tồn tại không
    if (!$staffId) {
        return response()->json(['error' => 'Staff ID not found in session'], 404);
    }

    // Lấy thông tin về nhân viên từ bảng tbl_staff
    $staff = StaffModel::where('staff_id', $staffId)->first();

    // Kiểm tra xem thông tin nhân viên có tồn tại không
    if (!$staff) {
        return response()->json(['error' => 'Staff not found'], 404);
    }

    // Gửi email
    $name = 'test name';
    Mail::send('contributions.test', compact('name'), function ($email) use ($staff) {
        $email->to($staff->email, $staff->name);
    });

    return response()->json(['message' => 'Email sent successfully'], 200);
}



//my



public function all_list_contribution()
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

        if ($staff->role_id == 1) {
            $super = ContributionModel::all();
//            $manage_contribution_super = view('contributions.all-list-contribution')-> with('all_list_contribution',$super);
            foreach ($super as $contribution) {
                // Kiểm tra xem contribution có dữ liệu word_document không
                if ($contribution->word_document) {
                    // Tiến hành xử lý và lưu tệp tạm thời nếu cần thiết
                    $fileData = $contribution->word_document;

                    // Tạo thư mục tạm thời nếu chưa tồn tại
                    $tempDirectory = 'C:\xampp\htdocs\arduino\resources\temp\document';
                    if (!file_exists($tempDirectory)) {
                        mkdir($tempDirectory, 0777, true);
                    }

                    // Tạo tên file tạm thời dựa trên ID của bản ghi
                    $tempFileName = 'document_' . $contribution->contribution_id . '_' . $contribution->staff_id . '_' . $contribution->faculty_id . '_' . '.docx';

                    // Tạo đường dẫn tệp tạm thời
                    $tempFilePath = $tempDirectory . '/' . $tempFileName;

                    // Ghi dữ liệu vào tệp tạm thời
                    file_put_contents($tempFilePath, $fileData);

                    // Lưu tên tệp và đường dẫn vào đối tượng contribution
                    $contribution->file_name = $tempFileName;
                    $contribution->file_path = $tempFilePath;
                }
            }


            // Trả về view 'layout' với danh sách bài đóng góp
//            return view('layout')->with('contributions.all_list_contribution', $manage_contribution_super);
            return view('contributions.all-list-contribution', ['all_list_contribution' => $super]);
        }


        // Lấy danh sách bài đóng góp dựa trên faculty_id của nhân viên
        $contributions = DB::table('tbl_contributions')
            ->where('faculty_id', $staff->faculty_id)
            ->get();


        // Lấy danh sách các bản ghi có dữ liệu trong cột 'word_document'
        // Duyệt qua từng contribution trong danh sách và xử lý dữ liệu
        foreach ($contributions as $contribution) {
            // Kiểm tra xem contribution có dữ liệu word_document không
            if ($contribution->word_document) {
                // Tiến hành xử lý và lưu tệp tạm thời nếu cần thiết
                $fileData = $contribution->word_document;

                // Tạo thư mục tạm thời nếu chưa tồn tại
                $tempDirectory = 'C:\xampp\htdocs\arduino\resources\temp\document';
                if (!file_exists($tempDirectory)) {
                    mkdir($tempDirectory, 0777, true);
                }

                // Tạo tên file tạm thời dựa trên ID của bản ghi
                $tempFileName = 'document_' . $contribution->contribution_id . '_' . $contribution->staff_id . '_' . $contribution->faculty_id . '_' . '.docx';

                // Tạo đường dẫn tệp tạm thời
                $tempFilePath = $tempDirectory . '/' . $tempFileName;

                // Ghi dữ liệu vào tệp tạm thời
                file_put_contents($tempFilePath, $fileData);

                // Lưu tên tệp và đường dẫn vào đối tượng contribution
                $contribution->file_name = $tempFileName;
                $contribution->file_path = $tempFilePath;
            }
        }

        // Trả về view với danh sách bản ghi đã lọc và xử lý
//        return view('contributions.all-list-contribution', ['all_list_contribution' => $contribution]);

        $manage_contribution = view('contributions.all-list-contribution')->with('all_list_contribution', $contributions);

        // Trả về view 'layout' với danh sách bài đóng góp
        return view('layout')->with('contributions.all_list_contribution', $manage_contribution);
    }


    //ĐANG KHÔNG DƯỢC DUYỆT THÀNH ĐƯỢC DUYẾT
    public function notApprove_contribution($contribution_id)
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

        if ($staff->role_id == 1 || $staff->role_id == 2) {
            DB::table('tbl_contributions')->where('contribution_id', $contribution_id)->update(['status' => 1]);
            Session::put('message', 'Contribution has updated its status to "not approved" ');
            return Redirect::to('all-list-contribution');
        }


        // Lấy danh sách bài đóng góp dựa trên faculty_id của nhân viên
        DB::table('tbl_contributions')
            ->where('faculty_id', $staff->faculty_id)
            ->update(['status' => 1]);
        Session::put('message', 'Contribution has updated its status to "not approved" ');
        return Redirect::to('all-list-contribution');
    }


    //TỪ ĐANG DUYẾT THÀNH KHÔNG DƯỢC DUYỆT
    public function approve_contribution($contribution_id)
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

        if ($staff->role_id == 1 || $staff->role_id == 2) {
            DB::table('tbl_contributions')->where('contribution_id', $contribution_id)->update(['status' => 2]);
            Session::put('message', 'Contribution has updated its status to "not approved" ');
            return Redirect::to('all-list-contribution');
        }


        // Lấy danh sách bài đóng góp dựa trên faculty_id của nhân viên
        DB::table('tbl_contributions')
            ->where('faculty_id', $staff->faculty_id)
            ->update(['status' => 2]);
        Session::put('message', 'Contribution has updated its status to " approved" ');
        return Redirect::to('all-list-contribution');
    }

    public function pending_contribution($contribution_id)
    {
        DB::table('tbl_contributions')->where('contribution_id', $contribution_id)->get();
        return Redirect::to('all-list-contribution');
    }


    //EDIT FUNCTION
    //GO TO EDIT PAGE
    public function edit_contribution($contribution_id)
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
            return redirect()->route('login.login')->with('error', 'Not found information staff');
        }

        if ($staff->role_id == 1) {
            $edit_super = DB::table('tbl_contributions')->where('contribution_id', $contribution_id)->get();
            $manage_edit_contribution = view('contributions.edit-contribution')->with('edit_contribution', $edit_super);
            return view('layout')->with('contributions.edit-contribution', $manage_edit_contribution);
        }

        $edit_contribution = DB::table('tbl_contributions')
            ->where('faculty_id', $staff->faculty_id)
            ->where('contribution_id', $contribution_id)
            ->get();

        $edit = DB::table('tbl_contributions')->where('contribution_id', $contribution_id)->get();


        //trả ra thông báo nếu không có đóng góp trong khoa
        if ($edit != $edit_contribution) {

            Session::put('error1', 'YOU CAN NOT SEE THIS CONTRIBUTION');
            return redirect('all-list-contribution');
        }

        $manage_edit_contribution = view('contributions.edit-contribution')->with('edit_contribution', $edit_contribution);


        return view('layout')->with('contributions.edit-contribution', $manage_edit_contribution);
    }

    //update data
    public function update_contribution(Request $request, $contribution_id)
    {
        $request->validate([
            'content_contribution' => 'required',
            'title_contribution' => 'required',
            'ex_date' => 'required',
            'image_contribution' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'word_document' => 'file|mimes:docx,pdf,doc,txt|max:2048'
        ], [
            'content_contribution.required' => 'Enter new content please!',
            'title_contribution.required' => 'Enter new title please!',
            'ex_date.required' => 'Enter new date please!',

        ]);

        $data = array();
        $data['content'] = $request->content_contribution;
        $data['title'] = $request->title_contribution;
        $data['expiration_date'] = $request->ex_date;

//        $contribution = DB::table('tbl_contributions')->where('contribution_id',$contribution_id)->where('image_url')->get();

        $contribution = ContributionModel::find($contribution_id);

        //image
        $get_image = $request->file('image_contribution');


        if ($request->hasFile('image_contribution')) {
            if ($contribution->image_contribution) {
                $oldImagePath = ('public/uploads/image_contribution' . $contribution->image_contribution);
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }

            $get_name_image = $get_image->getClientOriginalName();
            $name_image = current(explode('.', $get_name_image));
            $new_image = $name_image . '.' . $get_image->getClientOriginalExtension();
            $get_image->move('public/uploads/image_contribution', $new_image);

            $data['image_url'] = $new_image;

            DB::table('tbl_contributions')->where('contribution_id', $contribution_id)->update($data);

            Session::put('message_update', 'Contribution information has been updated');
            return Redirect::to('all-list-contribution');
        }

        //word
        $get_file = $request->file('document_contribution');
        $document = 'C:\xampp\htdocs\arduino\resources\temp\document';

        if ($get_file) {
            $get_name_file = $get_file->getClientOriginalName();
            $name_file = current(explode('.', $get_name_file));
            $new_file = $name_file . '.' . $get_file->getClientOriginalExtension();
            $get_file->move($document, $new_file);
            $data['word_document'] = $new_file;

            DB::table('tbl_contributions')->where('contribution_id', $contribution_id)->update($data);
            Session::put('message_update', 'Contribution information has been updated');
            return Redirect::to('all-list-contribution');
        }

        DB::table('tbl_contributions')->where('contribution_id', $contribution_id)->update($data);
        Session::put('message_update', 'Contribution information has been updated');
        return Redirect::to('all-list-contribution');

    }

    public function cancel_edit()
    {
        Session::put('message_cancel', 'Cancel edit!');
        return Redirect::to('all-list-contribution');
    }


    //DETAIL CONTRIBUTION FUNCTION
    public function show_contribution(Request $request, $contribution_id)
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

        if ($staff->role_id == 1) {

            $show_contribution_super = DB::table('tbl_contributions')->where('contribution_id', $contribution_id)->get();

            foreach ($show_contribution_super as $contribution) {
                // Kiểm tra xem contribution có dữ liệu word_document không
                if ($contribution->word_document) {
                    // Tiến hành xử lý và lưu tệp tạm thời nếu cần thiết
                    $fileData = $contribution->word_document;

                    // Tạo thư mục tạm thời nếu chưa tồn tại
                    $tempDirectory = 'C:\xampp\htdocs\arduino\resources\temp\document';
                    if (!file_exists($tempDirectory)) {
                        mkdir($tempDirectory, 0777, true);
                    }

                    // Tạo tên file tạm thời dựa trên ID của bản ghi
                    $tempFileName = 'document_' . $contribution->contribution_id . '_' . $contribution->staff_id . '_' . $contribution->faculty_id . '_' . '.docx';


                    // Tạo đường dẫn tệp tạm thời
                    $tempFilePath = $tempDirectory . '/' . $tempFileName;

                    // Ghi dữ liệu vào tệp tạm thời
                    file_put_contents($tempFilePath, $fileData); //ghi content vào tệp tạm thời

                    // Lưu tên tệp và đường dẫn vào đối tượng contribution
                    $contribution->file_name = $tempFileName; //lưu tên của file word tạm thời
                    $contribution->file_path = $tempFilePath; //lưu dg dẫn
                }
            }


            $manage_show_contribution = view('contributions.show-contribution')->with('show_contribution', $show_contribution_super);

            return view('layout')->with('contributions.show-contribution', $manage_show_contribution);
        }

        $show_contribution = DB::table('tbl_contributions')
            ->where('faculty_id', $staff->faculty_id)
            ->where('contribution_id', $contribution_id)
            ->get();

        $edit = DB::table('tbl_contributions')->where('contribution_id', $contribution_id)->get();


        //trả ra thông báo nếu không có đóng góp trong khoa
        if ($edit != $show_contribution) {

            Session::put('error1', 'YOU CAN NOT SEE THIS CONTRIBUTION');
            return redirect('all-list-contribution');
        }

        foreach ($show_contribution as $contribution) {
            // Kiểm tra xem contribution có dữ liệu word_document không
            if ($contribution->word_document) {
                // Tiến hành xử lý và lưu tệp tạm thời nếu cần thiết
                $fileData = $contribution->word_document;

                // Tạo thư mục tạm thời nếu chưa tồn tại
                $tempDirectory = 'C:\xampp\htdocs\arduino\resources\temp\document';
                if (!file_exists($tempDirectory)) {
                    mkdir($tempDirectory, 0777, true);
                }

                // Tạo tên file tạm thời dựa trên ID của bản ghi
                $tempFileName = 'document_' . $contribution->contribution_id . '_' . $contribution->staff_id . '_' . $contribution->faculty_id . '_' . '.docx';


                // Tạo đường dẫn tệp tạm thời
                $tempFilePath = $tempDirectory . '/' . $tempFileName;

                // Ghi dữ liệu vào tệp tạm thời
                file_put_contents($tempFilePath, $fileData); //ghi content vào tệp tạm thời

                // Lưu tên tệp và đường dẫn vào đối tượng contribution
                $contribution->file_name = $tempFileName; //lưu tên của file word tạm thời
                $contribution->file_path = $tempFilePath; //lưu dg dẫn
            }
        }


        $manage_show_contribution = view('contributions.show-contribution')->with('show_contribution', $show_contribution);

        return view('layout')->with('contributions.show-contribution', $manage_show_contribution);
    }


    //REMOVE FUNCTION
    public function remove($contribution_id)
    {
        $super = DB::table('tbl_contributions')->where('contribution_id', $contribution_id)->delete();
        Session::put('message_delete', 'Delete successful.');
//            Session::put('message_delete','YOU CAN NOT DELETE CONTRIBUTION');
        return Redirect::to('all-list-contribution');
    }


    //DOWNLOAD CONTRIBUTION BY ID FUNCTION
    public function downloard_contribution(Request $request, $contribution_id)
    {
        $show_contribution = DB::table('tbl_contributions')
            ->where('contribution_id', $contribution_id)
            ->get();

        foreach ($show_contribution as $contribution) {
            // Kiểm tra xem contribution có dữ liệu word_document không
            if ($contribution->word_document) {
                // Tiến hành xử lý và lưu tệp tạm thời nếu cần thiết
                $fileData = $contribution->word_document;

                // Tạo thư mục tạm thời nếu chưa tồn tại
                $tempDirectory = 'C:\xampp\htdocs\arduino\resources\tempt\document';
                if (!file_exists($tempDirectory)) {
                    mkdir($tempDirectory, 0777, true);
                }


                // Tạo tên file tạm thời dựa trên ID của bản ghi
                $filename = 'document_' . $contribution->contribution_id . '_' . $contribution->staff_id . '_' . $contribution->faculty_id . '_' . '.zip';


                // Tạo đường dẫn tệp tạm thời
                $tempFilePath = $tempDirectory . '/' . $filename;

                // Ghi dữ liệu vào tệp tạm thời
                file_put_contents($tempFilePath, $fileData); //ghi content vào tệp tạm thời

                // Lưu tên tệp và đường dẫn vào đối tượng contribution
                $contribution->file_name = $filename; //lưu tên của file word tạm thời
                $contribution->file_path = $tempFilePath; //lưu dg dẫn
            }

        }

        $zip = new ZipArchive();

        if ($zip->open(public_path($filename), ZipArchive::CREATE) === TRUE) {
            foreach ($show_contribution as $contribution) {
                // Kiểm tra xem contribution có dữ liệu word_document không
                if ($contribution->word_document) {
                    // Tiến hành xử lý và lưu tệp tạm thời nếu cần thiết
                    $fileData = $contribution->word_document;

                    // Tạo thư mục tạm thời nếu chưa tồn tại
                    $tempDirectory = 'C:\xampp\htdocs\arduino\resources\tempt\document';
                    if (!file_exists($tempDirectory)) {
                        mkdir($tempDirectory, 0777, true);
                    }


                    // Tạo tên file tạm thời dựa trên ID của bản ghi
                    $tempFileName = 'document_' . $contribution->contribution_id . '_' . $contribution->staff_id . '_' . $contribution->faculty_id . '_' . '.docx';


                    // Tạo đường dẫn tệp tạm thời
                    $tempFilePath = $tempDirectory . '/' . $tempFileName;

                    // Ghi dữ liệu vào tệp tạm thời
                    file_put_contents($tempFilePath, $fileData); //ghi content vào tệp tạm thời

                    // Lưu tên tệp và đường dẫn vào đối tượng contribution
                    $contribution->file_name = $tempFileName; //lưu tên của file word tạm thời
                    $contribution->file_path = $tempFilePath; //lưu dg dẫn
                }
                $relativeNameInZipFile = basename($tempFileName);
                $zip->addFile($tempFilePath, $relativeNameInZipFile);
            }
            $zip->close();
        }
        return response()->download(public_path($filename));
    }

}